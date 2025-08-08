<?php

// Generate QR value containing student details
$student_id = htmlspecialchars($studentData['student_id'] ?? 'N/A');
$name = htmlspecialchars($studentData['name'] ?? 'N/A');
// $l_name = htmlspecialchars($studentData['l_name'] ?? 'N/A');


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student QR Code • USep Attendance System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-image: 
                radial-gradient(circle at 1px 1px, #e2e8f0 1px, transparent 0),
                linear-gradient(to right, rgba(255,255,255,0.2), rgba(255,255,255,0.2));
            background-size: 24px 24px;
            background-color: #f8f9fa;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
<body class="p-4 md:p-6">
    <div class="max-w-5xl mx-auto space-y-8">
        <!-- QR Code Section -->
        <div>
            <h3 class="text-xl md:text-2xl font-bold text-[#a31d1d] mb-6 [text-shadow:_0px_1px_0px_rgb(0_0_0_/_0.1)]">
                Student QR Code
            </h3>
            <?php if(strpos($name, ',') !== false): ?>
                <?php if ($checker): ?>
                    <!-- QR Code Card -->
                    <div class="glass-card p-8 rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black space-y-6">
                        <div class="flex flex-col items-center">
                            <!-- Student Info -->
                            <div class="bg-white/80 backdrop-blur-sm px-6 py-3 rounded-xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black mb-8 w-full md:w-auto">
                                <p class="text-base md:text-lg font-bold text-gray-800">
                                    <?= "Name: $name " ?>
                                </p>
                                <p class="text-base md:text-lg font-bold text-gray-800">
                                    <?= "Student ID: $student_id" ?>
                                </p>
                            </div>

                            <!-- QR Code Container -->
                            <div id="qrcode" class="p-4 bg-white rounded-2xl shadow-lg floating"></div>

                            <!-- Download Button -->
                            <button id="downloadBtn" 
                                    class="mt-8 hidden bg-[#a31d1d] text-white px-6 py-2 rounded-xl font-semibold shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black hover:bg-[#8a1818] transition-all duration-200 text-sm md:text-base">
                                Download QR Code
                            </button>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Warning Message -->
                    <div class="glass-card p-8 rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                        <div class="flex items-center justify-center gap-3 text-red-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            <span class="text-base md:text-lg font-semibold">Please upload a profile picture to view your QR Code.</span>
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="glass-card p-8 rounded-2xl shadow-[0px_4px_0px_1px_rgba(0,0,0,1)] outline outline-1 outline-black">
                    <div class="flex items-center justify-center gap-3 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <span class="text-base md:text-lg font-semibold">Something went wrong. Please try again.</span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const qrValue = <?php echo json_encode($qr_value, JSON_HEX_TAG); ?>;
            const qrCodeContainer = document.getElementById('qrcode');
            const downloadBtn = document.getElementById('downloadBtn');

            if (qrValue) {
                const options = {
                    width: 250,
                    height: 250,
                    margin: 1,
                    color: {
                        dark: "#000000",
                        light: "#FFFFFF"
                    }
                };

                QRCode.toDataURL(qrValue, options)
                    .then(url => {
                        const img = document.createElement('img');
                        img.src = url;
                        img.alt = "QR Code";
                        img.classList.add('rounded-xl', 'shadow-lg');
                        qrCodeContainer.appendChild(img);

                        downloadBtn.style.display = "inline-block";
                        downloadBtn.addEventListener("click", function () {
                            // Canvas size
                            const canvasWidth = 350;
                            const canvasHeight = 420;
                            const canvas = document.createElement('canvas');
                            canvas.width = canvasWidth;
                            canvas.height = canvasHeight;
                            const ctx = canvas.getContext('2d');

                            // White background
                            ctx.fillStyle = "#fff";
                            ctx.fillRect(0, 0, canvasWidth, canvasHeight);

                            // Header
                            ctx.font = "bold 22px Poppins, sans-serif";
                            ctx.fillStyle = "#a31d1d";
                            ctx.textAlign = "center";
                            ctx.fillText("Official QR Code", canvasWidth / 2, 40);

                            // Draw QR code with rectangular border
                            const qrImg = new window.Image();
                            qrImg.onload = function () {
                                // Border (rectangle, no radius)
                                const qrX = (canvasWidth - 250) / 2;
                                const qrY = 60;
                                const qrSize = 250;
                                const borderWidth = 6;

                                ctx.save();
                                ctx.lineWidth = borderWidth;
                                ctx.strokeStyle = "#a31d1d";
                                ctx.strokeRect(qrX, qrY, qrSize, qrSize);
                                ctx.restore();

                                // QR code
                                ctx.drawImage(qrImg, qrX, qrY, qrSize, qrSize);

                                // Name
                                ctx.font = "bold 18px Poppins, sans-serif";
                                ctx.fillStyle = "#222";
                                ctx.fillText(<?php echo json_encode($name); ?>, canvasWidth / 2, 340);

                                // ID number
                                ctx.font = "16px Poppins, sans-serif";
                                ctx.fillStyle = "#444";
                                ctx.fillText("ID: " + <?php echo json_encode($student_id); ?>, canvasWidth / 2, 370);

                                // Download
                                const a = document.createElement('a');
                                a.href = canvas.toDataURL('image/png');
                                a.download = "Student_QRCode.png";
                                document.body.appendChild(a);
                                a.click();
                                document.body.removeChild(a);
                            };
                            qrImg.src = url;
                        });
                    })
                    .catch(error => console.error("QR Code generation error:", error));
            }
        });
    </script>
</body>
</html>
