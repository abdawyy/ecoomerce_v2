@php $locale = app()->getLocale(); @endphp

@section('title', __('auth.browser_sessions_title'))
@section('meta_description', __('auth.browser_sessions_description'))

<x-action-section>
    <x-slot name="title">
        {{ __('auth.browser_sessions_title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('auth.browser_sessions_subtitle') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
            {{ __('auth.browser_sessions_info') }}
        </div>

        @if (count($this->sessions) > 0)
            <div class="mt-5 space-y-6">
                @foreach ($this->sessions as $session)
                    <div class="flex items-center">
                        <div>
@if ($session->agent->isDesktop())
    <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25" />
    </svg>
@else
    <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
    </svg>
@endif
                        </div>

                        <div class="ms-3">
                            <div class="text-sm text-gray-600 dark:text-gray-300">
                                {{ $session->agent->platform() ?? __('auth.unknown') }} - {{ $session->agent->browser() ?? __('auth.unknown') }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ $session->ip_address }},
                                @if ($session->is_current_device)
                                    <span class="text-green-500 font-semibold">{{ __('auth.this_device') }}</span>
                                @else
                                    {{ __('auth.last_active') }} {{ $session->last_active }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="flex items-center mt-5">
            <x-button wire:click="confirmLogout" wire:loading.attr="disabled">
                {{ __('auth.logout_other_sessions') }}
            </x-button>

            <x-action-message class="ms-3" on="loggedOut">
                {{ __('auth.done') }}
            </x-action-message>
        </div>

        <x-dialog-modal wire:model.live="confirmingLogout">
            <x-slot name="title">
                {{ __('auth.logout_other_sessions') }}
            </x-slot>

            <x-slot name="content">
                {{ __('auth.logout_confirm_message') }}

                <div class="mt-4" x-data="{}"
                     x-on:confirming-logout-other-browser-sessions.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-input type="password"
                             class="mt-1 block w-3/4"
                             autocomplete="current-password"
                             placeholder="{{ __('auth.password') }}"
                             x-ref="password"
                             wire:model="password"
                             wire:keydown.enter="logoutOtherBrowserSessions" />
                    <x-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-secondary-button wire:click="$toggle('confirmingLogout')" wire:loading.attr="disabled">
                    {{ __('auth.cancel') }}
                </x-secondary-button>

                <x-button class="ms-3" wire:click="logoutOtherBrowserSessions" wire:loading.attr="disabled">
                    {{ __('auth.logout_other_sessions') }}
                </x-button>
            </x-slot>
        </x-dialog-modal>
    </x-slot>
</x-action-section>
