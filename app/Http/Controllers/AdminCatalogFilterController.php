<?php

namespace App\Http\Controllers;

use App\Services\CatalogFilterService;
use Illuminate\Http\Request;

class AdminCatalogFilterController extends Controller
{
    public function edit(CatalogFilterService $filters)
    {
        return view('admin.catalog-filters.edit', [
            'editor' => $filters->forAdmin(),
        ]);
    }

    public function update(Request $request, CatalogFilterService $filters)
    {
        if (! $filters->ready()) {
            return redirect()->route('admin.catalogFilters.edit')->with('error', __('catalog_filters.missing_tables'));
        }

        $request->validate([
            'groups' => 'array',
            'groups.*.sort' => 'nullable|integer|min:0|max:99',
            'options' => 'array',
            'bands' => 'array',
            'bands.*.label_en' => 'nullable|string|max:80',
            'bands.*.label_ar' => 'nullable|string|max:80',
            'bands.*.min_price' => 'nullable|numeric|min:0',
            'bands.*.max_price' => 'nullable|numeric|min:0',
            'bands_new' => 'array',
            'bands_new.*.label_en' => 'nullable|string|max:80',
            'bands_new.*.label_ar' => 'nullable|string|max:80',
            'bands_new.*.min_price' => 'nullable|numeric|min:0',
            'bands_new.*.max_price' => 'nullable|numeric|min:0',
            'bands_delete' => 'array',
        ]);

        $filters->save($request);

        return redirect()->route('admin.catalogFilters.edit')->with('success', __('catalog_filters.saved'));
    }
}
