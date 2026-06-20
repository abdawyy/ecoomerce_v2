<x-account.layout :title="__('account.profile_heading')">
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="account-card">
                <div class="card-header py-3 px-4">{{ __('account.profile_info') }}</div>
                <div class="card-body p-4">
                    <form action="{{ route('account.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">{{ __('auth.name') }}</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $user->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('auth.email') }}</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email', $user->email) }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('account.phone') }}</label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone', $user->phone) }}">
                            @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <button type="submit" class="btn btn-dark">{{ __('account.save_profile') }}</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="account-card">
                <div class="card-header py-3 px-4">{{ __('account.change_password') }}</div>
                <div class="card-body p-4">
                    <form action="{{ route('account.profile.password') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">{{ __('account.current_password') }}</label>
                            <input type="password" name="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required>
                            @error('current_password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('account.new_password') }}</label>
                            <input type="password" name="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" required>
                            @error('password', 'updatePassword')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('account.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-dark">{{ __('account.save_password') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-account.layout>
