@extends('layouts.topnavbar')
@extends('layouts.sidebar')
@section('content')
          <!DOCTYPE html>
            <html lang="en">

            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
                <title>Access Restricted</title>
                <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/fontawesome.min.css">
                <link rel="stylesheet" href="../assets/plugins/fontawesome/css/all.min.css">
                <link rel="stylesheet" href="../assets/css/style.css">
            </head>

            <style>
                .na-wrap{ display:flex; align-items:center; justify-content:center; min-height:60vh; padding:24px; }
                .na-card{
                    background:var(--tr-white); border:1px solid var(--tr-border); border-radius:16px;
                    box-shadow:0 4px 20px rgba(20, 33, 61, 0.05); padding:44px 40px; max-width:440px; text-align:center;
                }
                .na-icon{
                    width:56px; height:56px; border-radius:50%; background:#FEF3C7; color:#B45309;
                    display:flex; align-items:center; justify-content:center; font-size:22px; margin:0 auto 18px;
                }
                .na-card h3{ color:var(--tr-navy); font-weight:700; margin-bottom:8px; }
                .na-card p{ color:var(--tr-text-secondary); font-size:14px; margin-bottom:24px; }
            </style>

            <body>

        <div class="main-wrapper">
            <div class="page-wrapper">
                <div class="content container-fluid">
                    <div class="na-wrap">
                        <div class="na-card">
                            <div class="na-icon"><i class="fas fa-lock"></i></div>
                            <h3>Access Restricted</h3>
                            <p>Your role doesn't have access to {{ $moduleLabel ?? 'this section' }}. If you think this is a mistake, ask an admin to update your role's permissions.</p>
                            <a href="{{ route('home') }}" class="btn btn-primary"><i class="fas fa-house"></i> Back to Home</a>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
            </div>
         </div>

<script src="assets/js/jquery-3.6.0.min.js"></script>
<script src="assets/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/js/script.js"></script>

</body>
@endsection

</html>
