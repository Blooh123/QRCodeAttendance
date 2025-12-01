<?php
global $imageSource, $imageSource2, $imageSource4;
require "../app/core/imageConfig.php";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo $imageSource?>">
    <link rel="stylesheet" href="<?php echo ROOT?>assets/css/loginStyle.css">
    <title>Forgot Password • USeP QR Attendance</title>
    <style>
        /* Add Poppins font and existing styles */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700;800&display=swap');
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
    <!-- Header -->
    <div class="w-full max-w-4xl flex items-center justify-center mb-8">
        <div class="floating">
            <img 
                src="<?php echo $imageSource4 ?>" 
                alt="Logo" 
                class=" backdrop-blur-sm hover:transform hover:scale-105 transition-all duration-300"
            />
        </div>
    </div>

    <!-- Main Content -->
    <div class="w-full max-w-md flex flex-col items-center justify-center relative bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-[0_8px_32px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_12px_48px_-8px_rgba(0,0,0,0.2)] transition-all duration-300 floating">
        <!-- Background Illustration -->
        <img 
            class="absolute inset-0 w-full h-full object-contain opacity-20 pointer-events-none select-none z-0 scale-125"
            src="<?php echo $imageSource2?>" 
            alt="Illustration" 
            style="filter: blur(0.5px);" 
        />
        
        <!-- Form Content -->
        <div class="w-full flex flex-col items-center z-10">
            <div class="text-[#515050] text-3xl md:text-5xl font-extrabold mb-8 text-center w-full md:w-auto rounded-xl [text-shadow:_0px_2px_0px_rgb(0_0_0_/_0.1)]">
                FORGOT PASSWORD
            </div>
            <p class="text-[#515050] text-lg mb-8 text-center">Enter your email address and we'll send you an OTP to reset your password.</p>
            
            <form method="POST" class="w-full space-y-6" action="<?php echo ROOT;?>forgot-password">
                <div class="w-full relative">
                    <input 
                        type="email" 
                        name="email" 
                        required
                        placeholder="Enter your email address"
                        class="w-full h-12 md:h-14 pl-11 pr-4 bg-white rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-normal text-neutral-600 focus:outline-[#a31d1d] focus:ring-2 focus:ring-[#a31d1d] transition-all duration-200"
                    />
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                
                <div class="w-full flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
                    <button type="submit" 
                        class="w-full md:w-1/2 h-12 md:h-14 bg-[#a31d1d] hover:bg-[#7c1818] rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-bold text-white transition-all duration-200 focus:outline-[#a31d1d] focus:ring-2 focus:ring-[#a31d1d]">
                        SEND OTP
                    </button>
                    <a href="<?php echo ROOT;?>login" 
                        class="w-full md:w-1/2 h-12 md:h-14 bg-gray-500 hover:bg-gray-600 rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-bold text-white transition-all duration-200 focus:outline-gray-500 focus:ring-2 focus:ring-gray-500 flex items-center justify-center">
                        BACK TO LOGIN
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <div class="w-full max-w-4xl mt-8 text-center">
        <div class="text-[#515050] text-lg font-normal">Copyright © 2025. All Rights Reserved.</div>
        <div class="flex justify-center space-x-4 mt-2">
            <a href="#" onclick="showCookieNotice()" class="text-[#515050] text-lg font-bold underline">Cookies notice</a>
            <div class="w-px h-6 bg-[#515050]"></div>
            <a href="https://www.usep.edu.ph/usep-data-privacy-statement/" target="_blank" class="text-[#515050] text-lg font-bold underline">Privacy Policy</a>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<?php if (isset($_SESSION['error'])): ?>
    <div id="toast" class="toast fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg flex items-center justify-between">
        <span><?php echo $_SESSION['error']; ?></span>
        <button onclick="hideToast()" class="ml-4 text-red-700 hover:text-red-900">&times;</button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<script>
    // Hide the toast after 5 seconds
    setTimeout(() => {
        hideToast();
    }, 5000);

    function hideToast() {
        const toast = document.getElementById('toast');
        if (toast) {
            toast.classList.add('hide');
            setTimeout(() => {
                toast.remove();
            }, 500);
        }
    }
</script>
</body>
</html>