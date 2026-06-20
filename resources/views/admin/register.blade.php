<x-admin.header />
<x-admin.navbar />

<div class="d-flex justify-content-center align-items-center vh-100">
    <form action="{{ route('admin.register') }}" method="POST" class="p-4 shadow-sm bg-white rounded w-25">
        <div class="d-flex justify-content-center px-4 pt-3">
    <a class="text-decoration-none mx-2 {{ app()->getLocale() == 'en' ? 'fw-bold text-primary' : 'text-black' }}" href="{{ url('/lang/en') }}">EN</a>
    |
    <a class="text-decoration-none mx-2 {{ app()->getLocale() == 'ar' ? 'fw-bold text-primary' : 'text-black' }}" href="{{ url('/lang/ar') }}">العربية</a>
</div>
        @csrf
        <h3 class="text-center mb-4">{{ __('admin_register.title') }}</h3>

        <!-- Name Field -->
        <div class="mb-3">
            <label for="name" class="form-label">{{ __('admin_register.name') }}</label>
            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" required>
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email Field -->
        <div class="mb-3">
            <label for="email" class="form-label">{{ __('admin_register.email') }}</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password Field -->
        <div class="mb-3">
            <label for="password" class="form-label">{{ __('admin_register.password') }}</label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password Field -->
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">{{ __('admin_register.confirm_password') }}</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" required>
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-dark w-100">{{ __('admin_register.register') }}</button>

        <!-- General Error Messages -->
        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </form>
</div>

<x-web.footer />
