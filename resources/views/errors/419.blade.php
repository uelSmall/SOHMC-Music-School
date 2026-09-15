<!DOCTYPE html>
<html lang="{{ str_replace("_", "-", app()->currentLocale()) }}" dir="{{ language_direction() }}">
    <head>
        <meta charset="utf-8" />
        <link type="image/png" href="{{ asset("img/sohmc-favicon.png") }}" rel="icon" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>Session Expired | {{ config("app.name") }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@500;600;700;800&display=swap" rel="stylesheet" />

        @vite(["resources/css/app-frontend.css"])

        <style>
            .soh-error-wrap {
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 2rem 1.25rem;
                font-family: "Manrope", "Segoe UI", Arial, sans-serif;
                background: linear-gradient(160deg, #a6128d 0%, #8c0375 35%, #6b025e 65%, #4a0140 100%);
                color: #ffffff;
                text-align: center;
            }
            .soh-error-card {
                background: rgba(255, 255, 255, 0.97);
                border-radius: 1.5rem;
                padding: 3rem 2rem;
                max-width: 34rem;
                width: 100%;
                color: #0d0d0d;
                box-shadow: 0 25px 60px -20px rgba(0, 0, 0, 0.5);
            }
            .soh-error-status {
                font-family: "Sora", "Segoe UI", Arial, sans-serif;
                font-size: 3.5rem;
                font-weight: 800;
                line-height: 1;
                background: linear-gradient(135deg, #a6128d, #8c0375);
                -webkit-background-clip: text;
                background-clip: text;
                color: transparent;
            }
            .soh-error-title {
                font-family: "Sora", "Segoe UI", Arial, sans-serif;
                font-size: 1.35rem;
                font-weight: 700;
                margin-top: 0.5rem;
                color: #0d0d0d;
            }
            .soh-error-message {
                margin-top: 0.75rem;
                font-size: 0.95rem;
                line-height: 1.6;
                color: #4b5563;
            }
            .soh-error-actions {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
                justify-content: center;
                margin-top: 1.75rem;
            }
            .soh-error-btn {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.4rem;
                border-radius: 0.7rem;
                padding: 0.7rem 1.4rem;
                font-weight: 600;
                text-decoration: none;
                transition: background-color 0.18s ease, border-color 0.18s ease;
            }
            .soh-error-btn-primary {
                background: #a6128d;
                color: #fff;
            }
            .soh-error-btn-primary:hover {
                background: #8c0375;
            }
            .soh-error-btn-outline {
                border: 1px solid rgba(166, 18, 141, 0.4);
                color: #a6128d;
            }
            .soh-error-btn-outline:hover {
                border-color: #a6128d;
                background: rgba(166, 18, 141, 0.06);
            }
        </style>
    </head>

    <body>
        <div class="soh-error-wrap">
            <div class="soh-error-card">
                <x-application-logo />

                <div class="soh-error-status">419</div>

                <h1 class="soh-error-title">Your session has expired</h1>

                <p class="soh-error-message">
                    You were away for a little while, so for your security we ended the session. Don&rsquo;t worry &mdash; your information is safe. Refresh the page or sign in again to continue.
                </p>

                <div class="soh-error-actions">
                    <a href="{{ url("/") }}" class="soh-error-btn soh-error-btn-primary">Back to Home</a>
                    <a href="{{ route("login") }}" class="soh-error-btn soh-error-btn-outline">Sign In Again</a>
                </div>
            </div>
        </div>
    </body>
</html>