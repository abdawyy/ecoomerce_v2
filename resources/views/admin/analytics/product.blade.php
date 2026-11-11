<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1 class="mb-1">{{ $product->name }}</h1>
                <x-admin.return-button :href="\App\Support\AdminListReturn::href('analytics', 'admin.analytics')" />
                <p class="text-muted mb-0 mt-1">{{ $start->format('Y-m-d') }} — {{ $end->format('Y-m-d') }}</p>
            </div>
            <div class="card">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>{{ __('analytics.date') }}</th><th>{{ __('analytics.views') }}</th><th>{{ __('analytics.uniques') }}</th></tr></thead>
                        <tbody>
                            @forelse ($daily as $row)
                                <tr>
                                    <td>{{ $row->date->format('Y-m-d') }}</td>
                                    <td>{{ $row->views }}</td>
                                    <td>{{ $row->unique_visitors }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted">{{ __('analytics.no_data') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<x-admin.footer />
