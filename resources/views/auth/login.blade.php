<x-guest-layout>
    <style>
        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            font-family: 'Inter', 'Segoe UI', Arial, sans-serif;
            background: #f5f7fb;
            color: #0f172a;
        }

        .auth-login-page {
            width: 100%;
            height: 100vh;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 16px;
            overflow: hidden;
            background:
                radial-gradient(circle at 18% 20%, rgba(37, 99, 235, 0.08), transparent 24%),
                radial-gradient(circle at 82% 78%, rgba(59, 130, 246, 0.08), transparent 24%),
                #f5f7fb;
        }

        .auth-login-shell {
            width: 100%;
            max-width: 1080px;
            height: 600px;
            max-height: calc(100vh - 20px);
            display: grid;
            grid-template-columns: 1.18fr 0.95fr;
            background: #ffffff;
            border: 1px solid #e9eef7;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(15, 23, 42, 0.10);
        }

        /* LEFT SIDE */

        .auth-left {
            position: relative;
            overflow: hidden;
            padding: 28px 38px;
            color: #ffffff;
            background:
                radial-gradient(circle at top right, rgba(103, 169, 255, 0.24), transparent 22%),
                linear-gradient(180deg, #042f93 0%, #032372 38%, #02184f 100%);
        }

        .auth-left::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at left bottom, rgba(59, 130, 246, 0.20), transparent 18%);
            pointer-events: none;
        }

        .auth-left-grid {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .auth-top-badge {
            display: inline-flex;
            align-items: center;
            width: fit-content;
            gap: 8px;
            padding: 7px 13px;
            border-radius: 999px;
            background: rgba(82, 133, 255, 0.20);
            border: 1px solid rgba(255, 255, 255, 0.08);
            color: #ffffff;
            font-size: 12px;
            font-weight: 800;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.08);
        }

        .auth-top-badge .badge-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.18);
            font-size: 11px;
        }

        .auth-hero-copy {
            margin-top: 22px;
            max-width: 455px;
        }

        .auth-hero-copy h1 {
            margin: 0 0 14px;
            color: #ffffff;
            font-size: 46px;
            line-height: 1.02;
            font-weight: 900;
            letter-spacing: -0.045em;
        }

        .auth-hero-copy h1 span {
            display: block;
            color: #5c91ff;
        }

        .auth-hero-copy .lead {
            margin: 0 0 10px;
            max-width: 410px;
            color: rgba(255, 255, 255, 0.92);
            font-size: 15px;
            line-height: 1.6;
            font-weight: 600;
        }

        .auth-hero-copy .sub {
            margin: 0;
            max-width: 390px;
            color: rgba(193, 214, 255, 0.90);
            font-size: 13px;
            line-height: 1.55;
            font-weight: 500;
        }

        .auth-scene {
            position: relative;
            flex: 1;
            min-height: 0;
            margin-top: 0;
            transform: scale(0.78);
            transform-origin: left bottom;
        }

        .auth-route-line {
            position: absolute;
            right: 78px;
            top: 6px;
            width: 165px;
            height: 128px;
            border-top: 2px dashed rgba(74, 144, 255, 0.75);
            border-right: 2px dashed rgba(74, 144, 255, 0.75);
            border-radius: 0 120px 0 0;
            opacity: 0.8;
        }

        .auth-route-pin {
            position: absolute;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 15px;
            background: linear-gradient(180deg, #80b0ff, #3f78ff);
            box-shadow: 0 10px 20px rgba(38, 104, 255, 0.35);
        }

        .auth-route-pin.top {
            top: 0;
            right: 46px;
        }

        .auth-route-pin.bottom {
            left: 10px;
            bottom: 66px;
            width: 26px;
            height: 26px;
            font-size: 12px;
        }

        .auth-dashboard-panel {
            position: absolute;
            right: 78px;
            top: 132px;
            width: 190px;
            height: 120px;
            padding: 12px;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(21, 54, 143, 0.72), rgba(13, 39, 111, 0.84));
            border: 1px solid rgba(108, 155, 255, 0.28);
            box-shadow: 0 25px 40px rgba(1, 10, 38, 0.35);
            transform: rotate(-8deg);
            backdrop-filter: blur(10px);
        }

        .dashboard-mini-label {
            color: rgba(210, 226, 255, 0.88);
            font-size: 10px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .dashboard-mini-chart {
            height: 40px;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(180deg, rgba(38, 106, 255, 0.12), rgba(38, 106, 255, 0)),
                radial-gradient(circle at 10% 70%, rgba(58, 125, 255, 0.75) 0, rgba(58, 125, 255, 0.75) 2px, transparent 3px),
                radial-gradient(circle at 28% 56%, rgba(58, 125, 255, 0.75) 0, rgba(58, 125, 255, 0.75) 2px, transparent 3px),
                radial-gradient(circle at 45% 50%, rgba(58, 125, 255, 0.75) 0, rgba(58, 125, 255, 0.75) 2px, transparent 3px),
                radial-gradient(circle at 65% 30%, rgba(58, 125, 255, 0.75) 0, rgba(58, 125, 255, 0.75) 2px, transparent 3px),
                radial-gradient(circle at 82% 18%, rgba(58, 125, 255, 0.75) 0, rgba(58, 125, 255, 0.75) 2px, transparent 3px);
        }

        .dashboard-mini-chart::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(120deg,
                    transparent 8%,
                    transparent 12%,
                    rgba(73, 145, 255, 0.9) 13%,
                    rgba(73, 145, 255, 0.9) 14.2%,
                    transparent 15%,
                    transparent 25%,
                    rgba(73, 145, 255, 0.9) 26%,
                    rgba(73, 145, 255, 0.9) 27.2%,
                    transparent 28%,
                    transparent 38%,
                    rgba(73, 145, 255, 0.9) 39%,
                    rgba(73, 145, 255, 0.9) 40.2%,
                    transparent 41%,
                    transparent 54%,
                    rgba(73, 145, 255, 0.9) 55%,
                    rgba(73, 145, 255, 0.9) 56.2%,
                    transparent 57%,
                    transparent 70%,
                    rgba(73, 145, 255, 0.9) 71%,
                    rgba(73, 145, 255, 0.9) 72.2%,
                    transparent 73%);
            opacity: 0.8;
        }

        .dashboard-mini-bottom {
            margin-top: 10px;
            display: grid;
            grid-template-columns: 1fr 50px;
            gap: 10px;
            align-items: end;
        }

        .dashboard-mini-bars {
            height: 38px;
            display: flex;
            align-items: end;
            gap: 6px;
        }

        .dashboard-mini-bars span {
            display: block;
            flex: 1;
            border-radius: 8px 8px 4px 4px;
            background: linear-gradient(180deg, rgba(66, 133, 255, 0.95), rgba(26, 86, 220, 0.95));
        }

        .dashboard-mini-bars span:nth-child(1) { height: 16px; }
        .dashboard-mini-bars span:nth-child(2) { height: 28px; }
        .dashboard-mini-bars span:nth-child(3) { height: 22px; }
        .dashboard-mini-bars span:nth-child(4) { height: 34px; }

        .dashboard-mini-ring {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            position: relative;
            margin-left: auto;
            background: conic-gradient(#4e8cff 0 230deg, rgba(255, 255, 255, 0.12) 230deg 360deg);
        }

        .dashboard-mini-ring::after {
            content: "";
            position: absolute;
            inset: 9px;
            border-radius: 50%;
            background: #0d2b76;
        }

        .warehouse-base {
            position: absolute;
            left: 160px;
            bottom: 42px;
            width: 220px;
            height: 108px;
            border-radius: 12px 12px 16px 16px;
            background: linear-gradient(180deg, #244fc3, #0d2c85);
            box-shadow: 0 26px 40px rgba(0, 6, 28, 0.38);
        }

        .warehouse-roof {
            position: absolute;
            left: 146px;
            bottom: 134px;
            width: 248px;
            height: 42px;
            background: linear-gradient(180deg, #315fd8, #1a42af);
            clip-path: polygon(12% 100%, 88% 100%, 70% 10%, 30% 10%);
            filter: drop-shadow(0 10px 18px rgba(0, 0, 0, 0.18));
        }

        .warehouse-door {
            position: absolute;
            left: 248px;
            bottom: 42px;
            width: 52px;
            height: 72px;
            border-radius: 6px;
            background: linear-gradient(180deg, #0a1847, #06112d);
            box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.04);
        }

        .warehouse-window {
            position: absolute;
            width: 26px;
            height: 18px;
            border-radius: 5px;
            background: linear-gradient(180deg, #90b5ff, #4478ff);
            opacity: 0.9;
        }

        .warehouse-window.w1 {
            left: 178px;
            bottom: 98px;
        }

        .warehouse-window.w2 {
            left: 320px;
            bottom: 96px;
        }

        .ground-line {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 30px;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(78, 140, 255, 0.86), transparent);
            box-shadow:
                0 0 18px rgba(78, 140, 255, 0.85),
                0 0 34px rgba(78, 140, 255, 0.22);
        }

        .ground-grid {
            position: absolute;
            left: -20px;
            right: -20px;
            bottom: 2px;
            height: 128px;
            opacity: 0.7;
            background:
                linear-gradient(180deg, rgba(35, 102, 255, 0.20), transparent 65%),
                repeating-linear-gradient(90deg, rgba(70, 132, 255, 0.18) 0, rgba(70, 132, 255, 0.18) 1px, transparent 1px, transparent 52px),
                repeating-linear-gradient(180deg, rgba(70, 132, 255, 0.14) 0, rgba(70, 132, 255, 0.14) 1px, transparent 1px, transparent 28px);
            transform: perspective(220px) rotateX(70deg);
            transform-origin: center bottom;
        }

        .truck {
            position: absolute;
            left: 106px;
            bottom: 38px;
            width: 120px;
            height: 52px;
            border-radius: 12px 10px 10px 12px;
            background: linear-gradient(180deg, #ffffff, #dce8ff);
            box-shadow: 0 20px 26px rgba(0, 0, 0, 0.22);
        }

        .truck::before {
            content: "";
            position: absolute;
            right: -20px;
            top: 10px;
            width: 30px;
            height: 34px;
            border-radius: 6px 10px 8px 6px;
            background: linear-gradient(180deg, #f4f8ff, #dbe7ff);
        }

        .truck::after {
            content: "";
            position: absolute;
            right: -10px;
            top: 16px;
            width: 12px;
            height: 12px;
            border-radius: 4px;
            background: #88b3ff;
        }

        .truck-cabin-line {
            position: absolute;
            left: 16px;
            top: 14px;
            width: 24px;
            height: 16px;
            border-radius: 4px;
            border: 2px solid #3778ff;
        }

        .truck-box-line {
            position: absolute;
            right: 22px;
            top: 12px;
            width: 50px;
            height: 24px;
            border-radius: 6px;
            border: 2px solid #3778ff;
        }

        .truck-wheel,
        .truck-wheel::after {
            content: "";
            position: absolute;
            bottom: -10px;
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #101828;
            box-shadow: inset 0 0 0 6px #334155;
        }

        .truck-wheel.left {
            left: 16px;
        }

        .truck-wheel.right {
            right: 16px;
        }

        .truck-glow {
            position: absolute;
            left: 84px;
            bottom: 32px;
            width: 182px;
            height: 22px;
            background: radial-gradient(circle, rgba(61, 133, 255, 0.78), transparent 68%);
            filter: blur(10px);
        }

        .box {
            position: absolute;
            width: 30px;
            height: 28px;
            border-radius: 4px;
            background: linear-gradient(180deg, #e8bc79, #b97b36);
            box-shadow: 0 16px 22px rgba(0, 0, 0, 0.18);
        }

        .box::before {
            content: "";
            position: absolute;
            left: 4px;
            right: 4px;
            top: 8px;
            height: 2px;
            background: rgba(90, 56, 17, 0.45);
        }

        .box.b1 {
            left: 350px;
            bottom: 52px;
        }

        .box.b2 {
            left: 374px;
            bottom: 64px;
            width: 24px;
            height: 24px;
        }

        .box.b3 {
            left: 398px;
            bottom: 48px;
            width: 32px;
            height: 31px;
        }

        /* RIGHT SIDE */

        .auth-right {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 16px 22px;
            background: linear-gradient(180deg, #f8fbff 0%, #f6f8fc 100%);
        }

        .auth-right::before {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 160px;
            height: 100px;
            background-image: radial-gradient(#d6e5ff 1.2px, transparent 1.2px);
            background-size: 9px 9px;
            opacity: 0.75;
        }

        .auth-form-card {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 390px;
            padding: 24px 24px 22px;
            border-radius: 26px;
            background: #ffffff;
            border: 1px solid #eef2f8;
            box-shadow: 0 18px 50px rgba(15, 23, 42, 0.08);
        }

        .auth-form-icon {
            width: 42px;
            height: 42px;
            margin: 0 auto 12px;
            border-radius: 14px;
            background: linear-gradient(180deg, #f3f7ff, #eaf1ff);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2b62f4;
            font-size: 20px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        .auth-form-head {
            text-align: center;
            margin-bottom: 18px;
        }

        .auth-form-head h2 {
            margin: 0 0 8px;
            color: #182341;
            font-size: 32px;
            line-height: 1.05;
            font-weight: 900;
            letter-spacing: -0.04em;
        }

        .auth-form-head p {
            margin: 0 auto;
            max-width: 285px;
            color: #8b98b5;
            font-size: 13px;
            line-height: 1.5;
            font-weight: 500;
        }

        .login-status {
            margin-bottom: 14px;
            padding: 12px 14px;
            border-radius: 14px;
            background: #ecfdf5;
            border: 1px solid #bbf7d0;
            color: #166534;
            font-size: 13px;
            line-height: 1.5;
        }

        .auth-field {
            margin-bottom: 13px;
        }

        .auth-field label {
            display: block;
            margin-bottom: 6px;
            color: #4a5672;
            font-size: 13px;
            font-weight: 800;
        }

        .auth-input-wrap {
            position: relative;
        }

        .auth-input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa7c2;
            font-size: 14px;
            pointer-events: none;
        }

        .auth-input-extra {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa7c2;
            font-size: 14px;
        }

        .auth-input {
    width: 100%;
    height: 46px;
    border-radius: 13px;
    border: 1px solid #e2e8f4;
    background: #ffffff;
    padding: 0 16px;
    color: #182341;
    font-size: 13px;
    font-weight: 600;
    outline: none;
    transition: all 0.2s ease;
}

        .auth-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.12);
        }

        .auth-error {
            margin-top: 6px;
            color: #dc2626;
            font-size: 12px;
            font-weight: 500;
        }

        .auth-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 2px;
            margin-bottom: 14px;
        }

        .auth-remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #8895af;
            font-size: 12px;
            font-weight: 700;
        }

        .auth-remember input[type="checkbox"] {
            width: 14px;
            height: 14px;
            accent-color: #2563eb;
        }

        .auth-forgot {
            color: #2f6df6;
            font-size: 12px;
            font-weight: 800;
            text-decoration: none;
        }

        .auth-forgot:hover {
            text-decoration: underline;
        }

        .auth-submit {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 13px;
            background: linear-gradient(180deg, #2f6df6, #2458e8);
            color: #ffffff;
            font-size: 14px;
            font-weight: 900;
            letter-spacing: 0.02em;
            cursor: pointer;
            box-shadow: 0 14px 28px rgba(47, 109, 246, 0.30);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .auth-submit:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 32px rgba(47, 109, 246, 0.34);
        }

        .auth-divider {
            margin: 10px 0 8px;
            text-align: center;
            color: #b2bdd2;
            font-size: 12px;
            font-weight: 700;
        }

        .auth-register {
            text-align: center;
            color: #8a96ae;
            font-size: 12px;
            font-weight: 600;
        }

        .auth-register a {
            color: #2f6df6;
            font-weight: 900;
            text-decoration: none;
        }

        .auth-register a:hover {
            text-decoration: underline;
        }

        @media (max-width: 1100px) {
            html,
            body {
                overflow: auto;
            }

            .auth-login-page {
                height: auto;
                min-height: 100vh;
                overflow: auto;
            }

            .auth-login-shell {
                height: auto;
                max-height: none;
                grid-template-columns: 1fr;
                max-width: 860px;
            }

            .auth-left {
                min-height: 560px;
            }
        }

        @media (max-width: 768px) {
            .auth-login-page {
                padding: 10px;
            }

            .auth-left,
            .auth-right {
                padding: 22px;
            }

            .auth-hero-copy h1 {
                font-size: 40px;
            }

            .auth-form-head h2 {
                font-size: 30px;
            }

            .auth-scene {
                transform: scale(0.7);
            }
        }

        @media (max-width: 576px) {
            .auth-left {
                min-height: 520px;
                padding: 20px;
            }

            .auth-hero-copy {
                margin-top: 22px;
            }

            .auth-hero-copy h1 {
                font-size: 34px;
            }

            .auth-hero-copy .lead {
                font-size: 14px;
            }

            .auth-form-card {
                padding: 22px 20px;
                border-radius: 24px;
            }

            .auth-row {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <div class="auth-login-page">
        <div class="auth-login-shell">
            <div class="auth-left">
                <div class="auth-left-grid">
                    <div class="auth-top-badge">
                        <span>Sistem Pemesanan Logistik</span>
                    </div>

                    <div class="auth-hero-copy">
                        <h1>
                            Selamat Datang
                            <span>Kembali</span>
                        </h1>

                        <p class="lead">
                            Masuk ke sistem untuk mengelola pesanan, pelacakan pengiriman,
                            dan informasi logistik dengan cepat, rapi, dan efisien.
                        </p>

                        <p class="sub">
                            Solusi terpadu untuk operasional logistik modern.
                        </p>
                    </div>

                    <div class="auth-scene">
                        <div class="auth-route-line"></div>
                        <div class="auth-route-pin top">📍</div>
                        <div class="auth-route-pin bottom">📍</div>

                        <div class="auth-dashboard-panel">
                            <div class="dashboard-mini-label">Dashboard</div>
                            <div class="dashboard-mini-chart"></div>
                            <div class="dashboard-mini-bottom">
                                <div class="dashboard-mini-bars">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                                <div class="dashboard-mini-ring"></div>
                            </div>
                        </div>

                        <div class="ground-grid"></div>
                        <div class="ground-line"></div>

                        <div class="warehouse-roof"></div>
                        <div class="warehouse-base"></div>
                        <div class="warehouse-door"></div>
                        <div class="warehouse-window w1"></div>
                        <div class="warehouse-window w2"></div>

                        <div class="truck-glow"></div>
                        <div class="truck">
                            <div class="truck-cabin-line"></div>
                            <div class="truck-box-line"></div>
                            <div class="truck-wheel left"></div>
                            <div class="truck-wheel right"></div>
                        </div>

                        <div class="box b1"></div>
                        <div class="box b2"></div>
                        <div class="box b3"></div>
                    </div>
                </div>
            </div>

            <div class="auth-right">
                <div class="auth-form-card">

                    <div class="auth-form-head">
                        <h2>Masuk Akun</h2>
                        <p>
                            Silakan login menggunakan email dan password Anda
                            untuk melanjutkan ke dashboard.
                        </p>
                    </div>

                    @if (session('status'))
                        <div class="login-status">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="auth-field">
                            <label for="email">Email</label>
                            <div class="auth-input-wrap">
                                <input
                                    id="email"
                                    class="auth-input"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autofocus
                                    autocomplete="username"
                                    placeholder="Masukkan email Anda"
                                >
                            </div>

                            @error('email')
                                <div class="auth-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="auth-field">
                            <label for="password">Password</label>
                            <div class="auth-input-wrap">
                                <input
                                    id="password"
                                    class="auth-input"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan password"
                                >
                            </div>

                            @error('password')
                                <div class="auth-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="auth-row">
                            <label class="auth-remember" for="remember_me">
                                <input id="remember_me" type="checkbox" name="remember">
                                <span>Ingat saya</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="auth-forgot" href="{{ route('password.request') }}">
                                    Lupa password?
                                </a>
                            @endif
                        </div>

                        <button type="submit" class="auth-submit">
                            LOG IN
                        </button>

                        <div class="auth-divider">atau</div>

                        @if (Route::has('register'))
                            <div class="auth-register">
                                Belum punya akun?
                                <a href="{{ route('register') }}">Daftar sekarang</a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>