<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\Apptraits;
use App\Models\Category;
use App\Models\Type;
use App\Models\types;



class categoriesController extends Controller
{
    use Apptraits;
    public $model='App\Models\Category';
    public $url='/admin/categories';
    public function edit( Request $request,$id = null){
  // Retrieve the model instance to update
  $model = $this->model::find($id);

        if ($request->isMethod('post')) {
        $request->validate([

            'name' => 'required|string|max:255', // Adjust validation as needed
            'type_id' => 'required|exists:type,id', // Validates that the product_id exists in the products table

        ]);

        $attributes=[
            'id'=>$id
        ];
        $values=[
            'name'=>$request->name,
            'type_id' =>$request->type_id
        ];
        $model = self::updateOrCreate($this->model,$attributes,$values);

        if($model){
            return redirect()->route('categories.list')->with('success', 'your category has been successful saved.');

        }else{
            return redirect()->route('categories.list')->with('error', 'cannot be saved saved.');

        }
         // Optionally return a view with the model data for editing
    }
    $types=Type::all();
    return view('admin.categories.edit', compact('model','types'));


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
            'Type'=>'Type.name',
            'Created At' => 'created_at',
        ];

        // Use the search scope defined in the Type model (assuming it's implemented)
        $data = Category::search($search, $headerMap)->paginate(10)->appends(['search' => $search]); // 👈 This preserves the search query;

        // Define the headers for the table
        $headers = ['ID', 'Name', 'Type','Created At','Action'];

        // Prepare the rows by mapping through the types collection
        $rows =  $data->map(function ($data) {
            return [
                'ID' => $data->id,
                'Name' => $data->name,
                'Type'=>$data->type->name,
                'Created At' => $data->created_at->format('m/d/Y'),
                'is_active'=>$data->is_active
            ];
        });

        $url=$this->url;

        // Return the view with headers and rows data
        return view('admin.categories.list', compact('headers', 'rows', 'data', 'search','url'));
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
        $category = Category::findOrFail($id);

        self::toggleStatus($category); // now using self
        return back()->with('success', 'Status toggled');
    }
}
