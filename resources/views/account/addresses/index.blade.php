<x-account.layout :title="__('account.addresses')">
    <p class="text-muted mb-4">{{ __('account.addresses_hint') }}</p>

    <div class="account-card mb-4">
        <div class="card-header py-3 px-4">{{ __('account.add_address') }}</div>
        <div class="card-body p-4">
            <form action="{{ route('account.addresses.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">{{ __('account.address_line1') }}</label>
                    <input type="text" name="address_line1" class="form-control" value="{{ old('address_line1') }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('account.address_line2') }}</label>
                    <input type="text" name="address_line2" class="form-control" value="{{ old('address_line2') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('account.city') }}</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('account.state') }}</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">{{ __('account.postal_code') }}</label>
                    <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('account.country') }}</label>
                    <input type="text" name="country" class="form-control" value="{{ old('country', 'Egypt') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">{{ __('account.phone') }}</label>
                    <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number') }}" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-dark">{{ __('account.save_address') }}</button>
                </div>
            </form>
        </div>
    </div>

    @if ($addresses->isEmpty())
        <div class="account-card account-empty">
            <i class="fa-solid fa-location-dot d-block"></i>
            <p class="mb-0">{{ __('account.no_addresses') }}</p>
        </div>
    @else
        <div class="row g-3">
            @foreach ($addresses as $address)
                <div class="col-md-6">
                    <div class="account-card h-100">
                        <div class="card-body p-4">
                            @if ($address->is_default)
                                <span class="account-default-badge">{{ __('account.default_address') }}</span>
                            @endif
                            <p class="mb-1 fw-semibold">{{ $address->address_line1 }}</p>
                            @if ($address->address_line2)
                                <p class="mb-1 text-muted small">{{ $address->address_line2 }}</p>
                            @endif
                            <p class="mb-1 small">{{ $address->city }} {{ $address->postal_code }}</p>
                            <p class="mb-3 small">{{ $address->country }}</p>
                            <p class="mb-3 small"><i class="fa-solid fa-phone me-1"></i>{{ $address->phone_number }}</p>

                            <div class="d-flex gap-2 flex-wrap">
                                @unless ($address->is_default)
                                    <form action="{{ route('account.addresses.default', $address->id) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-dark">{{ __('account.set_default') }}</button>
                                    </form>
                                @endunless
                                <button class="btn btn-sm btn-outline-dark" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#edit-address-{{ $address->id }}">
                                    {{ __('account.edit_address') }}
                                </button>
                                <form action="{{ route('account.addresses.destroy', $address->id) }}" method="POST"
                                    onsubmit="return confirm('{{ __('account.delete_address') }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">{{ __('account.delete_address') }}</button>
                                </form>
                            </div>

                            <div class="collapse mt-3" id="edit-address-{{ $address->id }}">
                                <form action="{{ route('account.addresses.update', $address->id) }}" method="POST" class="row g-2">
                                    @csrf
                                    @method('PUT')
                                    <div class="col-12">
                                        <input type="text" name="address_line1" class="form-control form-control-sm"
                                            value="{{ $address->address_line1 }}" required>
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="address_line2" class="form-control form-control-sm"
                                            value="{{ $address->address_line2 }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="city" class="form-control form-control-sm"
                                            value="{{ $address->city }}" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="phone_number" class="form-control form-control-sm"
                                            value="{{ $address->phone_number }}" required>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="state" class="form-control form-control-sm"
                                            value="{{ $address->state }}">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="postal_code" class="form-control form-control-sm"
                                            value="{{ $address->postal_code }}">
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="country" class="form-control form-control-sm"
                                            value="{{ $address->country }}">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-sm btn-dark">{{ __('account.save_address') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-account.layout>
