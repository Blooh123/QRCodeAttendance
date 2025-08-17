<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Maintenance Mode • QRCode Attendance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            min-height: 100vh;
            font-family: 'Poppins', Arial, Helvetica, sans-serif;
            overflow: hidden;
            background: #e0e7ef;
        }
        .background-svg {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: 0;
            pointer-events: none;
            user-select: none;
        }
        .glass-card {
            background: rgba(255,255,255,0.85);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.18);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 2rem;
            border: 1px solid rgba(255,255,255,0.25);
            z-index: 2;
        }
        .gear {
            animation: spin 2s linear infinite;
            transform-origin: 50% 50%;
            filter: drop-shadow(0 0 8px #a31d1d44);
        }
        @keyframes spin {
            0% { transform: rotate(0deg);}
            100% { transform: rotate(360deg);}
        }
        .bounce {
            animation: bounce 1.5s infinite alternate;
        }
        @keyframes bounce {
            0% { transform: translateY(0);}
            100% { transform: translateY(-20px);}
        }
        .progress-bar {
            width: 100%;
            height: 8px;
            background: #f3f3f3;
            border-radius: 8px;
            overflow: hidden;
            margin: 24px 0 12px 0;
        }
        .progress-bar-inner {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #a31d1d, #fbbf24);
            border-radius: 8px;
            animation: progressAnim 2.5s infinite;
        }
        @keyframes progressAnim {
            0% { width: 0%; }
            50% { width: 100%; }
            100% { width: 0%; }
        }
        .footer {
            position: fixed;
            left: 0; right: 0; bottom: 0;
            width: 100%;
            text-align: center;
            padding: 12px 0;
            font-size: 1rem;
            color: #a31d1d;
            background: rgba(255,255,255,0.7);
            font-weight: 500;
            letter-spacing: 0.5px;
            z-index: 3;
        }
        .try-again-btn[aria-busy="true"] {
            pointer-events: none;
            opacity: 0.7;
        }
        /* Ensure card is above background */
        .main-content {
            position: relative;
            z-index: 2;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <!-- SVG Background Illustration -->
    <div class="background-svg" aria-hidden="true">
        <svg width="100%" height="100%" viewBox="0 0 1920 1080" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="skyGradient" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="#b3d8f6"/>
                    <stop offset="100%" stop-color="#e0e7ef"/>
                </linearGradient>
                <radialGradient id="cloudWhite" cx="50%" cy="50%" r="50%">
                    <stop offset="0%" stop-color="#fff" stop-opacity="0.9"/>
                    <stop offset="100%" stop-color="#fff" stop-opacity="0.3"/>
                </radialGradient>
            </defs>
            <!-- Sky -->
            <rect width="1920" height="1080" fill="url(#skyGradient)"/>
            <!-- Clouds -->
            <ellipse cx="400" cy="180" rx="180" ry="60" fill="url(#cloudWhite)"/>
            <ellipse cx="600" cy="220" rx="120" ry="40" fill="url(#cloudWhite)"/>
            <ellipse cx="1500" cy="160" rx="160" ry="50" fill="url(#cloudWhite)"/>
            <ellipse cx="1200" cy="250" rx="100" ry="30" fill="url(#cloudWhite)"/>
            <!-- Cityscape silhouette -->
            <g>
                <rect x="0" y="900" width="200" height="180" fill="#b0b7c3"/>
                <rect x="210" y="950" width="80" height="130" fill="#a3aab8"/>
                <rect x="320" y="920" width="120" height="160" fill="#c2c8d6"/>
                <rect x="470" y="970" width="60" height="110" fill="#b0b7c3"/>
                <rect x="550" y="940" width="90" height="140" fill="#a3aab8"/>
                <rect x="660" y="980" width="60" height="100" fill="#c2c8d6"/>
                <rect x="740" y="930" width="100" height="150" fill="#b0b7c3"/>
                <rect x="860" y="960" width="80" height="120" fill="#a3aab8"/>
                <rect x="960" y="900" width="180" height="180" fill="#c2c8d6"/>
                <rect x="1160" y="950" width="100" height="130" fill="#b0b7c3"/>
                <rect x="1280" y="920" width="120" height="160" fill="#a3aab8"/>
                <rect x="1420" y="970" width="60" height="110" fill="#c2c8d6"/>
                <rect x="1500" y="940" width="90" height="140" fill="#b0b7c3"/>
                <rect x="1610" y="980" width="60" height="100" fill="#a3aab8"/>
                <rect x="1690" y="930" width="100" height="150" fill="#c2c8d6"/>
                <rect x="1800" y="960" width="120" height="120" fill="#b0b7c3"/>
            </g>
        </svg>
    </div>
    <div class="main-content w-full flex items-center justify-center min-h-screen">
        <div class="relative glass-card px-10 py-14 max-w-lg w-full flex flex-col items-center">
            <div class="flex justify-center mb-8 space-x-4">
                <svg class="gear w-16 h-16 text-[#a31d1d]" fill="none" viewBox="0 0 64 64" stroke="currentColor">
                    <circle cx="32" cy="32" r="24" stroke-width="6" />
                    <path d="M32 8v8M32 48v8M8 32h8M48 32h8M16.97 16.97l5.66 5.66M41.37 41.37l5.66 5.66M16.97 47.03l5.66-5.66M41.37 22.63l5.66-5.66" stroke-width="4"/>
                </svg>
                <svg class="gear w-10 h-10 text-[#a31d1d] opacity-70" style="animation-direction: reverse;" fill="none" viewBox="0 0 64 64" stroke="currentColor">
                    <circle cx="32" cy="32" r="18" stroke-width="4" />
                    <path d="M32 14v4M32 46v4M14 32h4M46 32h4M21.5 21.5l3 3M39.5 39.5l3 3M21.5 42.5l3-3M39.5 24.5l3-3" stroke-width="3"/>
                </svg>
            </div>
            <div class="bounce mb-6">
                <svg class="w-16 h-16 mx-auto text-[#a31d1d]" fill="none" viewBox="0 0 48 48" stroke="currentColor">
                    <rect x="6" y="6" width="12" height="12" rx="2" stroke-width="3"/>
                    <rect x="30" y="6" width="12" height="12" rx="2" stroke-width="3"/>
                    <rect x="6" y="30" width="12" height="12" rx="2" stroke-width="3"/>
                    <rect x="21" y="21" width="6" height="6" rx="1" stroke-width="3"/>
                    <rect x="30" y="30" width="6" height="6" rx="1" stroke-width="3"/>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold text-[#a31d1d] mb-4 text-center drop-shadow-lg">We'll be back soon!</h1>
            <p class="text-gray-700 text-lg mb-6 text-center font-medium">
                The <span class="font-bold text-[#a31d1d]">QRCode Attendance System</span> is currently undergoing scheduled maintenance.<br>
                Please check back later. Thank you for your patience!
            </p>
            <div class="progress-bar">
                <div class="progress-bar-inner"></div>
            </div>
            <button id="tryAgainBtn" onclick="animateMessage()" class="try-again-btn mt-6 px-8 py-3 bg-[#a31d1d] hover:bg-[#7c1818] text-white rounded-xl font-bold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200 flex items-center gap-2 text-lg" aria-busy="false">
                <span id="btnText">Try Again</span>
                <svg id="btnSpinner" class="hidden animate-spin ml-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </button>
            <div id="message" class="mt-4 text-[#a31d1d] font-semibold text-center"></div>
        </div>
    </div>
    <div class="footer">
        &copy; <?php echo date('Y'); ?> QRCode Attendance System &mdash; Need help? <a href="mailto:support@yourdomain.com" class="underline hover:text-[#7c1818]">Contact Support</a>
    </div>
    <script>
        function animateMessage() {
            const msg = document.getElementById('message');
            const btn = document.getElementById('tryAgainBtn');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            btn.setAttribute('aria-busy', 'true');
            btnSpinner.classList.remove('hidden');
            btnText.textContent = 'Trying...';
            msg.textContent = "Still under maintenance...";
            msg.style.opacity = 1;
            msg.animate([
                { opacity: 0, transform: "translateY(20px)" },
                { opacity: 1, transform: "translateY(0)" }
            ], {
                duration: 500,
                fill: "forwards"
            });
            setTimeout(() => {
                btn.setAttribute('aria-busy', 'false');
                btnSpinner.classList.add('hidden');
                btnText.textContent = 'Try Again';
                msg.animate([
                    { opacity: 1, transform: "translateY(0)" },
                    { opacity: 0, transform: "translateY(-20px)" }
                ], {
                    duration: 500,
                    fill: "forwards"
                });
            }, 2000);
        }
    </script>
</body>
</html>