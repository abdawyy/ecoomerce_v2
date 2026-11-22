<?php

namespace App\Services;

use App\Models\CatalogFilterGroup;
use App\Models\CatalogFilterOption;
use App\Models\Category;
use App\Models\productItems;
use App\Models\products;
use App\Models\Season;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CatalogFilterService
{
    public const KEYS = ['category', 'price', 'type', 'season', 'color', 'size', 'stock', 'on_sale'];

    public function ready(): bool
    {
        return Schema::hasTable('catalog_filter_groups') && Schema::hasTable('catalog_filter_options');
    }

    public function priceBand(mixed $id): ?array
    {
        return $this->priceBands([$id])[0] ?? null;
    }

    /**
     * @param  array<int, mixed>  $ids
     * @return array<int, array{id: int, label: string, min: mixed, max: mixed}>
     */
    public function priceBands(array $ids): array
    {
        if (! $this->ready()) {
            return [];
        }

        $ids = collect($ids)
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->all();

        if ($ids === []) {
            return [];
        }

        return CatalogFilterOption::query()
            ->where('group_key', 'price')
            ->whereIn('id', $ids)
            ->get()
            ->map(fn ($band) => [
                'id' => $band->id,
                'label' => $this->optionLabel($band),
                'min' => $band->min_price,
                'max' => $band->max_price,
            ])
            ->values()
            ->all();
    }

    /**
     * @return array{configured: bool, groups: array<int, array<string, mixed>>}
     */
    public function forStorefront(): array
    {
        $catalog = $this->catalogChoices();
        $saved = $this->savedState();

        $groups = [];
        foreach ($this->orderedKeys($saved) as $key) {
            if ($saved['configured'] && ! ($saved['groups'][$key]['visible'] ?? true)) {
                continue;
            }

            $group = $this->presentGroup($key, $catalog, $saved);
            if ($group !== null) {
                $groups[] = $group;
            }
        }

        return [
            'configured' => $saved['configured'],
            'groups' => $groups,
        ];
    }

    /**
     * @return array{ready: bool, groups: array<int, array<string, mixed>>}
     */
    public function forAdmin(): array
    {
        $catalog = $this->catalogChoices();
        $saved = $this->savedState();
        $groups = [];

        foreach ($this->orderedKeys($saved) as $position => $key) {
            $state = $saved['groups'][$key] ?? null;
            $selected = $saved['options'][$key] ?? collect();
            $groups[] = [
                'key' => $key,
                'visible' => $state['visible'] ?? true,
                'sort' => $state['sort'] ?? $position,
                'choices' => $this->adminChoices($key, $catalog, $selected),
                'bands' => $key === 'price' ? $selected->values()->all() : [],
            ];
        }

        return [
            'ready' => $this->ready(),
            'groups' => $groups,
        ];
    }

    public function save(Request $request): void
    {
        $catalog = $this->catalogChoices();

        foreach (self::KEYS as $position => $key) {
            CatalogFilterGroup::query()->updateOrCreate(
                ['key' => $key],
                [
                    'is_visible' => $request->boolean("groups.$key.visible"),
                    'sort_order' => max(0, (int) $request->input("groups.$key.sort", $position)),
                ]
            );
        }

        $this->syncChoices('category', $request->input('options.category', []), $catalog['categories']);
        $this->syncChoices('type', $request->input('options.type', []), $catalog['types']);
        $this->syncChoices('season', $request->input('options.season', []), $catalog['seasons']);
        $this->syncChoices('color', $request->input('options.color', []), $catalog['colors']);
        $this->syncChoices('size', $request->input('options.size', []), $catalog['sizes']);
        $this->syncBands($request);
    }

    /**
     * @param  array<string, mixed>  $saved
     * @return array<int, string>
     */
    protected function orderedKeys(array $saved): array
    {
        $keys = self::KEYS;
        usort($keys, function (string $a, string $b) use ($saved) {
            $left = $saved['groups'][$a]['sort'] ?? array_search($a, self::KEYS, true);
            $right = $saved['groups'][$b]['sort'] ?? array_search($b, self::KEYS, true);

            return $left <=> $right;
        });

        return $keys;
    }

    /**
     * @param  array<string, mixed>  $catalog
     * @param  array<string, mixed>  $saved
     * @return array<string, mixed>|null
     */
    protected function presentGroup(string $key, array $catalog, array $saved): ?array
    {
        $selected = $saved['options'][$key] ?? collect();

        if ($key === 'price') {
            if ($selected->isNotEmpty()) {
                return [
                    'key' => 'price',
                    'kind' => 'price_bands',
                    'choices' => $selected->map(fn (CatalogFilterOption $band) => [
                        'value' => (string) $band->id,
                        'label' => $this->optionLabel($band),
                    ])->all(),
                ];
            }

            return [
                'key' => 'price',
                'kind' => 'price_range',
                'choices' => [],
            ];
        }

        if (in_array($key, ['stock', 'on_sale'], true)) {
            return [
                'key' => $key,
                'kind' => $key,
                'choices' => [],
            ];
        }

        $choices = $this->storeChoices($key, $catalog, $selected);
        if ($choices === []) {
            return null;
        }

        return [
            'key' => $key,
            'kind' => $key,
            'choices' => $choices,
        ];
    }

    /**
     * @param  array<string, mixed>  $catalog
     * @param  \Illuminate\Support\Collection<int, CatalogFilterOption>  $selected
     * @return array<int, array{value: string, label: string}>
     */
    protected function storeChoices(string $key, array $catalog, $selected): array
    {
        $pool = match ($key) {
            'category' => $catalog['categories'],
            'type' => $catalog['types'],
            'season' => $catalog['seasons'],
            'color' => $catalog['colors'],
            'size' => $catalog['sizes'],
            default => [],
        };

        if ($selected->isEmpty()) {
            return array_map(fn (array $choice) => [
                'value' => (string) $choice['value'],
                'label' => $choice['label'],
            ], $pool);
        }

        $allowed = $selected->pluck('value')->map(fn ($value) => (string) $value)->all();
        $byValue = collect($pool)->keyBy(fn (array $choice) => (string) $choice['value']);
        $choices = [];
        foreach ($allowed as $value) {
            if ($byValue->has($value)) {
                $choice = $byValue->get($value);
                $choices[] = [
                    'value' => (string) $choice['value'],
                    'label' => $choice['label'],
                ];
            }
        }

        return $choices;
    }

    /**
     * @param  array<string, mixed>  $catalog
     * @param  \Illuminate\Support\Collection<int, CatalogFilterOption>  $selected
     * @return array<int, array{value: string, label: string, checked: bool}>
     */
    protected function adminChoices(string $key, array $catalog, $selected): array
    {
        if (! in_array($key, ['category', 'type', 'season', 'color', 'size'], true)) {
            return [];
        }

        $checked = $selected->pluck('value')->map(fn ($value) => (string) $value)->all();
        $pool = match ($key) {
            'category' => $catalog['categories'],
            'type' => $catalog['types'],
            'season' => $catalog['seasons'],
            'color' => $catalog['colors'],
            'size' => $catalog['sizes'],
            default => [],
        };

        return array_map(fn (array $choice) => [
            'value' => (string) $choice['value'],
            'label' => $choice['label'],
            'checked' => in_array((string) $choice['value'], $checked, true),
        ], $pool);
    }

    /**
     * @return array{configured: bool, groups: array<string, array{visible: bool, sort: int}>, options: array<string, \Illuminate\Support\Collection>}
     */
    protected function savedState(): array
    {
        if (! $this->ready() || ! CatalogFilterGroup::query()->exists()) {
            return [
                'configured' => false,
                'groups' => [],
                'options' => [],
            ];
        }

        $groups = [];
        foreach (CatalogFilterGroup::query()->get() as $group) {
            $groups[$group->key] = [
                'visible' => (bool) $group->is_visible,
                'sort' => (int) $group->sort_order,
            ];
        }

        $options = CatalogFilterOption::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get()
            ->groupBy('group_key');

        return [
            'configured' => true,
            'groups' => $groups,
            'options' => $options->all(),
        ];
    }

    /**
     * @return array{categories: array, types: array, seasons: array, colors: array, sizes: array}
     */
    protected function catalogChoices(): array
    {
        $categories = Category::query()
            ->where('is_active', 1)
            ->orderBy('name')
            ->get()
            ->map(fn (Category $category) => [
                'value' => (string) $category->id,
                'label' => $category->name,
            ])
            ->all();

        $types = Type::query()
            ->where('is_active', 1)
            ->orderBy('name')
            ->get()
            ->map(fn (Type $type) => [
                'value' => (string) $type->id,
                'label' => $type->name,
            ])
            ->all();

        $seasons = Schema::hasTable('seasons')
            ? Season::query()
                ->where('is_active', 1)
                ->orderBy('name')
                ->get()
                ->map(fn (Season $season) => [
                    'value' => (string) $season->id,
                    'label' => $season->name,
                ])
                ->all()
            : [];

        $colors = products::query()->sellable()->distinct()->orderBy('color')->pluck('color')
            ->filter(fn ($color) => trim((string) $color) !== '')
            ->map(fn ($color) => ['value' => (string) $color, 'label' => (string) $color])
            ->values()
            ->all();

        $sizes = productItems::query()
            ->whereHas('products', fn ($query) => $query->sellable())
            ->distinct()
            ->orderBy('size')
            ->pluck('size')
            ->filter(fn ($size) => trim((string) $size) !== '')
            ->map(fn ($size) => ['value' => (string) $size, 'label' => (string) $size])
            ->values()
            ->all();

        return compact('categories', 'types', 'seasons', 'colors', 'sizes');
    }

    /**
     * @param  array<int, mixed>  $submitted
     * @param  array<int, array{value: string, label: string}>  $allowed
     */
    protected function syncChoices(string $key, array $submitted, array $allowed): void
    {
        $allowedValues = collect($allowed)->pluck('value')->map(fn ($value) => (string) $value)->all();
        $values = collect($submitted)
            ->map(fn ($value) => (string) $value)
            ->filter(fn ($value) => in_array($value, $allowedValues, true))
            ->unique()
            ->values();

        CatalogFilterOption::query()->where('group_key', $key)->delete();

        foreach ($values as $index => $value) {
            CatalogFilterOption::query()->create([
                'group_key' => $key,
                'value' => $value,
                'sort_order' => $index,
            ]);
        }
    }

    protected function syncBands(Request $request): void
    {
        $delete = collect($request->input('bands_delete', []))
            ->filter(fn ($id) => is_numeric($id))
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($delete !== []) {
            CatalogFilterOption::query()
                ->where('group_key', 'price')
                ->whereIn('id', $delete)
                ->delete();
        }

        foreach ((array) $request->input('bands', []) as $id => $row) {
            if (! is_numeric($id) || in_array((int) $id, $delete, true)) {
                continue;
            }
            $payload = $this->bandPayload($row);
            if ($payload === null) {
                continue;
            }
            CatalogFilterOption::query()
                ->where('group_key', 'price')
                ->whereKey((int) $id)
                ->update($payload);
        }

        $nextSort = (int) CatalogFilterOption::query()->where('group_key', 'price')->max('sort_order');
        foreach ((array) $request->input('bands_new', []) as $row) {
            $payload = $this->bandPayload($row);
            if ($payload === null) {
                continue;
            }
            $nextSort++;
            CatalogFilterOption::query()->create($payload + [
                'group_key' => 'price',
                'sort_order' => $nextSort,
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>|null
     */
    protected function bandPayload(array $row): ?array
    {
        $labelEn = trim((string) ($row['label_en'] ?? ''));
        $labelAr = trim((string) ($row['label_ar'] ?? ''));
        $min = $row['min_price'] ?? null;
        $max = $row['max_price'] ?? null;
        $min = $min === '' || $min === null ? null : $min;
        $max = $max === '' || $max === null ? null : $max;

        if ($labelEn === '' && $labelAr === '' && $min === null && $max === null) {
            return null;
        }

        if ($labelEn === '' || ($min === null && $max === null)) {
            return null;
        }

        if ($min !== null && $max !== null && (float) $min > (float) $max) {
            return null;
        }

        return [
            'label_en' => $labelEn,
            'label_ar' => $labelAr !== '' ? $labelAr : null,
            'min_price' => $min,
            'max_price' => $max,
        ];
    }

    protected function optionLabel(CatalogFilterOption $option): string
    {
        $locale = app()->getLocale();
        $label = $locale === 'ar'
            ? ($option->label_ar ?: $option->label_en)
            : ($option->label_en ?: $option->label_ar);

        return trim((string) $label);
    }
}
