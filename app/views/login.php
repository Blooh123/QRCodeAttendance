<?php
global $imageSource, $imageSource2, $imageSource4;
    require "../app/core/imageConfig.php";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="<?php echo ROOT?>assets/js/handleIssue.js"></script>
    <link rel="icon" type="image/x-icon" href="<?php echo $imageSource?>">
    <link rel="stylesheet" href="<?php echo ROOT?>assets/css/loginStyle.css">
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
        
        /* Existing styles */
        .text-stroke-2 {
            -webkit-text-stroke: 2px black;
            text-stroke: 2px black;
        }
        .text-stroke-1 {
            -webkit-text-stroke: 1px black;
            text-stroke: 1px black;
        }
    </style>
    <title>Attendance System • LogIn</title>

</head>
<body class="bg-[#f8f9fa]">

<!-- Toast Notification -->
<?php if (isset($_SESSION['error'])): ?>
    <div id="toast" class="toast fixed top-4 left-1/2 transform -translate-x-1/2 bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg flex items-center justify-between">
        <span><?php echo $_SESSION['error']; ?></span>
        <button onclick="hideToast()" class="ml-4 text-red-700 hover:text-red-900">&times;</button>
    </div>
    <?php unset($_SESSION['error']); // Clear the error message after displaying ?>
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
    

    <!-- Main Content Container -->
    <div class="w-full max-w-md flex flex-col items-center justify-center relative bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-[0_8px_32px_-4px_rgba(0,0,0,0.1)] hover:shadow-[0_12px_48px_-8px_rgba(0,0,0,0.2)] transition-all duration-300 floating">
        <!-- Illustration Behind Form -->
        <img 
            class="absolute inset-0 w-full h-full object-contain opacity-20 pointer-events-none select-none z-0 scale-125"
            src="<?php echo $imageSource2?>" 
            alt="Illustration" 
            style="filter: blur(0.5px);" 
        />
        
        <!-- Form Fields Centered -->
        <form method="POST" class="w-full flex flex-col items-center justify-center z-10 relative space-y-6" action="<?php echo ROOT;?>login">
            <div class="text-[#515050] text-3xl md:text-5xl font-extrabold mb-8 text-center w-full md:w-auto rounded-xl [text-shadow:_0px_2px_0px_rgb(0_0_0_/_0.1)]">
                LOG IN
            </div>
            <div class="w-full mb-4 relative">
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Username"
                    class="w-full h-12 md:h-14 pl-11 pr-4 bg-white rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-normal text-neutral-600 focus:outline-[#ec4899] focus:ring-2 focus:ring-[#ec4899] transition-all duration-200"
                    autocomplete="username"
                />
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A9 9 0 1112 21a9 9 0 01-6.879-3.196z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            </div>
            <div class="w-full mb-4 relative">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    class="w-full h-12 md:h-14 pl-11 pr-12 bg-white rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-normal text-neutral-600 focus:outline-[#ec4899] focus:ring-2 focus:ring-[#ec4899] transition-all duration-200"
                    autocomplete="current-password"
                />
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7C3.732 7.943 7.523 5 12 5z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <!-- Eye Icon -->
                <button type="button" onclick="togglePassword()"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-[#ec4899] focus:outline-none z-20">
                    <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                         class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
            <div class="w-full mb-6 flex flex-row items-center">
                <a href="javascript:void(0);" onclick="showIssueForm()" class="text-[#ec4899] text-lg font-normal hover:underline">Need help?</a>
                <span class="mx-2 text-[#515050]">|</span>
                <a href="<?php echo ROOT;?>forgot-password" class="text-[#ec4899] text-lg font-normal hover:underline">Forgot Password?</a>
            </div>
            <div class="w-full mb-4">
                <button type="submit"
                    class="w-full h-12 md:h-14 bg-[#ec4899] hover:bg-[#db2777] rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black text-base md:text-lg font-bold text-white transition-all duration-200 focus:outline-[#ec4899] focus:ring-2 focus:ring-[#ec4899]">
                    LOGIN
                </button>
            </div>
        </form>
    </div>


    <!-- Hidden Issue Form (Fixed Centered Position) -->
    <div id="issue-form" class="w-full mb-6 hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white/95 backdrop-blur-sm shadow-[0_8px_32px_-4px_rgba(0,0,0,0.2)] rounded-xl p-6 border border-gray-200 max-w-sm mx-auto z-50">
        <label for="issueDetails" class="text-[#515050] text-xl font-normal mb-2">Please specify the issue:</label>
        <textarea id="issueDetails" name="issueDetails" class="w-full h-24 pl-4 text-[#515050] text-lg border border-[#ddd] rounded-lg mb-4" placeholder="Please include your ID number or email"></textarea>

        <div class="w-full flex space-x-4">
            <button type="button" onclick="submitIssue()" class="w-1/2 h-12 bg-[#ec4899] text-white text-xl font-bold rounded-lg">Submit</button>
            <button type="button" onclick="hideIssueForm()" class="w-1/2 h-12 bg-gray-500 text-white text-xl font-bold rounded-lg">Cancel</button>
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

    <!-- Cookie Notice -->
    <div id="cookie-notice" class="fixed bottom-4 left-4 right-4 md:left-auto md:right-6 md:bottom-6 bg-white/95 backdrop-blur-sm shadow-[0_8px_32px_-4px_rgba(0,0,0,0.2)] rounded-2xl p-6 border border-gray-200 max-w-sm mx-auto z-50 transition-all duration-300 ease-in-out hidden floating">
        <div class="text-base text-gray-800 mb-4 leading-relaxed">
            This website uses a session cookie to keep you logged in securely. By using this site, you agree to our use of cookies.
        </div>
        <div class="flex justify-end">
            <button id="accept-cookie" class="bg-blue-600 hover:bg-blue-700 text-white text-base px-5 py-2.5 rounded-lg font-semibold shadow-md">
                Accept
            </button>
        </div>
    </div>

    <script>
        const cookieNotice = document.getElementById("cookie-notice");
        const acceptBtn = document.getElementById("accept-cookie");

        // Show cookie notice if not already accepted
        if (!localStorage.getItem("cookieAccepted")) {
            cookieNotice.classList.remove("hidden");
        }

        // Accept button
        acceptBtn.addEventListener("click", function () {
            localStorage.setItem("cookieAccepted", "true");
            cookieNotice.classList.add("hidden");
        });

        // Show cookie notice on "Cookies notice" link click
        function showCookieNotice() {
            cookieNotice.classList.remove("hidden");
        }
    </script>


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
            }, 500); // Wait for the animation to finish
        }
    }
</script>

<script>
    // Disable right-click
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    // Disable F12, Ctrl+Shift+I, Ctrl+U
    document.addEventListener('keydown', function(e) {
        if (e.key === 'F12' ||
            (e.ctrlKey && e.shiftKey && e.key === 'I') ||
            (e.ctrlKey && e.key === 'u')) {
            e.preventDefault();
        }
    });
</script>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
            `;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            `;
        }
    }
</script>
</body>
</html>