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
    <title>Verify OTP • USeP QR Attendance</title>
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

<!-- Toast Notification -->
<?php if (isset($_SESSION['error'])): ?>
    <div id="toast" class="toast fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg flex items-center justify-between">
        <span><?php echo $_SESSION['error']; ?></span>
        <button onclick="hideToast()" class="ml-4 text-red-700 hover:text-red-900">&times;</button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success'])): ?>
    <div id="toast" class="toast fixed top-4 left-1/2 transform -translate-x-1/2 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg flex items-center justify-between">
        <span><?php echo $_SESSION['success']; ?></span>
        <button onclick="hideToast()" class="ml-4 text-green-700 hover:text-green-900">&times;</button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

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
    <?php
        echo $_SESSION['reset_user_id'];
    ?>

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
                VERIFY OTP
            </div>
            <p class="text-[#515050] text-lg mb-8 text-center">Enter the OTP sent to your email address.</p>
            
            <form method="POST" class="w-full space-y-6" action="<?php echo ROOT;?>verify-otp">
                <div class="w-full relative">
                    <input type="text" 
                        name="otp" 
                        required 
                        maxlength="6" 
                        pattern="\d{6}"
                        placeholder="Enter 6-digit OTP"
                        class="w-full h-12 md:h-14 pl-11 pr-4 bg-white rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-normal text-neutral-600 focus:outline-[#a31d1d] focus:ring-2 focus:ring-[#a31d1d] transition-all duration-200"
                    />
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                    </svg>
                </div>

                <div class="w-full relative">
                    <input type="password" 
                        name="new_password" 
                        required 
                        minlength="8"
                        placeholder="Enter new password"
                        class="w-full h-12 md:h-14 pl-11 pr-16 bg-white rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-normal text-neutral-600 focus:outline-[#a31d1d] focus:ring-2 focus:ring-[#a31d1d] transition-all duration-200"
                    />
                    <button type="button" onclick="togglePassword(this)" aria-label="Show password" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-800 text-sm font-semibold">Show</button>
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <div class="w-full relative">
                    <input type="password" 
                        name="confirm_password" 
                        required 
                        minlength="8"
                        placeholder="Confirm new password"
                        class="w-full h-12 md:h-14 pl-11 pr-16 bg-white rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-normal text-neutral-600 focus:outline-[#a31d1d] focus:ring-2 focus:ring-[#a31d1d] transition-all duration-200"
                    />
                    <button type="button" onclick="togglePassword(this)" aria-label="Show password" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-800 text-sm font-semibold">Show</button>
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                
                <div class="w-full flex flex-col space-y-4 md:flex-row md:space-y-0 md:space-x-4">
                    <button type="submit" 
                        class="w-full md:w-1/2 h-12 md:h-14 bg-[#a31d1d] hover:bg-[#7c1818] rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-bold text-white transition-all duration-200 focus:outline-[#a31d1d] focus:ring-2 focus:ring-[#a31d1d]">
                        RESET PASSWORD
                    </button>
                    <a href="<?php echo ROOT;?>forgot-password" 
                        class="w-full md:w-1/2 h-12 md:h-14 bg-gray-500 hover:bg-gray-600 rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-bold text-white transition-all duration-200 focus:outline-gray-500 focus:ring-2 focus:ring-gray-500 flex items-center justify-center">
                        BACK
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

    function togglePassword(btn) {
        const input = btn.parentElement.querySelector('input');
        const show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        btn.textContent = show ? 'Hide' : 'Show';
        btn.setAttribute('aria-label', show ? 'Hide password' : 'Show password');
    }

    // Password validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const newPassword = document.querySelector('input[name="new_password"]').value;
        const confirmPassword = document.querySelector('input[name="confirm_password"]').value;
        
        if (newPassword !== confirmPassword) {
            e.preventDefault();
            alert('Passwords do not match!');
        }
    });
</script>
</body>
</html>