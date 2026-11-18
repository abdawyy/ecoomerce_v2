<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

@php $isRtl = app()->getLocale() === 'ar'; @endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1 class="mb-1">{{ __('catalog_filters.title') }}</h1>
                <p class="text-muted mb-0">{{ __('catalog_filters.intro') }}</p>
            </div>

            @if (! $editor['ready'])
                <div class="alert alert-warning">{{ __('catalog_filters.missing_tables') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('admin.catalogFilters.update') }}" method="POST" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                @csrf
                <div class="d-flex flex-column gap-3">
                    @foreach ($editor['groups'] as $group)
                        <section class="card border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                                    <h5 class="mb-0">{{ __('catalog_filters.groups.'.$group['key']) }}</h5>
                                    <div class="d-flex flex-wrap align-items-center gap-3">
                                        <div class="form-check mb-0">
                                            <input class="form-check-input" type="checkbox"
                                                   name="groups[{{ $group['key'] }}][visible]" value="1"
                                                   id="visible-{{ $group['key'] }}"
                                                   @checked(old('groups.'.$group['key'].'.visible', $group['visible']))>
                                            <label class="form-check-label" for="visible-{{ $group['key'] }}">{{ __('catalog_filters.visible') }}</label>
                                        </div>
                                        <div style="width: 110px;">
                                            <label class="form-label small mb-1" for="sort-{{ $group['key'] }}">{{ __('catalog_filters.sort') }}</label>
                                            <input type="number" min="0" max="99" class="form-control form-control-sm"
                                                   id="sort-{{ $group['key'] }}"
                                                   name="groups[{{ $group['key'] }}][sort]"
                                                   value="{{ old('groups.'.$group['key'].'.sort', $group['sort']) }}">
                                        </div>
                                    </div>
                                </div>

                                @if ($group['choices'] !== [])
                                    <p class="text-muted small">{{ __('catalog_filters.empty_hint') }}</p>
                                    <div class="row g-2">
                                        @foreach ($group['choices'] as $choice)
                                            <div class="col-md-4 col-lg-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                           name="options[{{ $group['key'] }}][]"
                                                           value="{{ $choice['value'] }}"
                                                           id="opt-{{ $group['key'] }}-{{ md5($choice['value']) }}"
                                                           @checked($choice['checked'])>
                                                    <label class="form-check-label" for="opt-{{ $group['key'] }}-{{ md5($choice['value']) }}">{{ $choice['label'] }}</label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif ($group['key'] === 'price')
                                    <p class="text-muted small">{{ __('catalog_filters.price_intro') }}</p>
                                    <div id="price-band-deletes"></div>
                                    <div class="d-flex flex-column gap-2" id="price-bands">
                                        @foreach ($group['bands'] as $band)
                                            <div class="row g-2 align-items-end" data-price-band>
                                                <div class="col-md-3">
                                                    <label class="form-label small">{{ __('catalog_filters.price_en') }}</label>
                                                    <input type="text" class="form-control" name="bands[{{ $band->id }}][label_en]" value="{{ $band->label_en }}">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="form-label small">{{ __('catalog_filters.price_ar') }}</label>
                                                    <input type="text" class="form-control" name="bands[{{ $band->id }}][label_ar]" value="{{ $band->label_ar }}" dir="rtl">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">{{ __('catalog_filters.price_min') }}</label>
                                                    <input type="number" min="0" step="0.01" class="form-control" name="bands[{{ $band->id }}][min_price]" value="{{ $band->min_price }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label small">{{ __('catalog_filters.price_max') }}</label>
                                                    <input type="number" min="0" step="0.01" class="form-control" name="bands[{{ $band->id }}][max_price]" value="{{ $band->max_price }}">
                                                </div>
                                                <div class="col-md-2">
                                                    <button type="button" class="btn btn-outline-danger btn-sm w-100 price-band-remove" data-band-id="{{ $band->id }}">
                                                        {{ __('catalog_filters.price_delete') }}
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <template id="price-band-template">
                                        <div class="row g-2 align-items-end mt-1" data-price-band>
                                            <div class="col-md-3">
                                                <label class="form-label small">{{ __('catalog_filters.price_en') }}</label>
                                                <input type="text" class="form-control" data-name="label_en">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label small">{{ __('catalog_filters.price_ar') }}</label>
                                                <input type="text" class="form-control" data-name="label_ar" dir="rtl">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label small">{{ __('catalog_filters.price_min') }}</label>
                                                <input type="number" min="0" step="0.01" class="form-control" data-name="min_price">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="form-label small">{{ __('catalog_filters.price_max') }}</label>
                                                <input type="number" min="0" step="0.01" class="form-control" data-name="max_price">
                                            </div>
                                            <div class="col-md-2">
                                                <button type="button" class="btn btn-outline-danger btn-sm w-100 price-band-remove">
                                                    {{ __('catalog_filters.price_delete') }}
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                    <button type="button" class="btn btn-outline-dark btn-sm mt-3" id="add-price-band">{{ __('catalog_filters.price_add') }}</button>
                                @endif
                            </div>
                        </section>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-dark py-2 px-4 mt-4" @disabled(! $editor['ready'])>{{ __('catalog_filters.save') }}</button>
            </form>
        </div>
    </div>
</main>

<script>
    (function () {
        const host = document.getElementById('price-bands');
        const deletes = document.getElementById('price-band-deletes');
        let newBandIndex = 0;

        document.getElementById('add-price-band')?.addEventListener('click', function () {
            const template = document.getElementById('price-band-template');
            if (!template || !host) return;
            const index = newBandIndex++;
            const row = template.content.firstElementChild.cloneNode(true);
            row.setAttribute('data-new-band', '1');
            row.querySelectorAll('[data-name]').forEach(function (input) {
                input.name = 'bands_new[' + index + '][' + input.getAttribute('data-name') + ']';
            });
            host.appendChild(row);
        });

        host?.addEventListener('click', function (event) {
            const button = event.target.closest('.price-band-remove');
            if (!button) return;
            const id = button.getAttribute('data-band-id');
            if (id && deletes) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'bands_delete[]';
                input.value = id;
                deletes.appendChild(input);
            }
            button.closest('[data-price-band]')?.remove();
        });
    })();
</script>

<x-admin.footer />
