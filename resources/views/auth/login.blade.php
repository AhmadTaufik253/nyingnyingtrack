<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in — NyingnyingTrack</title>

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

            <div class="login-header">
                <h1>Sign in</h1>
                <p>Access your fleet dashboard</p>
            </div>

            @if ($errors->any())
                <div class="alert-danger">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="you@company.com" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="form-options">
                    <label class="checkbox-label">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                </div>

                <button type="submit" class="btn-submit">Sign in</button>
            </form>

        </div>

        <div class="signal-readout">
            <span class="signal-dot"></span>
            <span id="coord">-6.276207, 106.688898</span>
            <span class="signal-sep">·</span>
            <span>fix locked</span>
        </div>

    </div>

    <script>
        // subtle live-feeling coordinate jitter — purely cosmetic, reinforces the tracking theme
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
