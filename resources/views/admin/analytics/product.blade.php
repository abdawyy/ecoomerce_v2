<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle">
                <h1>{{ $product->name }}</h1>
                <p class="text-muted">{{ $start->format('Y-m-d') }} — {{ $end->format('Y-m-d') }}</p>
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
            <a href="{{ route('admin.analytics') }}" class="btn btn-secondary mt-3">{{ __('analytics.back') }}</a>
        </div>
    </div>
</main>

<x-admin.footer />
