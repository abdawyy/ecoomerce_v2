@php
    $isArabic = app()->getLocale() === 'ar';
    $languageLabel = __('table.language');
    if ($languageLabel === 'table.language') {
        $languageLabel = 'Language';
    }
@endphp

<div class="container-fluid px-0 {{ $isArabic ? 'text-end' : '' }}" dir="{{ $isArabic ? 'rtl' : 'ltr' }}">
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-3">
        {{-- Language Switch --}}
        {{-- <div class="d-flex align-items-center gap-2 small text-muted">
            <span class="text-uppercase">{{ $languageLabel }}:</span>
            <a href="{{ url('/lang/en') }}" class="{{ !$isArabic ? 'fw-bold text-primary' : 'text-dark' }}">EN</a>
            <span class="text-muted">|</span>
            <a href="{{ url('/lang/ar') }}" class="{{ $isArabic ? 'fw-bold text-primary' : 'text-dark' }}">العربية</a>
        </div> --}}

        {{-- Search Form --}}
        {{-- <form action="{{ url()->current() }}" method="GET" class="w-100 w-md-auto">
            <div class="input-group search-group">
                <input type="text" name="search" class="form-control"
                       placeholder="{{ __('table.search_placeholder') }}" value="{{ request('search') }}">
                <button class="btn btn-dark" type="submit">{{ __('table.search_button') }}</button>
            </div>
        </form> --}}
    </div>

    {{-- Table --}}
    <div class="table-responsive shadow-sm rounded-4 admin-table-wrap">
        <table class="table table-modern align-middle mb-0">
            <thead>
                <tr>
                    @foreach ($headers as $header)
                        @php
                            $headerKey = 'table.headers.' . strtolower($header);
                            $headerLabel = __($headerKey);
                            $headerLabel = $headerLabel === $headerKey ? $header : $headerLabel;
                        @endphp
                        <th scope="col">{{ $headerLabel }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @if($rows->isEmpty())
                    <tr>
                        <td colspan="{{ count($headers) }}" class="text-center">
                            {{ __('table.no_results') }}
                        </td>
                    </tr>
                @else
                    @foreach ($rows as $row)
                        <tr>
                            @foreach ($headers as $header)
                                @php
                                    $headerKey = 'table.headers.' . strtolower($header);
                                    $headerLabel = __($headerKey);
                                    $headerLabel = $headerLabel === $headerKey ? $header : $headerLabel;
                                @endphp
                                <td data-label="{{ $headerLabel }}">
                                    @if ($header === 'Action')
                                        @php
                                            $actionsAlign = $isArabic ? 'justify-content-start' : 'justify-content-end';
                                            $actionCount = 1
                                                + (isset($row['is_active']) ? 1 : 0)
                                                + (isset($row['is_highest']) ? 1 : 0);
                                        @endphp
                                        <div class="table-actions {{ $actionsAlign }} {{ $actionCount >= 3 ? 'table-actions--multi' : '' }}">
                                            <x-admin.view-link :href="$url . '/edit/' . $row['ID']" />

                                            @if (isset($row['is_active']))
                                                <form method="POST" action="{{ $url }}/status/{{ $row['ID'] }}" class="table-actions-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $row['is_active'] == 1 ? 'btn-success' : 'btn-warning' }}">
                                                        {{ $row['is_active'] == 1 ? __('table.status_active') : __('table.status_inactive') }}
                                                    </button>
                                                </form>
                                            @endif

                                            @if (isset($row['is_highest']))
                                                <form method="POST" action="{{ route('admin.products.toggleHighestStatus', $row['ID']) }}" class="table-actions-form">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $row['is_highest'] == 1 ? 'btn-primary' : 'btn-secondary' }}">
                                                        {{ $row['is_highest'] == 1 ? __('table.status_highest') : __('table.status_normal') }}
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    @elseif ($header === 'ID')
                                        <a href="{{ $url }}/edit/{{ $row['ID'] }}" class="text-decoration-none">#{{ $row['ID'] }}</a>
                                    @elseif ($header === 'Status')
                                        @php
                                            $status = $row['Status'] ?? '';
                                            $statusClass = 'bg-secondary text-white';
                                            if (strtolower($status) === 'completed') $statusClass = 'bg-success text-white';
                                            if (strtolower($status) === 'pending') $statusClass = 'bg-warning text-dark';
                                            if (strtolower($status) === 'processing') $statusClass = 'bg-info text-white';
                                            if (strtolower($status) === 'cancelled') $statusClass = 'bg-danger text-white';
                                        @endphp
                                        <span class="status-badge {{ $statusClass }}">{{ $row[$header] ?? $status }}</span>
                                    @else
                                        {{ $row[$header] ?? '' }}
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
