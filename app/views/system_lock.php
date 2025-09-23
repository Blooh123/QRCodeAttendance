<?php
$username = $username ?? ($_COOKIE['locked_user'] ?? 'Unknown User');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Locked</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;800&display=swap">
    <style>
        body {
            background-image: 
                radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0),
                linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0.2));
            background-size: 24px 24px;
            background-color: #f8f9fa;
            font-family: 'Poppins', Arial, Helvetica, sans-serif !important;
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="bg-[#f8f9fa]">

<div class="min-h-screen flex flex-col items-center justify-center p-4">
    <!-- Header Logo -->
    <div class="w-full max-w-4xl flex items-center justify-center mb-8">
        <div class="floating">
            <i class="fas fa-lock text-7xl text-[#800000]"></i>
        </div>
    </div>

    <!-- Main Content Container -->
    <div class="w-full max-w-md flex flex-col items-center justify-center relative bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-[0_8px_32px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_12px_48px_-8px_rgba(0,0,0,0.2)] transition-all duration-300 floating">
        <div class="text-[#515050] text-3xl md:text-5xl font-extrabold mb-8 text-center w-full md:w-auto rounded-xl [text-shadow:_0px_2px_0px_rgb(0_0_0_/_0.1)]">
            SYSTEM LOCKED
        </div>
        <div class="w-full mb-4 text-center">
            <span class="text-lg text-gray-700">Welcome, <span class="font-semibold"><?php echo htmlspecialchars($username); ?></span></span>
        </div>
        <div class="w-full mb-6 text-base text-gray-600 text-center">
            Your session has been locked due to inactivity.<br>
            Please re-enter your password to unlock and continue.
        </div>
        <form method="POST" class="w-full flex flex-col items-center space-y-6" action="<?php echo ROOT; ?>system-lock">
            <div class="w-full mb-4 relative">
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    placeholder="Re-enter your password"
                    class="w-full h-12 md:h-14 pl-11 pr-12 bg-white rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-normal text-neutral-600 focus:outline-[#800000] focus:ring-2 focus:ring-[#800000] transition-all duration-200"
                    autocomplete="current-password"
                />
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7C3.732 7.943 7.523 5 12 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <!-- Eye Icon -->
                <button type="button" onclick="togglePassword()"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-[#800000] focus:outline-none z-20">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268-2.943 9.542-7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            <button type="submit"
                class="w-full h-12 md:h-14 bg-[#800000] hover:bg-[#660000] rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-bold text-white transition-all duration-200 focus:outline-[#800000] focus:ring-2 focus:ring-[#800000]">
                Unlock & Login
            </button>
        </form>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268-2.943 9.543-7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
        `;
    } else {
        passwordInput.type = 'password';
        eyeIcon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268-2.943 9.542-7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
    }
}
</script>
</body>
</html>