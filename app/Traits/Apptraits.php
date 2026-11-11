<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;




trait Apptraits
{
    public function updateOrCreate(string $modelClass, array $attributes, array $values = [])
    {
        // Find the model by the given attributes
        $model = $modelClass::where($attributes)->first();


        // If the model is found, update it with the provided values
        if ($model != null) {


            $model->update($values);

            return $model;
        }

        // If not found, create a new model instance with the attributes and values
        return $modelClass::create(array_merge($attributes, $values));
    }
    public function scopeSearch(Builder $query, ?string $search, array $headerMap): Builder
    {
        if ($search) {
            // Start a new where clause
            $query->where(function ($q) use ($search, $headerMap) {
                foreach ($headerMap as $header => $field) {
                    // Check if the field refers to an association (i.e., contains a dot for relations)
                    if (strpos($field, '.') !== false) {
                        // Handle associated entities using whereHas
                        [$relation, $relatedField] = explode('.', $field);
                        $q->orWhereHas($relation, function ($relatedQuery) use ($relatedField, $search) {
                            $relatedQuery->where($relatedField, 'like', "%{$search}%");
                        });
                    } else {
                        // Apply search conditions based on the direct field
                        $q->orWhere($field, 'like', "%{$search}%");
                    }
                }
            });
        }

        return $query;
    }

    public function deleteRecord($model, $id, $haveImage = false)
    {

        if ($haveImage) {
            try {
                // Find the record by ID with eager loading of associations
                $record = $model::with(['category', 'productImages', 'productItems'])->find($id);

                // Check if the record exists
                if (!$record) {
                    return false;  // Record not found
                }

                if (method_exists($record, 'reviews')) {
                    \App\Models\Review::where('product_id', $record->id)->delete();
                }

                // Delete associated product images
                if ($record->productImages && $record->productImages->isNotEmpty()) {
                    foreach ($record->productImages as $albumImage) {
                        $this->deleteImageFromStorage($albumImage->images);
                        $albumImage->delete(); // Delete the image record
                    }
                }

                // Delete associated product items
                if ($record->productItems && $record->productItems->isNotEmpty()) {
                    foreach ($record->productItems as $productItem) {
                        $productItem->delete(); // Delete the product item record
                    }
                }

                // Delete the main record
                $record->delete();

                return true;  // All records deleted successfully
            } catch (\Exception $e) {
                return false;  // Failed to delete record
            }
        }else{
            $record = $model::find($id);

            // Check if the record exists
            if (!$record) {
                return false;  // Record not found
            }
            $record->delete();

            return true;
        }
    }
    public function deletePerImage($model, $id)
    {
        try {
            $record = $model::find($id);

            if ($record) {
                $this->deleteImageFromStorage($record->images); // Assumes this method handles storage deletion gracefully
                $record->delete();
                return true; // Return true if deletion succeeds
            }

            return false; // Return false if the record is not found
        } catch (\Exception $e) {
            // Log the exception if needed
            return false; // Return false on error
        }
    }


    /**
     * Delete image file from storage.
     *
     * @param string $imagePath
     * @return bool
     */
protected function deleteImageFromStorage($imagePath)
{
    return $this->deletePublicUpload($imagePath);
}

    protected function ensurePublicStorageLink(): void
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        if (is_link($link)) {
            return;
        }

        $linkReal = realpath($link);
        $targetReal = realpath($target);
        if ($linkReal && $targetReal && $linkReal === $targetReal) {
            return;
        }

        if (! file_exists($link)) {
            @symlink($target, $link);
        }
    }

    protected function storePublicUpload(UploadedFile $file, string $directory = 'uploads'): string
    {
        if (! $file->isValid()) {
            throw new \RuntimeException(__('products.images_too_large'));
        }

        $directory = trim($directory, '/');
        $diskDir = storage_path('app/public/'.$directory);
        if (! is_dir($diskDir) && ! @mkdir($diskDir, 0775, true) && ! is_dir($diskDir)) {
            throw new \RuntimeException(__('products.images_save_failed'));
        }

        $this->ensurePublicStorageLink();

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $fileName = time().'-'.uniqid().'.'.$extension;
        $relative = $directory.'/'.$fileName;

        $stored = Storage::disk('public')->putFileAs($directory, $file, $fileName);
        if (! $stored) {
            throw new \RuntimeException(__('products.images_save_failed'));
        }

        $diskPath = storage_path('app/public/'.$relative);
        $publicPath = public_path('storage/'.$relative);
        if (is_file($diskPath) && realpath($diskPath) !== realpath($publicPath)) {
            $publicDir = dirname($publicPath);
            if ((is_dir($publicDir) || @mkdir($publicDir, 0775, true)) && is_writable($publicDir) && ! is_file($publicPath)) {
                @copy($diskPath, $publicPath);
            }
        }

        return $relative;
    }

    protected function deletePublicUpload($imagePath): bool
    {
        if (! is_string($imagePath) || $imagePath === '') {
            return false;
        }

        $relative = ltrim(str_replace(['../', '..\\'], '', $imagePath), '/\\');
        if ($relative === '') {
            return false;
        }

        $deleted = false;
        if (Storage::disk('public')->exists($relative)) {
            $deleted = Storage::disk('public')->delete($relative) || $deleted;
        }

        $publicPath = public_path('storage/'.$relative);
        if (is_file($publicPath)) {
            $deleted = @unlink($publicPath) || $deleted;
        }

        return $deleted;
    }

    public function uploadImages(Request $request, $model, int $product_id, string $fieldName = 'images', string $storagePath = 'uploads')
    {
        if ($request->hasFile($fieldName)) {
            foreach ($request->file($fieldName) as $file) {
                if (! $file) {
                    continue;
                }

                $relative = $this->storePublicUpload($file, $storagePath);

                $imageModel = new $model;
                $imageModel->products_id = $product_id;
                $imageModel->images = $relative;
                $imageModel->save();
            }

            return true;
        }

        return false;
    }

    // Step 1: Function to filter out null values from the quantities array
    private function filterQuantities(Request $request)
    {
        // Get the quantities from the request
        $quantities = $request->input('quantities', []);

        // Remove any entries with null values
        $quantities = array_filter($quantities, function ($quantity) {
            return $quantity !== null; // Keep only non-null quantities
        });

        // Replace the original quantities in the request
        $request->merge(['quantities' => $quantities]);
    }
      public function toggleStatus($model, string $booleanField = 'is_active'): bool
    {
        if (!isset($model->{$booleanField})) {
            return false; // or throw exception if preferred
        }

        $model->{$booleanField} = !$model->{$booleanField};
        return $model->save();
    }
    protected function getGuestCartItems()
{
    return session('cart', []);
}
protected function generatePdfInvoice($order)
{
    return app(\App\Services\PdfService::class)->saveInvoices($order);
}

    protected function rememberAdminListUrl(string $key): void
    {
        session(['admin.list_url.'.$key => url()->full()]);
    }

    protected function isSafeAdminUrl(?string $url): bool
    {
        if (! is_string($url) || $url === '') {
            return false;
        }

        $path = parse_url($url, PHP_URL_PATH) ?: '';
        if (! str_starts_with($path, '/admin')) {
            return false;
        }

        $host = parse_url($url, PHP_URL_HOST);
        if ($host && ! in_array($host, [parse_url((string) config('app.url'), PHP_URL_HOST), request()->getHost()], true)) {
            return false;
        }

        return true;
    }

    protected function resolveAdminListUrl(\Illuminate\Http\Request $request, string $defaultRoute, string $listKey): string
    {
        foreach ([$request->input('return'), session('admin.list_url.'.$listKey)] as $candidate) {
            if ($this->isSafeAdminUrl(is_string($candidate) ? $candidate : null)) {
                return $candidate;
            }
        }

        return route($defaultRoute);
    }

    protected function redirectToAdminList(\Illuminate\Http\Request $request, string $defaultRoute, string $listKey, mixed $id = null, ?string $success = null, ?string $error = null)
    {
        $url = $this->resolveAdminListUrl($request, $defaultRoute, $listKey);
        if ($id) {
            $url = preg_replace('/#.*$/', '', $url) ?: $url;
            $url .= '#admin-row-'.$id;
        }

        $redirect = redirect()->to($url);
        if ($success) {
            $redirect->with('success', $success);
        }
        if ($error) {
            $redirect->with('error', $error);
        }
        if ($id) {
            $redirect->with('highlight_id', $id);
        }

        return $redirect;
    }

}
