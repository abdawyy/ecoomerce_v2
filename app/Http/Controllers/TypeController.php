<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\types;
use App\Traits\Apptraits;


class TypeController extends Controller
{
    use Apptraits;
    public $model = 'App\Models\Type';
    public $url = '/admin/type';
    public function edit(Request $request, $id = null)
    {
        // Retrieve the model instance to update
        $model = $this->model::find($id);

        if ($request->isMethod('post')) {
            $request->validate([

                'name' => 'required|string|max:255', // Adjust validation as needed
            ]);

            $attributes = [
                'id' => $id
            ];
            $values = [
                'name' => $request->name
            ];
            $model = self::updateOrCreate($this->model, $attributes, $values);

            if ($model) {
                return redirect()->route('type.list')->with('success', 'your type has been successful saved.');

            } else {
                return redirect()->route('type.list')->with('error', 'cannot be saved saved.');

            }
            // Optionally return a view with the model data for editing
        }
        return view('admin.type.edit', compact('model'));


    }
    // Method to list types with search functionality
    public function list(Request $request)
    {
        // Get the search parameter from the request
        $search = $request->input('search');

        // Define the mapping of headers to fields
        $headerMap = [
            'ID' => 'id',
            'Name' => 'name',
            'Created At' => 'created_at',

        ];

        // Use the search scope defined in the Type model (assuming it's implemented)
        $data = Type::search($search, $headerMap)->paginate(10)->appends(['search' => $search]); // 👈 This preserves the search query;

        // Define the headers for the table
        $headers = ['ID', 'Name', 'Created At', 'Action'];

        // Prepare the rows by mapping through the data collection
        $rows = $data->map(function ($type) {
            return [
                'ID' => $type->id,
                'Name' => $type->name,
                'Created At' => $type->created_at->format('m/d/Y'),
                'is_active' => $type->is_active

            ];
        });

        $url = $this->url;

        // Return the view with headers and rows data
        return view('admin.type.list', compact('headers', 'rows', 'data', 'search', 'url'));
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
        $type = Type::findOrFail($id);

        self::toggleStatus($type);
        return back()->with('success', 'Status toggled');
    }
}
