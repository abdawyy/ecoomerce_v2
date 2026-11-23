@php
    $type = $row['filter_type'] ?? 'all';
    $value = (string) ($row['filter_value'] ?? '');
    $imageUrl = $row['image_url'] ?? '';
@endphp
<article class="card border-0 shadow-sm home-card-editor">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-4">
                <label class="form-label fw-semibold">{{ __('home_cards.image') }}</label>
                <div class="home-card-preview rounded overflow-hidden mb-2" @if ($imageUrl === '') hidden @endif>
                    <img src="{{ $imageUrl }}" alt="">
                </div>
                <input type="file" name="cards[{{ $index }}][image]" class="form-control js-card-image" accept="image/*">
                <div class="form-text">{{ __('home_cards.image_hint') }}</div>
                @error('cards.'.$index.'.image')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-8">
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn btn-outline-danger btn-sm" data-remove-card>{{ __('home_cards.remove') }}</button>
                </div>
                @if (! empty($row['id']))
                    <input type="hidden" name="cards[{{ $index }}][id]" value="{{ $row['id'] }}">
                @endif
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('home_cards.name_en') }}</label>
                        <input type="text" name="cards[{{ $index }}][title_en]" class="form-control" maxlength="80"
                               value="{{ $row['title_en'] ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('home_cards.name_ar') }}</label>
                        <input type="text" name="cards[{{ $index }}][title_ar]" class="form-control" maxlength="80" dir="rtl"
                               value="{{ $row['title_ar'] ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('home_cards.filter') }}</label>
                        <select name="cards[{{ $index }}][filter_type]" class="form-select js-filter-type">
                            @foreach (\App\Models\HomeCard::FILTER_TYPES as $filterType)
                                <option value="{{ $filterType }}" @selected($type === $filterType)>{{ __('home_cards.filters.'.$filterType) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">{{ __('home_cards.filter_value') }}</label>
                        <select name="cards[{{ $index }}][filter_value]" class="form-select js-filter-value"
                                data-selected="{{ $value }}"
                                @if ($type === 'all') hidden disabled @endif>
                            @if ($type !== 'all')
                                <option value="">{{ __('home_cards.choose_value') }}</option>
                                @foreach ($choices[$type] ?? [] as $option)
                                    <option value="{{ $option['value'] }}" @selected($value === (string) $option['value'])>{{ $option['label'] }}</option>
                                @endforeach
                            @endif
                        </select>
                        @error('cards.'.$index.'.filter_value')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>
