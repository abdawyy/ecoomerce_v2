<x-admin.header />
<x-admin.aside />
<x-admin.navbar />

@php
    $isRtl = app()->getLocale() === 'ar';
    $saved = $cards->keyBy('id');
    $rows = old('cards');
    if (! is_array($rows)) {
        $rows = $cards->map(fn ($card) => [
            'id' => $card->id,
            'title_en' => $card->title_en,
            'title_ar' => $card->title_ar,
            'filter_type' => $card->filter_type ?: 'all',
            'filter_value' => $card->filter_value,
            'image_url' => $card->image_path ? $branding->assetUrl($card->image_path) : '',
        ])->all();
    } else {
        $rows = array_values($rows);
        foreach ($rows as $index => $row) {
            $existing = isset($row['id']) ? $saved->get($row['id']) : null;
            $rows[$index]['image_url'] = $existing && $existing->image_path
                ? $branding->assetUrl($existing->image_path)
                : '';
        }
    }
@endphp

<main id="main">
    <div class="container">
        <div class="row pt-4">
            <div class="pagetitle mb-3">
                <h1 class="mb-1">{{ __('home_cards.title') }}</h1>
                <p class="text-muted mb-0">{{ __('home_cards.intro') }}</p>
            </div>

            @if (! $ready)
                <div class="alert alert-warning">{{ __('home_cards.missing_table') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.homeCards.update') }}" method="POST" enctype="multipart/form-data" id="home-cards-form" dir="{{ $isRtl ? 'rtl' : 'ltr' }}">
                @csrf
                <input type="hidden" name="cards_present" value="1">

                <div class="d-flex justify-content-between align-items-center gap-3 mb-3">
                    <span class="text-muted" id="home-card-count" data-template="{{ __('home_cards.count') }}"></span>
                    <button type="button" class="btn btn-outline-dark" id="home-card-add" @disabled(! $ready)>
                        {{ __('home_cards.add') }}
                    </button>
                </div>

                <div id="home-card-list" class="d-flex flex-column gap-3">
                    @foreach ($rows as $index => $row)
                        @include('admin.home-cards._card', ['index' => $index, 'row' => $row, 'choices' => $choices])
                    @endforeach
                </div>

                <p class="text-muted mt-3 mb-0 {{ $rows === [] ? '' : 'd-none' }}" id="home-card-empty">{{ __('home_cards.empty') }}</p>

                <div class="mt-4">
                    <button type="submit" class="btn btn-dark" @disabled(! $ready)>{{ __('home_cards.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</main>

<template id="home-card-template">
    @include('admin.home-cards._card', [
        'index' => '__INDEX__',
        'row' => ['id' => '', 'title_en' => '', 'title_ar' => '', 'filter_type' => 'all', 'filter_value' => '', 'image_url' => ''],
        'choices' => $choices,
    ])
</template>

<style>
    .home-card-editor .home-card-preview {
        height: 180px;
        background: var(--admin-surface-2, #f4f1ea);
    }
    .home-card-editor .home-card-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const max = 6;
        const list = document.getElementById('home-card-list');
        const addBtn = document.getElementById('home-card-add');
        const empty = document.getElementById('home-card-empty');
        const countLabel = document.getElementById('home-card-count');
        const template = document.getElementById('home-card-template');
        const choices = @json($choices);
        const chooseLabel = @json(__('home_cards.choose_value'));

        function cards() {
            return list.querySelectorAll('.home-card-editor');
        }

        function refresh() {
            const total = cards().length;
            addBtn.disabled = total >= max;
            empty.classList.toggle('d-none', total !== 0);
            countLabel.textContent = countLabel.dataset.template
                .replace(':count', total)
                .replace(':max', max);
        }

        function fillValues(card) {
            const type = card.querySelector('.js-filter-type').value;
            const select = card.querySelector('.js-filter-value');
            const current = select.dataset.selected || select.value;
            select.innerHTML = '';
            if (type === 'all') {
                select.hidden = true;
                select.disabled = true;
                return;
            }
            select.hidden = false;
            select.disabled = false;
            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = chooseLabel;
            select.appendChild(placeholder);
            (choices[type] || []).forEach(function (option) {
                const el = document.createElement('option');
                el.value = String(option.value);
                el.textContent = option.label;
                if (String(option.value) === String(current)) {
                    el.selected = true;
                }
                select.appendChild(el);
            });
            select.dataset.selected = '';
        }

        list.addEventListener('change', function (event) {
            const card = event.target.closest('.home-card-editor');
            if (!card) return;
            if (event.target.classList.contains('js-filter-type')) {
                const select = card.querySelector('.js-filter-value');
                select.dataset.selected = '';
                select.value = '';
                fillValues(card);
            }
            if (event.target.classList.contains('js-card-image') && event.target.files[0]) {
                const img = card.querySelector('.home-card-preview img');
                img.src = URL.createObjectURL(event.target.files[0]);
                card.querySelector('.home-card-preview').hidden = false;
            }
        });

        list.addEventListener('click', function (event) {
            const button = event.target.closest('[data-remove-card]');
            if (!button) return;
            button.closest('.home-card-editor').remove();
            refresh();
        });

        addBtn.addEventListener('click', function () {
            if (cards().length >= max) return;
            const markup = template.innerHTML.replaceAll('__INDEX__', 'new-' + Date.now());
            const wrap = document.createElement('div');
            wrap.innerHTML = markup.trim();
            const card = wrap.firstElementChild;
            list.appendChild(card);
            fillValues(card);
            refresh();
        });

        document.getElementById('home-cards-form').addEventListener('submit', function () {
            cards().forEach(function (card, index) {
                card.querySelectorAll('[name]').forEach(function (input) {
                    input.name = input.name.replace(/cards\[[^\]]+\]/, 'cards[' + index + ']');
                    if (input.disabled) input.disabled = false;
                });
            });
        });

        refresh();
    });
</script>

<x-admin.footer />
