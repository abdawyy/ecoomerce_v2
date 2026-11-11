<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\Apptraits;
use App\Models\discountCodes;
use App\Models\SiteSetting;
use App\Services\BrandingService;
use Illuminate\Support\Facades\Schema;

class discountCodesController extends Controller
{
    use Apptraits;
    public $model = 'App\Models\discountCodes';
    public $url = '/admin/discountCodes';
    public function edit(Request $request, $id = null)
    {
        // Retrieve the model instance to update
        $model = $this->model::find($id);

        if ($request->isMethod('post')) {
            $expiryRule = 'required|date|after:today';
            if ($model && $model->expiry_date) {
                try {
                    $stored = \Illuminate\Support\Carbon::parse($model->expiry_date)->toDateString();
                    $submitted = \Illuminate\Support\Carbon::parse((string) $request->input('expiry_date'))->toDateString();
                    if ($submitted === $stored) {
                        $expiryRule = 'required|date';
                    }
                } catch (\Throwable $e) {
                    $expiryRule = 'required|date|after:today';
                }
            }

            $request->validate([
                'code' => 'required|string|max:255|unique:discount_codes,code,'.($id ?: 'NULL').',id',
                'discount_percentage' => 'required|numeric|min:0|max:100',
                'expiry_date' => $expiryRule,
            ]);

            $attributes = [
                'id' => $id
            ];
            $values = [
                'code' => $request->code,
                'discount_percentage' => $request->discount_percentage,
                'expiry_date' => $request->expiry_date,

            ];
            $model = self::updateOrCreate($this->model, $attributes, $values);

            if ($model) {
                return $this->redirectToAdminList($request, 'discountCodes.list', 'discountCodes', $model->id, 'your Discount Code has been successful saved.');

            } else {
                return $this->redirectToAdminList($request, 'discountCodes.list', 'discountCodes', $id, null, 'cannot be saved saved.');

            }
            // Optionally return a view with the model data for editing
        }

        return view('admin.discountCodes.edit', compact('model'));


    }
    // Method to list types with search functionality
    public function list(Request $request)
    {
        $this->rememberAdminListUrl('discountCodes');
        // Get the filter parameters from the request
        $search = $request->input('search');
        $minDiscount = $request->input('min_discount');
        $maxDiscount = $request->input('max_discount');

        // Define the mapping of headers to fields
        $headerMap = [
            'ID' => 'id',
            'Code' => 'code',
            'Discount Percentage' => 'discount_percentage',
            'Expiry Date' => 'expiry_date',
        ];

        // Build query with filters
        $query = discountCodes::query();
        
        // Apply search filter
        if ($search) {
            $query = $query->search($search, $headerMap);
        }
        
        // Apply min discount filter
        if ($minDiscount !== null && $minDiscount !== '') {
            $query = $query->where('discount_percentage', '>=', $minDiscount);
        }
        
        // Apply max discount filter
        if ($maxDiscount !== null && $maxDiscount !== '') {
            $query = $query->where('discount_percentage', '<=', $maxDiscount);
        }
        
        // Paginate and preserve query parameters
        $data = $query->paginate(10)->appends(['search' => $search, 'min_discount' => $minDiscount, 'max_discount' => $maxDiscount]); // 👈 This preserves filter parameters

        // Define the headers for the table
        $headers = ['ID', 'Code', 'Discount Percentage', 'Expiry Date', 'Action'];

        // Prepare the rows by mapping through the types collection
        $rows = $data->map(function ($data) {
            return [
                'ID' => $data->id,
                'Code' => $data->code,
                'Discount Percentage' => $data->discount_percentage . ' %',
                'Expiry Date' => $data->expiry_date,
                'Created At' => $data->created_at->format('m/d/Y'),
                'href' => route('discountCodes.edit', $data->id),
                'is_active' => $data->is_active

            ];
        });

        $url = $this->url;

        // Return the view with headers and rows data
        return view('admin.discountCodes.list', compact('headers', 'rows', 'data', 'search', 'url'));
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
        $discount_codes = discountCodes::findOrFail($id);

        self::toggleStatus($discount_codes); // now using self
        return back()->with('success', 'Status toggled');
    }

    public function freeShipping(BrandingService $branding)
    {
        return view('admin.discountCodes.free-shipping', [
            'settings' => $branding->settings(),
        ]);
    }

    public function updateFreeShipping(Request $request, BrandingService $branding)
    {
        $validated = $request->validate([
            'free_shipping_min_amount' => 'nullable|numeric|min:0',
        ]);

        $raw = $validated['free_shipping_min_amount'] ?? null;
        $amount = ($raw === null || $raw === '' || (float) $raw <= 0) ? null : round((float) $raw, 2);

        $settings = SiteSetting::current();
        if (Schema::hasColumn('site_settings', 'free_shipping_min_amount')) {
            $settings->free_shipping_min_amount = $amount;
            $settings->save();
        }
        $branding->clearCache();

        return redirect()->route('discountCodes.freeShipping')->with('success', __('discount_codes.free_shipping_saved'));
    }
}

