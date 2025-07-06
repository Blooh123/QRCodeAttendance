<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Maintenance Mode • QRCode Attendance System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #f8f9fa;
            background-image:
                repeating-linear-gradient(135deg, #a31d1d22 0px, #a31d1d22 2px, transparent 2px, transparent 24px),
                repeating-linear-gradient(45deg, #a31d1d11 0px, #a31d1d11 2px, transparent 2px, transparent 24px);
            min-height: 100vh;
            font-family: 'Poppins', Arial, Helvetica, sans-serif;
            overflow: hidden;
        }
        .gear {
            animation: spin 2s linear infinite;
            transform-origin: 50% 50%;
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
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="relative bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl px-10 py-12 max-w-lg w-full flex flex-col items-center">
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
        <h1 class="text-3xl md:text-4xl font-extrabold text-[#a31d1d] mb-4 text-center">Building something awesome!</h1>
        <p class="text-gray-700 text-lg mb-6 text-center">
            The QRCode Attendance System is currently undergoing scheduled maintenance.<br>
            Please check back later. Thank you for your patience!
        </p>
        <button onclick="animateMessage()" class="mt-4 px-6 py-2 bg-[#a31d1d] hover:bg-[#7c1818] text-white rounded-xl font-bold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black transition-all duration-200">
            Try Again
        </button>
        <div id="message" class="mt-4 text-[#a31d1d] font-semibold text-center"></div>
    </div>
    <script>
        function animateMessage() {
            const msg = document.getElementById('message');
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