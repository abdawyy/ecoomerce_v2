<?php

namespace App\Http\Controllers;

use App\Models\CatalogFilterOption;
use App\Models\Category;
use App\Models\HomeCard;
use App\Models\productItems;
use App\Models\products;
use App\Models\Season;
use App\Models\SiteSetting;
use App\Models\Type;
use App\Services\BrandingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class HomeCardController extends Controller
{
    public function edit(BrandingService $branding)
    {
        return view('admin.home-cards.edit', [
            'cards' => Schema::hasTable('home_cards')
                ? HomeCard::query()->orderBy('sort_order')->orderBy('id')->get()
                : collect(),
            'choices' => $this->choices(),
            'branding' => $branding,
            'ready' => Schema::hasTable('home_cards'),
        ]);
    }

    public function update(Request $request, BrandingService $branding)
    {
        if (! Schema::hasTable('home_cards')) {
            return back()->with('error', __('home_cards.missing_table'));
        }

        if (! $request->boolean('cards_present')) {
            return back()->with('error', __('home_cards.invalid_form'));
        }

        $request->validate([
            'cards' => 'nullable|array|max:6',
            'cards.*.id' => 'nullable|integer',
            'cards.*.title_en' => 'nullable|string|max:80',
            'cards.*.title_ar' => 'nullable|string|max:80',
            'cards.*.filter_type' => 'required|in:'.implode(',', HomeCard::FILTER_TYPES),
            'cards.*.filter_value' => 'nullable|string|max:191',
            'cards.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $cards = array_values($request->input('cards', []));
        $choices = $this->choices();
        $existing = HomeCard::query()->get()->keyBy('id');
        $errors = [];

        foreach ($cards as $index => $card) {
            $id = isset($card['id']) && $card['id'] !== '' ? (int) $card['id'] : null;
            $type = (string) ($card['filter_type'] ?? 'all');
            $value = trim((string) ($card['filter_value'] ?? ''));

            if ($id && ! $existing->has($id)) {
                $errors["cards.$index.id"] = __('home_cards.invalid_card');
            }

            if ($type !== 'all') {
                $allowed = collect($choices[$type] ?? [])->pluck('value')->map(fn ($item) => (string) $item)->all();
                if ($value === '' || ! in_array($value, $allowed, true)) {
                    $errors["cards.$index.filter_value"] = __('home_cards.invalid_filter');
                }
            }

            $hasImage = $request->hasFile("cards.$index.image")
                || ($id && filled($existing->get($id)?->image_path));
            if (! $hasImage) {
                $errors["cards.$index.image"] = __('home_cards.image_required');
            }
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }

        DB::transaction(function () use ($cards, $existing, $request, $branding) {
            $keep = [];

            foreach ($cards as $index => $card) {
                $id = isset($card['id']) && $card['id'] !== '' ? (int) $card['id'] : null;
                $model = $id ? $existing->get($id) : new HomeCard();
                $type = (string) $card['filter_type'];

                $model->sort_order = $index + 1;
                $model->title_en = $this->cleanText($card['title_en'] ?? null);
                $model->title_ar = $this->cleanText($card['title_ar'] ?? null);
                $model->filter_type = $type;
                $model->filter_value = $type === 'all' ? null : trim((string) $card['filter_value']);

                if ($request->hasFile("cards.$index.image")) {
                    $this->releaseImage($branding, $model->image_path);
                    $model->image_path = $branding->uploadFile($request->file("cards.$index.image"), 'home_card');
                }

                $model->save();
                $keep[] = $model->id;
            }

            $removed = HomeCard::query()
                ->when($keep !== [], fn ($query) => $query->whereNotIn('id', $keep))
                ->get();

            foreach ($removed as $card) {
                $this->releaseImage($branding, $card->image_path);
                $card->delete();
            }
        });

        return redirect()->route('admin.homeCards.edit')->with('success', __('home_cards.saved'));
    }

    /**
     * @return array<string, array<int, array{value: string, label: string}>>
     */
    protected function choices(): array
    {
        $categories = Category::query()->orderBy('name')->get()
            ->map(fn (Category $category) => [
                'value' => (string) $category->id,
                'label' => $category->name.($category->is_active ? '' : ' ('.__('branding.inactive').')'),
            ])->values()->all();

        $types = Type::query()->orderBy('name')->get()
            ->map(fn (Type $type) => [
                'value' => (string) $type->id,
                'label' => $type->name,
            ])->values()->all();

        $seasons = Schema::hasTable('seasons')
            ? Season::query()->orderBy('name')->get()->map(fn (Season $season) => [
                'value' => (string) $season->id,
                'label' => $season->name,
            ])->values()->all()
            : [];

        $colors = products::query()->sellable()->distinct()->orderBy('color')->pluck('color')
            ->filter(fn ($color) => trim((string) $color) !== '')
            ->map(fn ($color) => ['value' => (string) $color, 'label' => (string) $color])
            ->values()->all();

        $sizes = productItems::query()
            ->whereHas('products', fn ($query) => $query->sellable())
            ->distinct()
            ->orderBy('size')
            ->pluck('size')
            ->filter(fn ($size) => trim((string) $size) !== '')
            ->map(fn ($size) => ['value' => (string) $size, 'label' => (string) $size])
            ->values()->all();

        $prices = Schema::hasTable('catalog_filter_options')
            ? CatalogFilterOption::query()->where('group_key', 'price')->orderBy('sort_order')->orderBy('id')->get()
                ->map(function (CatalogFilterOption $option) {
                    $label = app()->getLocale() === 'ar'
                        ? ($option->label_ar ?: $option->label_en)
                        : ($option->label_en ?: $option->label_ar);

                    return [
                        'value' => (string) $option->id,
                        'label' => trim((string) $label) !== '' ? trim((string) $label) : (string) $option->id,
                    ];
                })->values()->all()
            : [];

        return [
            'category' => $categories,
            'type' => $types,
            'season' => $seasons,
            'color' => $colors,
            'size' => $sizes,
            'price_band' => $prices,
            'stock' => [
                ['value' => 'in_stock', 'label' => __('web.in_stock')],
                ['value' => 'out_of_stock', 'label' => __('web.out_of_stock')],
            ],
        ];
    }

    protected function cleanText(mixed $value): ?string
    {
        $text = trim(strip_tags((string) $value));

        return $text === '' ? null : $text;
    }

    protected function releaseImage(BrandingService $branding, ?string $path): void
    {
        if (! $path) {
            return;
        }

        $settings = SiteSetting::current();
        if (in_array($path, [$settings->category_image_1_path, $settings->category_image_2_path], true)) {
            return;
        }

        $branding->deleteStoredFile($path);
    }
}
