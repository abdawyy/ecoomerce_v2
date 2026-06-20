<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\Apptraits;
use App\Models\Cities;

class CitiesController extends Controller
{
    use Apptraits;
    public $model = 'App\Models\Cities';
    public $url = '/admin/cities';
    public function edit(Request $request, $id = null)
    {
        // Retrieve the model instance to update
        $model = $this->model::find($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:255', // Validates that code is required and a string with max 255 characters
                'price' => 'required|numeric', // Validates that the discount_percentage is a number between 0 and 100
            ]);

            $attributes = [
                'id' => $id
            ];
            $values = [
                'name' => $request->name,
                'price' => $request->price,

            ];
            $model = self::updateOrCreate($this->model, $attributes, $values);

            if ($model) {
                return redirect()->route('cities.list')->with('success', 'your cities price has been successful saved.');
            } else {
                return redirect()->route('cities.list')->with('error', 'cannot be saved saved.');
            }
            // Optionally return a view with the model data for editing
        }

        return view('admin.cities.edit', compact('model'));
    }
    // Method to list types with search functionality
    public function list(Request $request)
    {
        // Get the filter parameters from the request
        $search = $request->input('search');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');

        // Define the mapping of headers to fields
        $headerMap = [
            'ID' => 'id',
            'Name' => 'name',
            'Price' => 'price',
            'Created At' => 'created_at',
        ];

        // Build query with filters
        $query = Cities::query();
        
        // Apply search filter
        if ($search) {
            $query = $query->search($search, $headerMap);
        }
        
        // Apply min price filter
        if ($minPrice !== null && $minPrice !== '') {
            $query = $query->where('price', '>=', $minPrice);
        }
        
        // Apply max price filter
        if ($maxPrice !== null && $maxPrice !== '') {
            $query = $query->where('price', '<=', $maxPrice);
        }
        
        // Paginate and preserve query parameters
        $data = $query->paginate(10)->appends(['search' => $search, 'min_price' => $minPrice, 'max_price' => $maxPrice]); // 👈 This preserves filter parameters

        // Define the headers for the table
        $headers = ['ID', 'Name', 'Price', 'Created At', 'Action'];

        // Prepare the rows by mapping through the types collection
        $rows =  $data->map(function ($data) {
            return [
                'ID' => $data->id,
                'Name' => $data->name,
                'Price' => $data->price,
                'Created At' => $data->created_at->format('m/d/Y'),
                'is_active'=>$data->is_active
            ];
        });

        $url = $this->url;

        // Return the view with headers and rows data
        return view('admin.cities.list', compact('headers', 'rows', 'data', 'search', 'url'));
    }
    public function delete($id)
    {
        // Call the deleteRecord function from the trait
        $isDeleted = self::deleteRecord($this->model, $id);

        // Handle the flash message based on the result
        if ($isDeleted) {
            // Success flash message
            return redirect()->back()->with('success', 'Record deleted successfully.');
        } else {
            // Failure flash message
            return redirect()->back()->with('error', 'Failed to delete the record. Record may not exist.');
        }
    }
       public function toggleUserStatus($id)
    {
        $city = Cities::findOrFail($id);

        self::toggleStatus($city); // now using self
        return back()->with('success', 'Status toggled');
    }
}
