<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\products;
use App\Models\productItems;
use App\Models\Category;
use App\Models\Type;
use App\Models\Season;
use App\Models\productImages;

use App\Models\Guide;
use App\Models\orders;
use App\Services\AnalyticsService;
use App\Services\CatalogFilterService;
use App\Services\MetaPixelService;
use App\Support\OrderStatus;
use App\Traits\Apptraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use Apptraits;
    public $model = 'App\Models\products';
    public $association = 'App\Models\productItems';

    public $url = '/admin/products';

    public $associationImage = 'App\Models\productImages';




    public $images = 'App\Models\productImages';
    public function edit(Request $request, $id = null)
    {
        // Retrieve the model instance to update (if $id is provided

        $model = $this->model::with('category', 'productItems', 'productImages')->find($id);




        if ($request->isMethod('post')) {
            if ($this->requestExceededPostMax($request)) {
                return redirect()->back()->withInput()->withErrors([
                    'images' => __('products.images_too_large'),
                ]);
            }

            // Validate the incoming request

            self::filterQuantities($request);

            $rules = [
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'sale' => 'nullable|numeric|min:0|max:100',
                'quantities' => 'nullable|array',
                'quantities.*' => 'nullable|numeric|min:0',
                'custom_size' => 'nullable|string|max:50',
                'custom_size_qty' => 'nullable|numeric|min:0',
                'category_id' => 'required|integer|exists:categories,id',
                'type_id' => 'required|integer|exists:type,id',
                'color' => 'required|string|max:255',
                'images' => 'nullable|array',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ];
            if (Schema::hasColumn('products', 'sku')) {
                $rules['sku'] = 'required|string|max:32|unique:products,sku,'.($id ?: 'NULL').',id';
            }
            if (Schema::hasColumn('products', 'season_id')) {
                $rules['season_id'] = 'required|integer|exists:seasons,id';
            }
            if (Schema::hasColumn('products', 'slug')) {
                $rules['slug'] = 'nullable|string|max:255|unique:products,slug,'.($id ?: 'NULL').',id';
                $rules['meta_title_en'] = 'nullable|string|max:255';
                $rules['meta_title_ar'] = 'nullable|string|max:255';
                $rules['meta_description_en'] = 'nullable|string|max:500';
                $rules['meta_description_ar'] = 'nullable|string|max:500';
            }
            $request->validate($rules);

            $values = [
                'name' => $request->name,
                'price' => $request->price,
                'sale' => $request->sale ?? 0,
                'category_id' => $request->category_id,
                'type_id' => $request->type_id,
                'color' => $request->color,
                'description' => $request->description,
            ];
            if (Schema::hasColumn('products', 'sku')) {
                $values['sku'] = trim((string) $request->sku);
            }
            if (Schema::hasColumn('products', 'season_id')) {
                $values['season_id'] = $request->season_id;
            }
            if (Schema::hasColumn('products', 'slug')) {
                $values['slug'] = $this->uniqueProductSlug($request->slug ?: Str::slug((string) $request->name), $id);
                $values['meta_title_en'] = $request->meta_title_en;
                $values['meta_title_ar'] = $request->meta_title_ar;
                $values['meta_description_en'] = $request->meta_description_en;
                $values['meta_description_ar'] = $request->meta_description_ar;
                $values['is_indexable'] = $request->boolean('is_indexable', true);
            }
            if (Schema::hasColumn('products', 'guide_id')) {
                $values['guide_id'] = $request->guide_id ?: null;
            }
            $quantities = $request->quantities ?? [];
            $customSize = trim((string) $request->input('custom_size', ''));
            if ($customSize !== '') {
                $customQty = $request->input('custom_size_qty');
                $quantities[$customSize] = ($customQty === null || $customQty === '') ? 0 : $customQty;
            }

            if (collect($quantities)->filter(fn ($qty) => $qty !== null && $qty !== '')->isEmpty()) {
                return redirect()->back()->withInput()->withErrors([
                    'quantities' => __('products.sizes_required'),
                ]);
            }

            if ($this->requestExceededPostMax($request)) {
                return redirect()->back()->withInput()->withErrors([
                    'images' => __('products.images_too_large'),
                ]);
            }

            foreach ($request->file('images', []) ?: [] as $file) {
                if ($file && ! $file->isValid()) {
                    return redirect()->back()->withInput()->withErrors([
                        'images' => __('products.images_too_large'),
                    ]);
                }
            }

            $storedFiles = [];
            $savedId = $id;

            try {
                DB::transaction(function () use ($request, $values, $id, $quantities, &$storedFiles, &$savedId) {
                    if ($id) {
                        $model = $this->model::query()->find($id);
                        if (! $model) {
                            throw new \RuntimeException('missing-product');
                        }
                        $model->fill($values);
                        $model->save();
                    } else {
                        $model = $this->model::query()->create($values);
                    }
                    $savedId = $model->id;

                    $this->storeUploadedImages($request, $model->id, $storedFiles);
                    $this->saveProductItems($quantities, $model);
                });
            } catch (\Throwable $e) {
                foreach ($storedFiles as $relative) {
                    $this->deleteStoredUpload($relative);
                }

                if ($e instanceof \RuntimeException && $e->getMessage() === 'missing-product') {
                    return redirect()->route('products.edit')->with('error', __('products.save_failed'));
                }

                report($e);

                return redirect()->back()->withInput()->withErrors([
                    'images' => $e instanceof \RuntimeException
                        ? $e->getMessage()
                        : __('products.images_save_failed'),
                ]);
            }

            return $this->redirectToAdminList($request, 'products.list', 'products', $savedId, __('products.saved'));
        }
        $categories = Category::all();
        $types = Type::all();
        $seasons = Schema::hasTable('seasons') ? Season::query()->orderBy('name')->get() : collect();
        $guides = Schema::hasTable('guides') ? Guide::active()->orderBy('title_en')->get() : collect();
        $sizes = $this->availableProductSizes($model);

        return view('admin.product.edit', compact('model', 'categories', 'types', 'seasons', 'guides', 'sizes'));
    }

    /**
     * Defaults + every size already used in the catalog (and this product).
     */
    protected function availableProductSizes(?products $model = null): array
    {
        $defaults = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL', '4XL', '5XL', '6XL', 'S/M', 'L/XL', '10', '12', '14'];

        $fromCatalog = productItems::query()
            ->whereNotNull('size')
            ->where('size', '!=', '')
            ->distinct()
            ->orderBy('size')
            ->pluck('size')
            ->all();

        $fromProduct = $model?->productItems?->pluck('size')->filter()->all() ?? [];

        return collect($defaults)
            ->merge($fromCatalog)
            ->merge($fromProduct)
            ->map(fn ($size) => trim((string) $size))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function uniqueProductSlug(string $slug, mixed $ignoreId = null): string
    {
        $slug = trim($slug);
        if ($slug === '') {
            $slug = 'product-'.uniqid();
        }

        $base = $slug;
        $i = 1;
        while (
            $this->model::query()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    public function saveProductItems($quantities, $model)
    {
        $submitted = [];

        foreach ($quantities as $size => $quantity) {
            $size = trim((string) $size);
            if ($size === '') {
                continue;
            }

            if ($quantity === null || $quantity === '') {
                $quantity = 0;
            }

            $submitted[] = $size;

            self::updateOrCreate($this->association, [
                'products_id' => $model->id,
                'size' => $size,
            ], [
                'quantity' => (int) $quantity,
                'size' => $size,
                'products_id' => $model->id,
            ]);
        }

        $existing = $model->productItems();
        if ($submitted !== []) {
            $existing->whereNotIn('size', $submitted);
        }
        $existing->update(['quantity' => 0]);
    }

    protected function storeUploadedImages(Request $request, int $productId, array &$stored): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        foreach ($request->file('images') as $file) {
            if (! $file) {
                continue;
            }

            $relative = $this->storePublicUpload($file, 'uploads');
            $stored[] = $relative;

            $imageModel = new productImages();
            $imageModel->products_id = $productId;
            $imageModel->images = $relative;
            $imageModel->save();
        }
    }

    protected function deleteStoredUpload(string $relative): void
    {
        $this->deletePublicUpload($relative);
    }

    protected function requestExceededPostMax(Request $request): bool
    {
        $length = (int) $request->server('CONTENT_LENGTH', 0);
        $postMax = $this->phpIniBytes((string) ini_get('post_max_size'));
        if ($length < 1 || $postMax < 1 || $length <= $postMax) {
            return false;
        }

        return ! $request->has('name') && ! $request->hasFile('images');
    }

    protected function phpIniBytes(string $value): int
    {
        $value = trim($value);
        if ($value === '') {
            return 0;
        }

        $unit = strtolower(substr($value, -1));
        $number = (int) $value;

        return match ($unit) {
            'g' => $number * 1024 * 1024 * 1024,
            'm' => $number * 1024 * 1024,
            'k' => $number * 1024,
            default => (int) $value,
        };
    }

    public function list(Request $request)
    {
        $this->rememberAdminListUrl('products');
        // Get the search and filter parameters from the request
        $search = $request->input('search');
        $category_id = $request->input('category_id');
        $type_id = $request->input('type_id');
        $season_id = $request->input('season_id');
        $color = $request->input('color');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $stock_status = $request->input('stock_status');
        $highest = $request->input('highest');

        // Define the mapping of headers to fields
        $headerMap = [
            'ID' => 'id',
            'SKU' => 'sku',
            'Name' => 'name',
            'Price' => 'price',
            'Sale' => 'sale',
            'Brand' => 'Type.name',
            'Season' => 'Season.name',
            'Description' => 'description',
            'Color' => 'color',
            'Category Name' => 'Category.name', // Assuming relation is named `category`
            'Created At' => 'created_at',

        ];

        // Build the query with filters
        $query = products::with('category', 'type', 'season', 'productItems', 'productImages');
        
        // Apply search
        if ($search) {
            $query = $query->search($search, $headerMap);
        }
        
        // Apply category filter
        if ($category_id) {
            $query = $query->where('category_id', $category_id);
        }
        
        // Apply type filter
        if ($type_id) {
            $query = $query->where('type_id', $type_id);
        }

        if ($season_id) {
            $query = $query->where('season_id', $season_id);
        }
        
        // Apply color filter
        if ($color) {
            $query = $query->where('color', $color);
        }
        
        // Apply price range filter
        if ($minPrice !== null && $minPrice !== '') {
            $query = $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice !== null && $maxPrice !== '') {
            $query = $query->where('price', '<=', $maxPrice);
        }
        
        // Apply stock status filter
        if ($stock_status) {
            if ($stock_status === 'in_stock') {
                $query = $query->whereHas('productItems', function ($q) {
                    $q->where('quantity', '>', 0);
                });
            } elseif ($stock_status === 'out_of_stock') {
                $query = $query->whereDoesntHave('productItems', function ($q) {
                    $q->where('quantity', '>', 0);
                });
            }
        }

        if ($highest === '1') {
            $query = $query->where('is_highest', 1);
        } elseif ($highest === '0') {
            $query = $query->where(function ($q) {
                $q->where('is_highest', 0)->orWhereNull('is_highest');
            });
        }

        // Paginate with appends for filter preservation
        $data = $query->paginate(10)->appends([
            'search' => $search,
            'category_id' => $category_id,
            'type_id' => $type_id,
            'season_id' => $season_id,
            'color' => $color,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'stock_status' => $stock_status,
            'highest' => $highest,
        ]);

        // Define the headers for the table
        $headers = ['ID', 'SKU', 'Name', 'Qty', 'Created At', 'Price', 'Sale', 'Brand', 'Season', 'Description', 'Color', 'Category Name', 'Action'];

        // Prepare the rows by mapping through the types collection
        $rows = $data->map(function ($data) {
            $image = $data->productImages->first();

            return [
                'ID' => $data->id,
                'SKU' => $data->sku,
                'Name' => $data->name,
                'Qty' => (int) $data->productItems->sum(fn ($item) => (int) $item->quantity),
                'preview' => $image ? asset('storage/'.$image->images) : null,
                'preview_name' => $data->name,
                'Price' => $data->price,
                'Sale' => $data->sale,
                'Brand' => $data->type->name ?? '—',
                'Season' => $data->season->name ?? '—',
                'Description' => $data->description,
                'Color' => $data->color,
                'Category Name' => $data->category->name ?? '—',
                'Created At' => $data->created_at?->format('m/d/Y') ?? '',
                'href' => route('products.edit', $data->id),
                'is_active' => $data->is_active,
                'is_highest' => $data->is_highest

            ];
        });

        $url = $this->url;

        // Get filter options for the view
        $categories = Category::all();
        $types = Type::all();
        $seasons = Schema::hasTable('seasons') ? Season::query()->orderBy('name')->get() : collect();
        $colors = products::distinct()->pluck('color')->filter()->values();
        $maxProductPrice = products::max('price');
        
        // Return the view with headers and rows data
        return view('admin.product.list', compact('headers', 'rows', 'data', 'search', 'url', 'categories', 'types', 'seasons', 'colors', 'maxProductPrice', 'category_id', 'type_id', 'season_id', 'color', 'minPrice', 'maxPrice', 'stock_status'));
    }
    public function delete($id)
    {
        // Call the deleteRecord function from the trait
        $isDeleted = self::deleteRecord($this->model, $id, true);

        // Handle the flash message based on the result
        if ($isDeleted) {
            // Success flash message
            return redirect()->back()->with('success', __('admin.record_deleted'));
        } else {
            // Failure flash message
            return redirect()->back()->with('error', __('admin.record_delete_failed'));
        }
    }
    public function productWebList(Request $request, CatalogFilterService $catalogFilters, $id = null)
    {
        $search = $request->input('search');
        $typeIds = array_slice($this->filterValues($request, 'type_id'), 0, 1);
        $seasonIds = array_slice($this->filterValues($request, 'season_id'), 0, 1);
        $colors = array_slice($this->filterValues($request, 'color'), 0, 1);
        $sizes = array_slice($this->filterValues($request, 'size'), 0, 1);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $stockStatuses = array_slice(array_values(array_intersect(
            $this->filterValues($request, 'stock_status'),
            ['in_stock', 'out_of_stock']
        )), 0, 1);
        $onSale = $request->boolean('on_sale');
        $sort = $request->input('sort', 'newest');
        $priceBands = $catalogFilters->priceBands(array_slice($this->filterValues($request, 'price_band'), 0, 1));
        $priceBand = $priceBands[0] ?? null;

        $queryCategoryIds = array_slice($this->filterValues($request, 'category_id'), 0, 1);
        $fromPath = $queryCategoryIds === [] && $id;
        $categoryIds = $fromPath ? [(string) $id] : $queryCategoryIds;

        $query = products::with([
            'category',
            'type',
            'season',
            'productItems',
            'productImages',
        ])->sellable();

        $categoryName = null;
        $categories = collect();
        if ($categoryIds !== []) {
            $categories = Category::query()
                ->whereIn('id', $categoryIds)
                ->where('is_active', 1)
                ->get();
            if ($categories->isEmpty() && $fromPath) {
                $inactive = Category::query()->whereIn('id', $categoryIds)->first();
                if ($inactive) {
                    $keeper = Category::query()
                        ->where('is_active', 1)
                        ->whereRaw('LOWER(TRIM(name)) = ?', [mb_strtolower(trim((string) $inactive->name))])
                        ->first();
                    if ($keeper) {
                        $categories = collect([$keeper]);
                    }
                }
                if ($categories->isEmpty()) {
                    abort(404);
                }
            }
            if ($categories->isNotEmpty()) {
                $query->whereIn('category_id', $categories->pluck('id'));
                $categoryName = $categories->count() === 1 ? $categories->first() : null;
                if ($fromPath) {
                    $id = $categories->first()->id;
                }
            } elseif ($fromPath) {
                $id = null;
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
            app(AnalyticsService::class)->recordSearch($request, $search);
        }

        if ($typeIds !== []) {
            $query->whereIn('type_id', $typeIds);
        }

        if ($seasonIds !== []) {
            $query->whereIn('season_id', $seasonIds);
        }

        if ($colors !== []) {
            $query->whereIn('color', $colors);
        }

        $boundedBands = array_values(array_filter($priceBands, function ($band) {
            return ($band['min'] !== null && $band['min'] !== '')
                || ($band['max'] !== null && $band['max'] !== '');
        }));
        if ($priceBands !== []) {
            $minPrice = null;
            $maxPrice = null;
            if ($boundedBands !== []) {
                $query->where(function ($q) use ($boundedBands) {
                    foreach ($boundedBands as $band) {
                        $q->orWhere(function ($inner) use ($band) {
                            if ($band['min'] !== null && $band['min'] !== '') {
                                $inner->where('price', '>=', $band['min']);
                            }
                            if ($band['max'] !== null && $band['max'] !== '') {
                                $inner->where('price', '<=', $band['max']);
                            }
                        });
                    }
                });
            }
        } else {
            if ($minPrice !== null && $minPrice !== '') {
                $query->where('price', '>=', $minPrice);
            }
            if ($maxPrice !== null && $maxPrice !== '') {
                $query->where('price', '<=', $maxPrice);
            }
        }

        if ($sizes !== []) {
            $query->whereHas('productItems', function ($q) use ($sizes) {
                $q->whereIn('size', $sizes);
            });
        }

        $stock_status = count($stockStatuses) === 1 ? $stockStatuses[0] : null;
        if ($stock_status === 'in_stock') {
            $query->whereHas('productItems', function ($q) {
                $q->where('quantity', '>', 0);
            });
        } elseif ($stock_status === 'out_of_stock') {
            $query->where(function ($q) {
                $q->whereDoesntHave('productItems')
                  ->orWhereHas('productItems', function ($q2) {
                      $q2->where('quantity', '<=', 0);
                  });
            });
        }

        if ($onSale) {
            $query->where('sale', '>', 0);
        }

        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default => $query->orderByDesc('is_highest')->orderByDesc('created_at'),
        };

        $queryParams = [
            'search' => $search,
            'category_id' => $request->has('category_id') ? $categories->pluck('id')->all() : null,
            'type_id' => $typeIds ?: null,
            'season_id' => $seasonIds ?: null,
            'color' => $colors ?: null,
            'size' => $sizes ?: null,
            'min_price' => $priceBands !== [] ? null : $minPrice,
            'max_price' => $priceBands !== [] ? null : $maxPrice,
            'price_band' => array_column($priceBands, 'id') ?: null,
            'stock_status' => $stockStatuses ?: null,
            'on_sale' => $onSale ? '1' : null,
            'sort' => $sort !== 'newest' ? $sort : null,
        ];

        $data = $query->paginate(40)->appends(array_filter($queryParams, function ($value) {
            if (is_array($value)) {
                return $value !== [];
            }

            return $value !== null && $value !== '';
        }));

        $types = Type::where('is_active', 1)->get();
        $seasons = Schema::hasTable('seasons') ? Season::where('is_active', 1)->orderBy('name')->get() : collect();
        $maxProductPrice = products::sellable()->max('price');
        $filterBar = $catalogFilters->forStorefront();
        $type_id = count($typeIds) === 1 ? $typeIds[0] : null;
        $season_id = count($seasonIds) === 1 ? $seasonIds[0] : null;
        $color = count($colors) === 1 ? $colors[0] : null;
        $size = count($sizes) === 1 ? $sizes[0] : null;
        $selectedCategories = $categories;

        $departmentTitle = count($typeIds) === 1
            ? optional($types->firstWhere('id', (int) $typeIds[0]))->name
            : null;
        $listTitle = ($categoryName?->name) ?: ($departmentTitle ?: __('web.all_products'));
        $categoryInBar = collect($filterBar['groups'] ?? [])->contains(fn ($group) => $group['key'] === 'category');
        $listRouteParams = (! $categoryInBar && $id) ? ['id' => $id] : [];
        $filterAction = route('product.List', $listRouteParams);
        $clearUrl = route('product.List', $listRouteParams);

        $viewData = compact(
            'data', 'types', 'seasons', 'maxProductPrice', 'filterBar', 'priceBand', 'priceBands',
            'id', 'categoryName', 'selectedCategories', 'search', 'type_id', 'typeIds', 'season_id', 'seasonIds', 'color', 'colors',
            'size', 'sizes', 'minPrice', 'maxPrice', 'stock_status', 'stockStatuses', 'onSale', 'sort',
            'listTitle', 'filterAction', 'clearUrl'
        );

        if ($request->headers->get('X-PLP-Ajax') === '1') {
            return response()
                ->view('product.partials.results', $viewData)
                ->header('Cache-Control', 'no-store');
        }

        return view('product.list', $viewData);
    }

    /**
     * @return array<int, string>
     */
    protected function filterValues(Request $request, string $key): array
    {
        $value = $request->input($key);
        if ($value === null || $value === '') {
            return [];
        }

        return collect(is_array($value) ? $value : [$value])
            ->map(fn ($item) => is_scalar($item) ? trim((string) $item) : '')
            ->filter(fn ($item) => $item !== '')
            ->unique()
            ->values()
            ->all();
    }

    protected function relatedProducts(products $product, int $limit = 4)
    {
        return $this->findActiveProductQuery()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->orderByDesc('is_highest')
            ->limit($limit)
            ->get();
    }

    protected function featuredProducts(int $limit = 4)
    {
        return $this->findActiveProductQuery()
            ->orderByDesc('is_highest')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function productWebShow($id, Request $request, AnalyticsService $analytics)
    {
        $product = $this->findActiveProductQuery()->with(['guide', 'reviews.user'])->find($id);

        if (! $product) {
            abort(404);
        }

        $metaViewContent = $this->trackStorefrontProductView($product, $request, $analytics);

        $relatedProducts = $this->relatedProducts($product);
        $avgRating = round($product->reviews->avg('rating') ?? 0, 1);
        $reviewCount = $product->reviews->count();
        $canReview = $this->userCanReviewProduct($product);

        return view('product.show', compact('product', 'relatedProducts', 'avgRating', 'reviewCount', 'metaViewContent', 'canReview'));
    }

    public function productBySlug(string $slug, Request $request, AnalyticsService $analytics)
    {
        if (! Schema::hasColumn('products', 'slug')) {
            abort(404);
        }

        $product = $this->findActiveProductQuery()->with(['guide', 'reviews.user'])->where('slug', $slug)->first();

        if (! $product) {
            abort(404);
        }

        $metaViewContent = $this->trackStorefrontProductView($product, $request, $analytics);

        $relatedProducts = $this->relatedProducts($product);
        $avgRating = round($product->reviews->avg('rating') ?? 0, 1);
        $reviewCount = $product->reviews->count();
        $canReview = $this->userCanReviewProduct($product);

        return view('product.show', compact('product', 'relatedProducts', 'avgRating', 'reviewCount', 'metaViewContent', 'canReview'));
    }

    protected function userCanReviewProduct(products $product): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return orders::query()
            ->where('user_id', auth()->id())
            ->whereIn('status', OrderStatus::completedValues())
            ->whereHas('orderItems', fn ($query) => $query->where('products_id', $product->id))
            ->exists();
    }

    protected function trackStorefrontProductView(products $product, Request $request, AnalyticsService $analytics): array
    {
        try {
            $meta = app(MetaPixelService::class);
            $sendCapi = $meta->shouldSendViewContentCapi($product, $request, $analytics);
            $analytics->recordProductView($product, $request);
            $payload = $meta->productCustomData($product, 1);
            if ($sendCapi) {
                $meta->sendCapi('ViewContent', $payload, $request);

                return $payload;
            }

            return [];
        } catch (\Throwable $e) {
            report($e);

            return [];
        }
    }

    protected function findActiveProductQuery()
    {
        return products::with(['category', 'type', 'season', 'productItems', 'productImages'])->sellable();
    }
    public function deleteImage($id)
    {
        // Call the deleteRecord function from the trait
        $isDeleted = self::deletePerImage($this->associationImage, $id);

        // Handle the flash message based on the result
        if ($isDeleted) {
            // Success flash message
            return redirect()->back()->with('success', __('products.image_deleted'));
        } else {
            // Failure flash message
            return redirect()->back()->with('error', __('products.image_delete_failed'));
        }

    }
    public function toggleUserStatus($id)
    {
        $product = products::findOrFail($id);

        self::toggleStatus($product);
        return back()->with('success', __('admin.status_toggled'));
    }

    public function toggleHighestStatus($id)
    {
        $product = products::findOrFail($id);

        self::toggleStatus($product, "is_highest");
        return back()->with('success', __('admin.status_toggled'));
    }

    public function clearHighest()
    {
        products::query()->where('is_highest', 1)->update(['is_highest' => 0]);

        return back()->with('success', __('products.cleared_highest'));
    }



}
