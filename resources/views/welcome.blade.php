<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Smart Omega</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #224abe;
            --glass-bg: rgba(255, 255, 255, 0.95);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            /* Modern Gradient Background */
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Entrance Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-container {
            animation: fadeInUp 0.8s ease-out;
            max-width: 1000px;
            width: 100%;
            padding: 20px;
        }

        .login-card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
        }

        .login-image {
            background: url('https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
            position: relative;
        }

        /* Overlay on Image */
        .login-image::after {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(78, 115, 223, 0.2);
        }

        .form-control {
            border-radius: 10px;
            padding: 12px 15px;
            border: 1px solid #e1e1e1;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
            transform: translateX(5px); /* Subtle animation on focus */
        }

        .btn-login {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
        }

        .company-name {
            font-weight: 800;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .footer-text {
            font-size: 0.85rem;
            color: #7f8c8d;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="card login-card">
        <div class="row g-0">
            <div class="col-md-6 d-none d-md-block login-image"></div>

            <div class="col-md-6 p-4 p-lg-5">
                <div class="text-center mb-5">
                    @foreach($Company as $Details)
                        <h2 class="company-name">{{ $Details->name }}</h2>
                        <p class="text-muted small"><i class="fas fa-map-marker-alt me-1"></i> {{ $Details->address }}</p>
                    @endforeach
                </div>

                <form action="{{ route('login') }}" method="post">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">Username</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-user text-muted"></i></span>
                            <input id="username" type="text" class="form-control border-start-0 @error('username') is-invalid @enderror"
                                   name="username" value="{{ old('username') }}" placeholder="Enter username" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold text-secondary">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="fas fa-lock text-muted"></i></span>
                            <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror"
                                   name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn btn-primary btn-login">
                            LOGIN TO SYSTEM <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>

                <div class="text-center footer-text">
                    <p>© {{ date('Y') }} Smart Omega (PVT) Ltd. <br>
                    <small>Enterprise Management System v2.0</small></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>