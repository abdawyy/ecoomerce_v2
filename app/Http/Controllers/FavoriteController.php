<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\products;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class FavoriteController extends Controller
{
    public function toggle(Request $request, products $product): JsonResponse|RedirectResponse
    {
        if (! Schema::hasTable('favorites') || ! $product->isSellable()) {
            abort(404);
        }

        $existing = Favorite::query()
            ->where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $favorited = false;
            $message = __('account.favorite_removed');
        } else {
            Favorite::query()->create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
            ]);
            $favorited = true;
            $message = __('account.favorite_added');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'favorited' => $favorited,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
