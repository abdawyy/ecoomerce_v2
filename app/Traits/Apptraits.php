<?php

namespace App\Traits;

use App\Services\PublicUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\UploadedFile;
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

    protected function storePublicUpload(UploadedFile $file, string $directory = 'uploads'): string
    {
        return app(PublicUpload::class)->store($file, $directory);
    }

    protected function deletePublicUpload($imagePath): bool
    {
        return app(PublicUpload::class)->delete(is_string($imagePath) ? $imagePath : null);
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
        return \App\Support\AdminListReturn::isSafe($url);
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
            $url = \App\Support\AdminListReturn::withoutPage(preg_replace('/#.*$/', '', $url) ?: $url);
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
