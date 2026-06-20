<x-account.layout :title="__('account.reviews')">
    @if ($reviews->isEmpty())
        <div class="account-card account-empty">
            <i class="fa-solid fa-star d-block"></i>
            <p class="mb-0">{{ __('account.no_reviews') }}</p>
        </div>
    @else
        <div class="vstack gap-3">
            @foreach ($reviews as $review)
                <div class="account-card">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap mb-2">
                            <div>
                                <div class="small text-muted">{{ __('account.review_for') }}</div>
                                @if ($review->product)
                                    <a href="{{ route('product.show', $review->product_id) }}" class="fw-bold text-dark text-decoration-none">
                                        {{ $review->product->name }}
                                    </a>
                                @else
                                    <span class="fw-bold">—</span>
                                @endif
                            </div>
                            <div class="text-warning">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="mb-2">{{ $review->comment }}</p>
                        <div class="small text-muted">{{ $review->created_at?->diffForHumans() }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {{ $reviews->links() }}
        </div>
    @endif
</x-account.layout>
