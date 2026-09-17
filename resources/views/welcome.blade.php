<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <title>Login | Sheeba</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/img/icon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/theme-redesign.css') }}?v={{ filemtime(public_path('assets/css/theme-redesign.css')) }}">
</head>
<body class="auth-body">
    <x-loading-screen label="Loading..." />
    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-logo">
                <span class="logo-mark" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/>
                        <polyline points="3.29 7 12 12 20.71 7"/>
                        <line x1="12" y1="22" x2="12" y2="12"/>
                    </svg>
                </span>
                @foreach($Company as $Details)
                    <span class="logo-text">{{ $Details->name }}</span>
                @endforeach
            </div>

            <h3 class="auth-title">Welcome back</h3>
            @foreach($Company as $Details)
                <p class="auth-subtitle"><i class="fas fa-map-marker-alt me-1"></i>{{ $Details->address }}</p>
            @endforeach

            <form action="{{ route('login') }}" method="post">
                @csrf

                <div class="si-field mb-3">
                    <label for="username">Username</label>
                    <div class="si-icon-wrap">
                        <i class="fas fa-user"></i>
                        <input id="username" type="text" class="form-control @error('username') is-invalid @enderror"
                            name="username" value="{{ old('username') }}" required autocomplete="username" autofocus
                            placeholder="Enter your username">
                    </div>
                    @error('username')
                        <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="si-field mb-4">
                    <label for="password">Password</label>
                    <div class="si-icon-wrap">
                        <i class="fas fa-lock"></i>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password" placeholder="••••••••">
                    </div>
                    @error('password')
                        <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-100 auth-submit">
                    Login to System <i class="fas fa-arrow-right ms-1"></i>
                </button>
            </form>

            <p class="auth-footer">© {{ date('Y') }} Smart Omega (PVT) Ltd.<br>Enterprise Management System v2.0</p>
        </div>
    </div>
</body>
</html>
