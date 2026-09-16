@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0 ps-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('status'))
    <div class="alert alert-success mb-3">{{ session('status') }}</div>
@endif

@if (session('error'))
    <div class="alert alert-danger mb-3">{{ session('error') }}</div>
@endif
