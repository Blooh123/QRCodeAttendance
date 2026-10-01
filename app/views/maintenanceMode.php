<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#f3f5f1">
    <title>Service Unavailable</title>
    <style>
        :root {
            color-scheme: light;
            font-family: "Trebuchet MS", "Segoe UI", sans-serif;
            background: #f3f5f1;
            color: #202b27;
        }

        * {
            box-sizing: border-box;
        }

        body {
            display: grid;
            min-width: 320px;
            min-height: 100vh;
            min-height: 100svh;
            margin: 0;
            place-items: center;
            padding: 32px 22px;
            background-color: #f3f5f1;
            background-image: linear-gradient(rgba(32, 43, 39, .035) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(32, 43, 39, .035) 1px, transparent 1px);
            background-size: 36px 36px;
        }

        main {
            width: min(100%, 540px);
            text-align: center;
            animation: arrive .55s ease-out both;
        }

        .status-mark {
            display: grid;
            width: 64px;
            height: 64px;
            margin: 0 auto 28px;
            place-items: center;
            border: 1px solid #e5b38c;
            border-radius: 50%;
            background: #fff8f1;
            color: #a74b24;
        }

        .status-mark svg {
            width: 28px;
            height: 28px;
        }

        .eyebrow {
            margin: 0 0 14px;
            color: #a74b24;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
        }

        h1 {
            margin: 0;
            font-size: clamp(2rem, 8vw, 3.25rem);
            line-height: 1.08;
            font-weight: 700;
        }

        .message {
            max-width: 410px;
            margin: 20px auto 0;
            color: #58635e;
            font-size: 1.05rem;
            line-height: 1.7;
        }

        .divider {
            width: 44px;
            height: 3px;
            margin: 30px auto 22px;
            border: 0;
            background: #d77b48;
        }

        .contact-note {
            margin: 0;
            color: #58635e;
            font-size: .92rem;
            line-height: 1.6;
        }

        @keyframes arrive {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                scroll-behavior: auto !important;
            }
        }
    </style>
</head>
<body>
    <main aria-labelledby="page-title">
        <div class="status-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="9"></circle>
                <path d="M12 7.5v5"></path>
                <path d="M12 16.5h.01"></path>
            </svg>
        </div>
        <p class="eyebrow">Service notice</p>
        <h1 id="page-title">Service unavailable</h1>
        <p class="message">This service is currently unavailable, and access has been temporarily paused.</p>
        <hr class="divider">
        <p class="contact-note">Please contact your service administrator for assistance.</p>
    </main>
</body>
</html>
