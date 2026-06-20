<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
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
    // Define the full path to the image in the 'public/storage/uploads' directory
    $fullPath = public_path('storage/' . $imagePath); // Adjust if the path is relative

    // Check if the image exists, then delete it
    if (file_exists($fullPath)) {
        return unlink($fullPath); // Delete the file using unlink
    }

    return false;
}

    public function uploadImages(Request $request, $model, int $product_id, string $fieldName = 'images', string $storagePath = 'uploads')
    {
        if ($request->hasFile($fieldName)) {
            // Iterate through each uploaded file
            foreach ($request->file($fieldName) as $file) {
                // Generate a unique filename
                $fileName = time() . '-' . uniqid() . '.' . $file->getClientOriginalExtension(); // Ensure unique filenames

                // Define the target path within public/storage/uploads
                $targetPath = public_path('storage/' . $storagePath . '/' . $fileName); // public/storage/uploads/filename.jpg

                // Move the file to the specified path
                $file->move(public_path('storage/' . $storagePath), $fileName); // Move file to public/storage/uploads

                // Create a new instance of the model for each image
                $imageModel = new $model;

                // Set the properties
                $imageModel->products_id = $product_id; // Set the product ID
                $imageModel->images = $storagePath . '/' . $fileName; // Store the relative file path

                // Save the model instance to the database
                $imageModel->save();
            }

            return true; // Indicate that images were uploaded successfully
        }

        return false; // No images uploaded
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

}
