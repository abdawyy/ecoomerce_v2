<?php

namespace App\Http\Controllers;

use App\Models\orders;
use App\Models\products;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Http\Requests\ReviewRequest;
use App\Support\OrderStatus;
use App\Traits\Apptraits;
use Illuminate\Support\Facades\Mail;



class ReviewController extends Controller
{
    use Apptraits;
    public $url = '/admin/reviews';




public function store(ReviewRequest $request)
{
    $data = $request->validated();

    $purchased = orders::query()
        ->where('user_id', auth()->id())
        ->whereIn('status', OrderStatus::completedValues())
        ->whereHas('orderItems', fn ($q) => $q->where('products_id', $data['product_id']))
        ->exists();

    if (! $purchased) {
        return response()->json(['message' => __('web.review_requires_purchase')], 403);
    }

    $review = Review::create([
        'user_id' => auth()->id(),
        'product_id' => $data['product_id'],
        'rating' => $data['rating'],
        'comment' => $data['comment'],
    ]);

    // Fetch product and user info
    $product = products::find($request->product_id);
    $user = auth()->user();

    // Prepare email content
    $body = "A new review has been submitted:\n\n" .
            "Product: {$product->name}\n" .
            "User: {$user->name} ({$user->email})\n" .
            "Rating: {$request->rating}\n\n" .
            "Comment:\n{$request->comment}";

    // Send email
    Mail::raw($body, function ($message) use ($user) {
        $message->to(config('hayah.admin_email'))
                ->subject('New Product Review Submitted')
                ->replyTo($user->email);
    });

    return response()->json(['message' => 'Review submitted']);
}

    public function list(Request $request, $productId)
    {
        // Get the search parameter from the request
        $search = $request->input('search');

        // Define the mapping of headers to fields
        $headerMap = [
            'ID' => 'id',
            'User Name' => 'user.name',
            'Rating' => 'rating',
            'Message' => 'comment',
            'Created At' => 'created_at',
        ];

        // Get only reviews for the given product with eager loading for user
        $data = Review::with('user')
            ->where('product_id', $productId)
            ->search($search, $headerMap)
            ->paginate(10)->appends(['search' => $search]); // 👈 This preserves the search query;

        // Define table headers
        $headers = ['ID', 'User Name', 'Rating', 'Comment', 'Created At', 'Action'];

        // Prepare rows
        $rows = $data->map(function ($data) {
            return [
                'ID' => $data->id,
                'User Name' => optional($data->user)->name,
                'Rating' => str_repeat('⭐', $data->rating),
                'Comment' => $data->comment,
                'Created At' => $data->created_at->format('m/d/Y'),
                'is_active' => $data->is_active,
            ];
        });

        $url = $this->url;

        return view('admin.product.review', compact('headers', 'rows', 'data', 'search', 'url', 'productId'));
    }

    public function toggleUserStatus($id)
    {
        $review = Review::findOrFail($id);

        self::toggleStatus($review);
        return back()->with('success', 'Status toggled');
    }


}
