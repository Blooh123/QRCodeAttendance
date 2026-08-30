<?php
global $imageSource, $imageSource2, $imageSource4;
require "../app/core/imageConfig.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="<?php echo $imageSource ?>">
    <title>QR Code Attendance System • USeP Tagum-Mabini Campus</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        :root {
            --brand-red: #d11f2a;
            --brand-red-deep: #7b0d18;
            --brand-red-soft: #fef2f2;
            --bg-soft: #f7f7f5;
            --text: #111827;
            --muted: #4b5563;
            --panel: #ffffff;
            --line: rgba(148,163,184,0.22);
        }

        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #f9fafb 0%, #f3f4f6 100%);
            color: var(--text);
            overflow-x: hidden;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        a {
            text-decoration: none;
        }

        .hero-shell {
            position: relative;
            background: radial-gradient(circle at top left, rgba(255,255,255,0.22), transparent 24%),
                        linear-gradient(135deg, #0f172a 0%, #111827 20%, #7b0d18 100%);
            overflow: hidden;
        }

        .hero-shell::before,
        .hero-shell::after {
            content: "";
            position: absolute;
            border-radius: 9999px;
            background: rgba(255,255,255,0.08);
            filter: blur(18px);
        }

        .hero-shell::before {
            width: 28rem;
            height: 28rem;
            right: -8rem;
            top: -10rem;
        }

        .hero-shell::after {
            width: 22rem;
            height: 22rem;
            left: -6rem;
            bottom: -10rem;
        }

        .soft-panel {
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.12);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 24px 50px rgba(15, 23, 42, 0.18);
        }

        .card-surface {
            background: rgba(255,255,255,0.96);
            border: 1px solid rgba(148,163,184,0.18);
            box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .card-surface:hover,
        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 26px 50px rgba(15, 23, 42, 0.12);
        }

        .feature-card {
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            border: 1px solid rgba(148,163,184,0.18);
        }

        .section-tag {
            display: inline-block;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--brand-red);
        }

        .qr-phone {
            position: relative;
            width: 100%;
            max-width: 360px;
            margin: 0 auto;
            border-radius: 2.2rem;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            border: 10px solid #111827;
            box-shadow: 0 35px 70px rgba(15, 23, 42, 0.25);
            padding: 18px 16px 14px;
        }

        .qr-phone::before {
            content: "";
            position: absolute;
            left: 50%;
            top: 12px;
            transform: translateX(-50%);
            width: 105px;
            height: 18px;
            background: rgba(255,255,255,0.08);
            border-radius: 9999px;
        }

        .qr-screen {
            position: relative;
            border-radius: 1.5rem;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            overflow: hidden;
            min-height: 510px;
            padding: 20px 18px;
        }

        .qr-code-box {
            position: relative;
            width: 118px;
            height: 118px;
            margin: 0 auto 18px;
            border-radius: 1rem;
            background: #fff;
            border: 10px solid rgba(17,24,39,0.08);
            overflow: hidden;
        }

        .qr-code-box::before,
        .qr-code-box::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(15,23,42,0.95) 0 14%, transparent 14% 22%, rgba(15,23,42,0.95) 22% 36%, transparent 36% 44%, rgba(15,23,42,0.95) 44% 58%, transparent 58% 66%, rgba(15,23,42,0.95) 66% 82%, transparent 82% 90%, rgba(15,23,42,0.95) 90% 100%),
                linear-gradient(rgba(15,23,42,0.95) 0 14%, transparent 14% 22%, rgba(15,23,42,0.95) 22% 36%, transparent 36% 44%, rgba(15,23,42,0.95) 44% 58%, transparent 58% 66%, rgba(15,23,42,0.95) 66% 82%, transparent 82% 90%, rgba(15,23,42,0.95) 90% 100%);
            background-size: 100% 100%;
            opacity: 0.9;
            mask-image: radial-gradient(circle at center, black 52%, transparent 100%);
            -webkit-mask-image: radial-gradient(circle at center, black 52%, transparent 100%);
        }

        .qr-code-box::after {
            animation: pulseQr 2.5s ease-in-out infinite;
        }

        @keyframes pulseQr {
            0% { opacity: 0.4; transform: scale(0.98); }
            50% { opacity: 1; transform: scale(1); }
            100% { opacity: 0.4; transform: scale(0.98); }
        }

        .scanner-line {
            position: absolute;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(90deg, transparent, rgba(220,38,38,0.9), transparent);
            box-shadow: 0 0 16px rgba(220,38,38,0.8);
            animation: scan 2.4s ease-in-out infinite;
        }

        @keyframes scan {
            0% { top: 26%; opacity: 0; }
            20% { opacity: 1; }
            60% { opacity: 1; }
            100% { top: 72%; opacity: 0; }
        }

        .scan-ui {
            position: absolute;
            inset: 20px 18px 18px;
            border: 1px solid rgba(148,163,184,0.2);
            border-radius: 1.4rem;
            pointer-events: none;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.45rem 0.8rem;
            border-radius: 9999px;
            background: rgba(22,163,74,0.1);
            color: #166534;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .floating-card {
            position: absolute;
            padding: 0.75rem 0.9rem;
            border-radius: 1rem;
            background: rgba(255,255,255,0.96);
            box-shadow: 0 18px 35px rgba(15,23,42,0.12);
            border: 1px solid rgba(148,163,184,0.16);
            animation: floaty 4s ease-in-out infinite;
        }

        .floating-card:nth-child(1) { right: 18px; top: 18px; }
        .floating-card:nth-child(2) { left: 18px; bottom: 38px; animation-delay: 0.7s; }

        @keyframes floaty {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        .process-step {
            position: relative;
        }

        .process-step::before {
            content: "";
            position: absolute;
            top: 24%;
            left: -42px;
            width: 38px;
            height: 2px;
            background: linear-gradient(90deg, rgba(220,38,38,0.4), rgba(220,38,38,0.8));
        }

        .process-step:first-child::before { display: none; }

        .hero-cta {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            transition: all 0.2s ease;
        }

        .hero-cta:hover {
            transform: translateY(-2px);
        }

        .shield-graphic {
            position: relative;
            width: 220px;
            height: 260px;
            margin: 0 auto;
            border-radius: 30% 30% 38% 38% / 26% 26% 42% 42%;
            background: linear-gradient(180deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04));
            border: 1px solid rgba(255,255,255,0.14);
            box-shadow: 0 20px 42px rgba(15, 23, 42, 0.2);
        }

        .shield-graphic::before {
            content: "";
            position: absolute;
            inset: 24px;
            border-radius: inherit;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(249,115,22,0.4);
        }

        .shield-qr {
            position: absolute;
            inset: 0;
            margin: auto;
            width: 130px;
            height: 130px;
            background: rgba(255,255,255,0.94);
            border-radius: 18px;
            overflow: hidden;
            box-shadow: inset 0 0 0 10px rgba(17,24,39,0.04);
        }

        .shield-qr::before,
        .shield-qr::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(15,23,42,0.96) 0 15%, transparent 15% 22%, rgba(15,23,42,0.96) 22% 38%, transparent 38% 44%, rgba(15,23,42,0.96) 44% 60%, transparent 60% 67%, rgba(15,23,42,0.96) 67% 84%, transparent 84% 90%, rgba(15,23,42,0.96) 90% 100%),
                linear-gradient(rgba(15,23,42,0.96) 0 15%, transparent 15% 22%, rgba(15,23,42,0.96) 22% 38%, transparent 38% 44%, rgba(15,23,42,0.96) 44% 60%, transparent 60% 67%, rgba(15,23,42,0.96) 67% 84%, transparent 84% 90%, rgba(15,23,42,0.96) 90% 100%);
            background-size: 100% 100%;
            opacity: 0.9;
            mask-image: radial-gradient(circle at center, black 52%, transparent 100%);
            -webkit-mask-image: radial-gradient(circle at center, black 52%, transparent 100%);
        }

        .shield-graphic i {
            position: absolute;
            inset: 0;
            margin: auto;
            width: 60px;
            height: 60px;
            display: grid;
            place-items: center;
            color: rgba(220,38,38,0.8);
            font-size: 2rem;
            z-index: 1;
        }

        .stats-wrap {
            background: linear-gradient(135deg, #111827 0%, #1f2937 40%, #7b0d18 100%);
            border-radius: 2rem;
            box-shadow: 0 18px 45px rgba(15,23,42,0.16);
        }

        .stats-number {
            font-size: clamp(2.2rem, 4vw, 3.4rem);
            font-weight: 800;
            letter-spacing: -0.08em;
            color: #fff;
        }

        .stats-label {
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #fecaca;
        }

        .stats-sub {
            display: block;
            margin-top: 0.25rem;
            color: rgba(255,255,255,0.72);
            font-size: 0.9rem;
        }

        .campus-card {
            background: linear-gradient(135deg, rgba(255,255,255,0.96), rgba(248,250,252,0.88));
            border: 1px solid rgba(148,163,184,0.18);
        }

        .campus-mark {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 88px;
            height: 88px;
            border-radius: 24px;
            background: linear-gradient(135deg, #7b0d18 0%, #d11f2a 100%);
            color: #fff;
            font-weight: 900;
            font-size: 1.5rem;
            box-shadow: 0 18px 30px rgba(127,29,29,0.32);
        }

        .campus-shape {
            position: relative;
            height: 220px;
            margin-top: 1.2rem;
            display: flex;
            align-items: end;
            justify-content: center;
            gap: 1rem;
        }

        .campus-shape .block {
            position: relative;
            background: linear-gradient(180deg, #f8fafc, #e2e8f0);
            border-radius: 22px 22px 0 0;
            border: 1px solid rgba(148,163,184,0.2);
            box-shadow: 0 16px 30px rgba(15,23,42,0.06);
        }

        .campus-shape .block.main {
            width: 180px;
            height: 150px;
        }

        .campus-shape .block.side {
            width: 110px;
            height: 110px;
        }

        .campus-shape .block.tall {
            width: 74px;
            height: 90px;
            background: linear-gradient(180deg, #fff1f2, #fdf2f8);
        }

        .campus-shape .block::before {
            content: "";
            position: absolute;
            left: 18px;
            right: 18px;
            top: 18px;
            height: 12px;
            border-radius: 9999px;
            background: linear-gradient(90deg, #d11f2a, #fca5a5);
        }

        .campus-shape .block::after {
            content: "";
            position: absolute;
            left: 18px;
            right: 18px;
            bottom: 16px;
            top: 44px;
            border-radius: 12px;
            background: repeating-linear-gradient(90deg, rgba(148,163,184,0.35) 0, rgba(148,163,184,0.35) 9%, transparent 9%, transparent 17%);
        }

        @media (max-width: 768px) {
            .process-step::before { display: none; }
            .qr-screen { min-height: 440px; }
            .qr-phone {
                max-width: 100%;
                width: min(100%, 320px);
            }
            .floating-card {
                position: static;
                transform: none;
                width: 100%;
                margin-bottom: 0.75rem;
            }
            .floating-card:nth-child(1),
            .floating-card:nth-child(2) {
                right: auto;
                left: auto;
                top: auto;
                bottom: auto;
            }
            .hero-shell::before,
            .hero-shell::after {
                display: none;
            }
        }
    </style>
</head>
<body>

    <header class="sticky top-0 z-50 border-b border-slate-200 bg-white/85 backdrop-blur-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between py-4">
                <div class="flex items-center gap-3">
                    <img src="<?php echo $imageSource4 ?>" alt="USeP Logo" class="h-10 w-auto">
                    <div>
                        <p class="text-lg font-bold text-slate-900">QR Attendance</p>
                        <p class="text-[10px] uppercase tracking-[0.18em] text-slate-500">USeP Tagum-Mabini Campus</p>
                    </div>
                </div>

                <nav id="site-nav" class="hidden md:flex items-center gap-7 text-sm font-medium text-slate-600">
                    <a href="#home" class="hover:text-red-600 transition-colors">Home</a>
                    <a href="#features" class="hover:text-red-600 transition-colors">Features</a>
                    <a href="#how-it-works" class="hover:text-red-600 transition-colors">How It Works</a>
                    <a href="#about" class="hover:text-red-600 transition-colors">About</a>
                    <a href="#contact" class="hover:text-red-600 transition-colors">Contact</a>
                    <a href="#register" class="rounded-full bg-slate-900 px-4 py-2.5 text-white hover:bg-slate-700 transition-colors">Get Started</a>
                    <a href="<?php echo ROOT ?>login" class="rounded-full border border-slate-300 px-4 py-2.5 text-slate-700 hover:border-red-200 hover:text-red-600 transition-colors">Login</a>
                </nav>

                <button id="menu-toggle" class="md:hidden flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-700">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div id="mobile-menu" class="hidden pb-4 md:hidden">
                <div class="flex flex-col gap-2 pt-2 text-sm font-medium text-slate-700">
                    <a href="#home" class="rounded-lg px-3 py-2 hover:bg-slate-100">Home</a>
                    <a href="#features" class="rounded-lg px-3 py-2 hover:bg-slate-100">Features</a>
                    <a href="#how-it-works" class="rounded-lg px-3 py-2 hover:bg-slate-100">How It Works</a>
                    <a href="#about" class="rounded-lg px-3 py-2 hover:bg-slate-100">About</a>
                    <a href="#contact" class="rounded-lg px-3 py-2 hover:bg-slate-100">Contact</a>
                    <div class="mt-2 flex gap-2">
                        <a href="#register" class="flex-1 rounded-full bg-slate-900 px-4 py-2.5 text-center text-white">Register</a>
                        <a href="<?php echo ROOT ?>login" class="flex-1 rounded-full border border-slate-300 px-4 py-2.5 text-center text-slate-700">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main id="home">
        <section class="hero-shell text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-22">
                <div class="grid items-center gap-12 lg:grid-cols-[1.1fr_0.9fr]">
                    <div data-aos="fade-up" data-aos-duration="900">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white/90">
                            <i class="fas fa-shield-alt"></i>
                            Fast. Secure. Smarter Attendance.
                        </span>
                        <h1 class="mt-7 text-4xl font-black leading-[1.02] sm:text-5xl lg:text-7xl">
                            Attendance, Simplified.
                        </h1>
                        <p class="mt-6 max-w-xl text-lg leading-8 text-slate-200">
                            A smarter QR-based attendance platform designed to make student attendance faster, more accurate, and easier to manage.
                        </p>

                        <div class="mt-8 flex flex-col gap-4 sm:flex-row">
                            <a href="<?php echo ROOT ?>login" class="hero-cta rounded-full border border-white/40 bg-white/5 px-7 py-4 text-white hover:border-white/60 hover:bg-white/10">Get Startted</a>
                        </div>

                        <div class="mt-10 flex flex-wrap gap-3 text-sm text-slate-100/90">
                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-2"><i class="fas fa-check-circle mr-2 text-emerald-300"></i>QR-based verification</span>
                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-2"><i class="fas fa-check-circle mr-2 text-emerald-300"></i>Event-ready tracking</span>
                            <span class="rounded-full border border-white/10 bg-white/5 px-3 py-2"><i class="fas fa-check-circle mr-2 text-emerald-300"></i>Live attendance monitoring</span>
                        </div>
                    </div>

                    <div class="relative" data-aos="zoom-in" data-aos-delay="200">
                        <div class="qr-phone">
                            <div class="qr-screen">
                                <div class="scan-ui"></div>
                                <div class="scanner-line"></div>

                                <div class="floating-card text-[11px] font-semibold text-slate-600">
                                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Attendance Recorded ✓</div>
                                </div>
                                <div class="floating-card text-[11px] font-semibold text-slate-600">
                                    <div class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-red-500"></span> Checking in...</div>
                                </div>

                                <div class="pt-8 text-center">
                                    <div class="qr-code-box"></div>
                                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Student QR</p>
                                    <h3 class="mt-4 text-2xl font-bold text-slate-900">Diana S.</h3>
                                    <p class="mt-1 text-sm text-slate-500">BSIT - 3rd Year</p>
                                </div>

                                <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-100/90 p-3">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-slate-500">Status</p>
                                            <p class="mt-1 text-sm font-bold text-slate-900">Checked In</p>
                                        </div>
                                        <span class="status-pill"><i class="fas fa-check"></i> Verified</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="bg-[#f8fafc] py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center" data-aos="fade-up" data-aos-duration="800">
                    <span class="section-tag">Features</span>
                    <h2 class="mt-4 text-3xl font-black text-slate-900 md:text-5xl">Everything You Need for Smarter Attendance</h2>
                    <p class="mt-4 text-lg text-slate-600">
                        Built to support university events, activities, and organizations with a faster, more accurate attendance workflow.
                    </p>
                </div>

                <div class="mt-14 grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                    <div class="feature-card card-surface rounded-[1.8rem] p-8" data-aos="fade-up" data-aos-delay="100">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-red-100 text-xl text-red-600"><i class="fas fa-qrcode"></i></div>
                        <h3 class="text-xl font-bold text-slate-900">QR Code Scanning</h3>
                        <p class="mt-3 text-slate-600">Fast and convenient attendance recording using student QR codes.</p>
                    </div>

                    <div class="feature-card card-surface rounded-[1.8rem] p-8" data-aos="fade-up" data-aos-delay="200">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-100 text-xl text-blue-600"><i class="fas fa-chart-line"></i></div>
                        <h3 class="text-xl font-bold text-slate-900">Real-Time Monitoring</h3>
                        <p class="mt-3 text-slate-600">Monitor attendance records as they are being recorded.</p>
                    </div>

                    <div class="feature-card card-surface rounded-[1.8rem] p-8" data-aos="fade-up" data-aos-delay="300">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-xl text-amber-600"><i class="fas fa-location-dot"></i></div>
                        <h3 class="text-xl font-bold text-slate-900">GPS Geofencing</h3>
                        <p class="mt-3 text-slate-600">Help ensure attendance is recorded within the designated event location.</p>
                    </div>

                    <div class="feature-card card-surface rounded-[1.8rem] p-8" data-aos="fade-up" data-aos-delay="400">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-100 text-xl text-rose-600"><i class="fas fa-scale-balanced"></i></div>
                        <h3 class="text-xl font-bold text-slate-900">Automated Sanctions</h3>
                        <p class="mt-3 text-slate-600">Automatically identify attendance violations and generate corresponding sanctions.</p>
                    </div>

                    <div class="feature-card card-surface rounded-[1.8rem] p-8" data-aos="fade-up" data-aos-delay="600">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-100 text-xl text-emerald-600"><i class="fas fa-clipboard-list"></i></div>
                        <h3 class="text-xl font-bold text-slate-900">Attendance Reports</h3>
                        <p class="mt-3 text-slate-600">Generate organized attendance records and reports for administrators.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="how-it-works" class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center" data-aos="fade-up" data-aos-duration="800">
                    <span class="section-tag">How It Works</span>
                    <h2 class="mt-4 text-3xl font-black text-slate-900 md:text-5xl">Effortless Process, Accurate Results</h2>
                </div>

                <div class="mt-14 grid gap-8 lg:grid-cols-3">
                    <div class="process-step card-surface rounded-[1.8rem] p-8 text-center" data-aos="fade-up" data-aos-delay="100">
                        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-red-100 text-3xl text-red-600">
                            <span class="font-black">01</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900">Register</h3>
                        <p class="mt-3 text-slate-600">Students register their information and receive their QR code.</p>
                    </div>

                    <div class="process-step card-surface rounded-[1.8rem] p-8 text-center" data-aos="fade-up" data-aos-delay="200">
                        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-blue-100 text-3xl text-blue-600">
                            <span class="font-black">02</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900">Scan</h3>
                        <p class="mt-3 text-slate-600">Students present their QR code to the assigned facilitator.</p>
                    </div>

                    <div class="process-step card-surface rounded-[1.8rem] p-8 text-center" data-aos="fade-up" data-aos-delay="300">
                        <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-2xl bg-emerald-100 text-3xl text-emerald-600">
                            <span class="font-black">03</span>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900">Record</h3>
                        <p class="mt-3 text-slate-600">Attendance is instantly recorded and stored in the system.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-slate-50 py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-[0.9fr_1.1fr]">
                    <div data-aos="fade-right" data-aos-delay="100">
                        <div class="card-surface rounded-[2rem] p-6 md:p-8">
                            <div class="mb-6 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Student dashboard</p>
                                    <h3 class="mt-2 text-2xl font-black text-slate-900">Overview</h3>
                                </div>
                                <span class="rounded-full bg-red-50 px-3 py-1 text-xs font-bold text-red-600">Active</span>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Events</p>
                                    <p class="mt-2 text-3xl font-black text-slate-900">08</p>
                                </div>
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-500">Attendance</p>
                                    <p class="mt-2 text-3xl font-black text-slate-900">96%</p>
                                </div>
                            </div>

                            <div class="mt-6 space-y-3">
                                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-100 text-red-600"><i class="fas fa-qrcode"></i></div>
                                        <div>
                                            <p class="font-bold text-slate-900">Quick attendance scan</p>
                                            <p class="text-sm text-slate-500">Event check-in</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold text-emerald-600">Ready</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-blue-600"><i class="fas fa-clock"></i></div>
                                        <div>
                                            <p class="font-bold text-slate-900">Attendance history</p>
                                            <p class="text-sm text-slate-500">Last 30 days</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-700">14 logs</span>
                                </div>
                                <div class="flex items-center justify-between rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600"><i class="fas fa-file-alt"></i></div>
                                        <div>
                                            <p class="font-bold text-slate-900">Excuse request</p>
                                            <p class="text-sm text-slate-500">Submitted</p>
                                        </div>
                                    </div>
                                    <span class="text-sm font-bold text-emerald-600">Approved</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div data-aos="fade-left" data-aos-delay="200">
                        <span class="section-tag">Student Experience</span>
                        <h2 class="mt-4 text-3xl font-black text-slate-900 md:text-5xl">A smarter way for students to stay on track.</h2>
                        <div class="mt-8 space-y-6">
                            <div class="flex gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600"><i class="fas fa-bolt"></i></div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900">Quick attendance scanning</h3>
                                    <p class="mt-1 text-lg text-slate-600">Students can check in quickly and avoid long queues during events and activities.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600"><i class="fas fa-calendar-check"></i></div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900">Attendance history</h3>
                                    <p class="mt-1 text-lg text-slate-600">Students can review past attendance records and keep track of their participation.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600"><i class="fas fa-shield-alt"></i></div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900">Secure student records</h3>
                                    <p class="mt-1 text-lg text-slate-600">The system protects attendance records while maintaining accurate student tracking.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid items-center gap-12 lg:grid-cols-[1.05fr_0.95fr]">
                    <div data-aos="fade-right" data-aos-delay="100">
                        <span class="section-tag">Facilitator Experience</span>
                        <h2 class="mt-4 text-3xl font-black text-slate-900 md:text-5xl">Built to support facilitators with clarity and control.</h2>
                        <div class="mt-8 space-y-6">
                            <div class="flex gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-red-100 text-red-600"><i class="fas fa-user-check"></i></div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900">Dedicated facilitator accounts</h3>
                                    <p class="mt-1 text-lg text-slate-600">Facilitators can securely access the right event and attendance tools for their assigned responsibilities.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600"><i class="fas fa-camera"></i></div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900">Real-time attendance monitoring</h3>
                                    <p class="mt-1 text-lg text-slate-600">Attendance can be validated instantly while the event is ongoing and updated continuously.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600"><i class="fas fa-user-friends"></i></div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-900">Student verification</h3>
                                    <p class="mt-1 text-lg text-slate-600">Facilitators can verify student identity and attendance more effectively through system checks.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div data-aos="fade-left" data-aos-delay="200">
                        <div class="card-surface rounded-[2rem] p-6 md:p-8">
                            <div class="mb-6 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Scanner</p>
                                    <h3 class="mt-2 text-2xl font-black text-slate-900">Facilitator Panel</h3>
                                </div>
                                <span class="rounded-full bg-emerald-500 px-3 py-1 text-xs font-bold text-white">Live</span>
                            </div>

                            <div class="grid gap-5 md:grid-cols-[0.8fr_1.2fr]">
                                <div class="rounded-[1.6rem] border border-slate-200 bg-slate-100 p-4">
                                    <div class="mx-auto h-[160px] w-[160px] rounded-[1.5rem] border-[10px] border-white bg-white shadow-inner">
                                        <div class="relative h-full w-full overflow-hidden rounded-[0.8rem] bg-white">
                                            <div class="absolute inset-0 bg-[radial-gradient(circle,_rgba(17,24,39,0.1),transparent_65%)]"></div>
                                            <div class="absolute inset-0" style="background: linear-gradient(90deg, rgba(15,23,42,0.95) 0 14%, transparent 14% 22%, rgba(15,23,42,0.95) 22% 36%, transparent 36% 44%, rgba(15,23,42,0.95) 44% 58%, transparent 58% 66%, rgba(15,23,42,0.95) 66% 82%, transparent 82% 90%, rgba(15,23,42,0.95) 90% 100%), linear-gradient(rgba(15,23,42,0.95) 0 14%, transparent 14% 22%, rgba(15,23,42,0.95) 22% 36%, transparent 36% 44%, rgba(15,23,42,0.95) 44% 58%, transparent 58% 66%, rgba(15,23,42,0.95) 66% 82%, transparent 82% 90%, rgba(15,23,42,0.95) 90% 100%); background-size: 100% 100%; opacity: 0.9; mask-image: radial-gradient(circle at center, black 52%, transparent 100%); -webkit-mask-image: radial-gradient(circle at center, black 52%, transparent 100%);"></div>
                                            <div class="absolute left-0 h-[3px] w-full bg-gradient-to-r from-transparent via-red-500 to-transparent shadow-[0_0_16px_rgba(220,38,38,0.8)] animate-[scan_2.2s_ease-in-out_infinite]" style="top: 30%;"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Current Event</p>
                                        <p class="mt-2 text-lg font-bold text-slate-900">Student Orientation</p>
                                    </div>
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-500">Scanned</p>
                                        <p class="mt-2 text-3xl font-black text-slate-900">184</p>
                                    </div>
                                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-700">
                                        Attendance Recorded ✓
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-slate-900 py-20 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center" data-aos="fade-up" data-aos-duration="800">
                    <span class="section-tag" style="color:#fca5a5;">Security</span>
                    <h2 class="mt-4 text-3xl font-black md:text-5xl">Built With Security in Mind.</h2>
                </div>

                <div class="mt-14 grid items-center gap-12 lg:grid-cols-2">
                    <div data-aos="fade-right" data-aos-delay="100">
                        <div class="rounded-[2rem] border border-white/10 bg-[radial-gradient(circle_at_center,_rgba(220,38,38,0.18),_rgba(15,23,42,0.8)_58%,_rgba(15,23,42,0.92)_100%)] p-10">
                            <div class="shield-graphic">
                                <div class="shield-qr"></div>
                                <i class="fas fa-shield-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div data-aos="fade-left" data-aos-delay="200">
                        <p class="text-lg leading-8 text-slate-300">
                            The system is designed to help protect attendance records through secure authentication, role-based access, activity logs, controlled facilitator access, and database-backed attendance records.
                        </p>
                        <div class="mt-8 grid gap-4 sm:grid-cols-2">
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <i class="mb-3 block text-xl text-red-300 fas fa-user-lock"></i>
                                <h3 class="font-bold">Secure Authentication</h3>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <i class="mb-3 block text-xl text-red-300 fas fa-key"></i>
                                <h3 class="font-bold">Role-Based Access</h3>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <i class="mb-3 block text-xl text-red-300 fas fa-lock"></i>
                                <h3 class="font-bold">Activity Logs</h3>
                            </div>
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <i class="mb-3 block text-xl text-red-300 fas fa-database"></i>
                                <h3 class="font-bold">Database Records</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-[#f8fafc] py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="stats-wrap p-8 md:p-12" data-aos="fade-up" data-aos-duration="900">
                    <div class="grid gap-8 text-center md:grid-cols-4">
                        <div>
                            <div class="stats-number stat-number" data-target="2">0</div>
                            <div class="stats-label mt-3">+ Years</div>
                            <span class="stats-sub">of system utilization</span>
                        </div>
                        <div>
                            <div class="stats-number stat-number" data-target="24">0</div>
                            <div class="stats-label mt-3">Real-Time</div>
                            <span class="stats-sub">attendance recording</span>
                        </div>
                        <div>
                            <div class="stats-number stat-number" data-target="100">0</div>
                            <div class="stats-label mt-3">QR-Based</div>
                            <span class="stats-sub">student verification</span>
                        </div>
                        <div>
                            <div class="stats-number stat-number" data-target="99">0</div>
                            <div class="stats-label mt-3">Automated</div>
                            <span class="stats-sub">attendance management</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="bg-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-3xl text-center" data-aos="fade-up" data-aos-duration="800">
                    <span class="section-tag">University Focus</span>
                    <h2 class="mt-4 text-3xl font-black text-slate-900 md:text-5xl">Designed for the USeP Tagum-Mabini Campus</h2>
                </div>

                <div class="mt-14 grid items-center gap-12 lg:grid-cols-2">
                    <div class="campus-card rounded-[2rem] p-8" data-aos="fade-right" data-aos-delay="100">
                        <div class="campus-mark">USeP</div>
                        <div class="campus-shape">
                            <div class="block main"></div>
                            <div class="block side"></div>
                            <div class="block tall"></div>
                        </div>
                    </div>

                    <div data-aos="fade-left" data-aos-delay="200">
                        <p class="text-xl leading-8 text-slate-700">
                            The platform was developed specifically to support attendance management during university events and activities, helping administrators, facilitators, and students work with a smoother and more reliable system.
                        </p>
                        <div class="mt-8 space-y-4 text-slate-600">
                            <div class="flex items-center gap-3"><i class="fas fa-check-circle text-red-500"></i><span>Event-ready attendance tracking</span></div>
                            <div class="flex items-center gap-3"><i class="fas fa-check-circle text-red-500"></i><span>Designed for university participation</span></div>
                            <div class="flex items-center gap-3"><i class="fas fa-check-circle text-red-500"></i><span>Supports student accountability and monitoring</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer id="contact" class="bg-slate-950 py-16 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 md:grid-cols-5">
                <div class="md:col-span-2">
                    <div class="mb-4 flex items-center gap-3">
                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-red-600 text-lg font-black">QR</div>
                        <div>
                            <h3 class="text-xl font-bold">QR Code Attendance System</h3>
                        </div>
                    </div>
                    <p class="max-w-md text-slate-400">Smarter attendance management for a better university experience.</p>
                </div>

                <div>
                    <h4 class="mb-4 font-bold">Navigation</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="#home" class="hover:text-white transition-colors">Home</a></li>
                        <li><a href="#features" class="hover:text-white transition-colors">Features</a></li>
                        <li><a href="#how-it-works" class="hover:text-white transition-colors">How It Works</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="mb-4 font-bold">Resources</h4>
                    <ul class="space-y-2 text-slate-400">
                        <li><a href="#" class="hover:text-white transition-colors">Privacy</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Terms</a></li>
                        <li><a href="#contact" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="mb-4 font-bold">Campus</h4>
                    <p class="text-slate-400">Developed for USeP Tagum-Mabini Campus</p>
                </div>
            </div>

            <div class="mt-10 border-t border-slate-800 pt-6 text-center text-slate-400">
                <p>© <?php echo date('Y'); ?> QR Code Attendance System</p>
            </div>
        </div>
    </footer>

    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            offset: 80
        });

        const statNumbers = document.querySelectorAll('.stat-number');
        statNumbers.forEach((element) => {
            const target = Number(element.dataset.target);
            const suffix = target >= 100 ? '%' : '+';
            let current = 0;
            const step = target / 90;

            const tick = () => {
                current += step;
                if (current < target) {
                    element.textContent = Math.floor(current) + suffix;
                    requestAnimationFrame(tick);
                } else {
                    element.textContent = target + suffix;
                }
            };

            tick();
        });

        const toggleButton = document.getElementById('menu-toggle');
        const mobileMenu = document.getElementById('mobile-menu');

        toggleButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });

        document.addEventListener('contextmenu', function (event) {
            event.preventDefault();
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'F12' ||
                (event.ctrlKey && event.shiftKey && event.key.toLowerCase() === 'i') ||
                (event.ctrlKey && event.key.toLowerCase() === 'u')) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>
