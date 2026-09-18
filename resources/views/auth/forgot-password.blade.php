<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — NyingnyingTrack</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@500;600;700&family=Inter:wght@400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

    <div class="grid-backdrop" aria-hidden="true"></div>

    <div class="login-wrapper">

        <div class="brand-mark">
            <span class="brand-dot"></span>
            <span class="brand-name">nyingnyingtrack</span>
        </div>

        <div class="login-card">

            @if (session('status'))
                <div class="reset-success">
                    <div class="reset-success-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="login-header">
                        <h1>Check your email</h1>
                        <p>{{ session('status') }}</p>
                    </div>
                    <a href="{{ route('login') }}" class="btn-submit reset-back-btn">Back to sign in</a>
                </div>
            @else
                <div class="login-header">
                    <h1>Forgot password?</h1>
                    <p>Enter your email and we'll send a reset link</p>
                </div>

                @if ($errors->any())
                    <div class="alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="you@company.com" value="{{ old('email') }}" required autofocus>
                    </div>

                    <button type="submit" class="btn-submit">Send reset link</button>
                </form>

                <div class="login-footer">
                    <a href="{{ route('login') }}" class="forgot-link">← Back to sign in</a>
                </div>
            @endif

        </div>

        <div class="signal-readout">
            <span class="signal-dot"></span>
            <span id="coord">-6.276207, 106.688898</span>
            <span class="signal-sep">·</span>
            <span>fix locked</span>
        </div>

    </div>

    <script>
        const el = document.getElementById('coord');
        if (el && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            const baseLat = -6.276207, baseLng = 106.688898;
            setInterval(() => {
                const lat = (baseLat + (Math.random() - 0.5) * 0.00008).toFixed(6);
                const lng = (baseLng + (Math.random() - 0.5) * 0.00008).toFixed(6);
                el.textContent = `${lat}, ${lng}`;
            }, 2200);
        }
    </script>

</body>
</html>