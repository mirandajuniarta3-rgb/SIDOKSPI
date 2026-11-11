<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f1f3ff">
    <title>Masuk - SIDOKSPI | PDAM Tirta Pinang</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #17233c;
            background: #f1f3ff;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        * { box-sizing: border-box; }

        body {
            min-width: 320px;
            min-height: 100vh;
            min-height: 100svh;
            margin: 0;
            display: grid;
            background: #fff;
        }

        button, input { font: inherit; }

        .login-shell {
            display: grid;
            grid-template-columns: minmax(0, .88fr) minmax(0, 1.2fr);
            width: 100%;
            min-height: 100vh;
            min-height: 100svh;
            overflow: hidden;
            background: #fff;
        }

        .brand-panel {
            position: relative;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            padding: clamp(32px, 5vw, 72px);
            color: #fff;
            background: linear-gradient(150deg, #1555b5 0%, #075bd7 53%, #176b91 100%);
        }

        .brand-panel::before,
        .brand-panel::after {
            position: absolute;
            right: -14%;
            bottom: -96px;
            left: -12%;
            height: 180px;
            border-radius: 50% 50% 0 0;
            background: rgb(111 199 246 / 17%);
            content: "";
            transform: rotate(-5deg);
        }

        .brand-panel::after {
            right: -25%;
            bottom: -119px;
            left: 18%;
            height: 180px;
            background: rgb(111 199 246 / 12%);
            transform: rotate(7deg);
        }

        .brand-header, .brand-content, .brand-footer { position: relative; z-index: 1; }

        .brand-header {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .brand-mark {
            display: grid;
            width: 56px;
            height: 56px;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 12px;
            background: rgb(255 255 255 / 17%);
        }

        .brand-mark svg { width: 30px; height: 30px; }

        .brand-tag {
            display: inline-block;
            margin-bottom: 4px;
            padding: 3px 10px;
            border-radius: 20px;
            background: #7ff2dc;
            color: #073964;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .045em;
        }

        .brand-organization { color: #d3e2ff; font-size: 12px; }

        .brand-content { margin: 28px 0 auto; }

        .brand-content h1 {
            margin: 0 0 7px;
            font-size: clamp(30px, 4vw, 34px);
            letter-spacing: -.045em;
            line-height: 1.16;
        }

        .brand-content h1 span { color: #82f2dc; }

        .brand-description {
            max-width: 370px;
            margin: 0;
            color: #e2edff;
            font-size: 14px;
            line-height: 1.55;
        }

        .feature-list {
            display: grid;
            gap: 12px;
            margin: 40px 0 0;
            padding: 0;
            list-style: none;
        }

        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 13px;
            border: 1px solid rgb(255 255 255 / 5%);
            border-radius: 9px;
            background: rgb(255 255 255 / 11%);
        }

        .feature-icon {
            display: grid;
            width: 27px;
            height: 27px;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 6px;
            background: #7ff2dc;
            color: #075b7d;
        }

        .feature-icon svg { width: 16px; height: 16px; }
        .feature-copy strong { display: block; margin: 1px 0 3px; font-size: 11px; }
        .feature-copy span { display: block; color: #d8e6ff; font-size: 11px; line-height: 1.5; }

        .brand-footer {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding-top: 24px;
            color: #d1e4ff;
            font-size: 10px;
            font-weight: 650;
        }

        .brand-footer span:first-child::before {
            display: inline-block;
            width: 6px;
            height: 6px;
            margin-right: 7px;
            border-radius: 50%;
            background: #7ff2dc;
            content: "";
            vertical-align: 1px;
        }

        .form-panel {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 54px clamp(36px, 8vw, 120px);
        }

        .form-content { width: min(100%, 394px); }

        .portal-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 14px;
            padding: 6px 12px;
            border-radius: 999px;
            background: #eef0ff;
            color: #074bb4;
            font-size: 10px;
            font-weight: 750;
        }

        .portal-badge svg { width: 13px; height: 13px; }

        .form-content h2 {
            margin: 0 0 5px;
            font-size: 24px;
            letter-spacing: -.035em;
        }

        .intro {
            margin: 0 0 27px;
            color: #5e6a80;
            font-size: 13px;
            line-height: 1.55;
        }

        .alert {
            margin-bottom: 18px;
            padding: 11px 13px;
            border: 1px solid #fecaca;
            border-radius: 8px;
            background: #fff1f2;
            color: #b42332;
            font-size: 12px;
            line-height: 1.5;
        }

        .field { margin-bottom: 17px; }
        .field label { display: block; margin-bottom: 7px; color: #17233c; font-size: 11px; font-weight: 750; }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 13px;
            width: 17px;
            height: 17px;
            color: #77849b;
            pointer-events: none;
            transform: translateY(-50%);
        }

        .field input {
            width: 100%;
            min-height: 42px;
            padding: 10px 13px 10px 39px;
            border: 1px solid transparent;
            border-radius: 8px;
            outline: none;
            background: #f7f6fc;
            color: #17233c;
            font-size: 12px;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }

        .field input::placeholder { color: #9aa4b6; }
        .field input:focus { border-color: #3c72d4; background: #fff; box-shadow: 0 0 0 3px rgb(60 114 212 / 13%); }
        .field input[aria-invalid="true"] { border-color: #d92d3a; }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            display: grid;
            width: 28px;
            height: 28px;
            place-items: center;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: #77849b;
            cursor: pointer;
            transform: translateY(-50%);
        }

        .toggle-password:hover { background: #ecebf5; color: #174fa8; }
        .toggle-password svg { width: 17px; height: 17px; }
        .password-input { padding-right: 43px !important; }
        .field-error { margin: 6px 0 0; color: #b42332; font-size: 11px; }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 1px 0 22px;
            color: #56647a;
            font-size: 11px;
        }

        .remember-option { display: inline-flex; align-items: center; gap: 8px; cursor: pointer; }
        .remember-option input { width: 15px; height: 15px; margin: 0; accent-color: #0754bc; }
        .help-link { color: #0754bc; font-weight: 700; text-decoration: none; }
        .help-link:hover { text-decoration: underline; }

        .submit-button {
            display: flex;
            width: 100%;
            min-height: 42px;
            align-items: center;
            justify-content: center;
            gap: 9px;
            border: 0;
            border-radius: 8px;
            background: #064bb2;
            color: #fff;
            cursor: pointer;
            font-size: 12px;
            font-weight: 750;
            transition: background .15s, transform .15s;
        }

        .submit-button:hover { background: #063e96; }
        .submit-button:active { transform: translateY(1px); }
        .submit-button svg { width: 16px; height: 16px; }

        .security-note {
            display: flex;
            min-height: 39px;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 24px;
            border-radius: 8px;
            background: #f0f1ff;
            color: #36455e;
            font-size: 10px;
            text-align: center;
        }

        .security-note svg { width: 14px; height: 14px; color: #047568; }

        .help-copy {
            margin: 36px 0 0;
            color: #647087;
            font-size: 11px;
            line-height: 1.6;
            text-align: center;
        }

        .help-copy strong { color: #17233c; }

        .copyright {
            margin: 64px 0 0;
            color: #647087;
            font-size: 10px;
            text-align: center;
        }

        @media (max-width: 760px) {
            .login-shell { grid-template-columns: 1fr; }
            .brand-panel { min-height: 280px; padding: 27px; }
            .brand-content { margin: 22px 0 12px; }
            .brand-content h1 { font-size: 27px; }
            .brand-description { max-width: 470px; font-size: 13px; }
            .feature-list { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; margin-top: 20px; }
            .feature-item { gap: 8px; padding: 10px; }
            .feature-copy span { font-size: 10px; }
            .brand-footer { padding-top: 20px; }
            .form-panel { padding: 38px 28px; }
            .help-copy { margin-top: 27px; }
            .copyright { margin-top: 40px; }
        }

        @media (max-width: 480px) {
            body { padding: 0; background: #fff; }
            .login-shell { border-radius: 0; box-shadow: none; }
            .brand-panel { min-height: 245px; padding: 22px; }
            .brand-mark { width: 46px; height: 46px; }
            .feature-list { grid-template-columns: 1fr; gap: 7px; }
            .feature-item { padding: 8px 10px; }
            .feature-copy span { font-size: 10px; }
            .form-panel { padding: 32px 22px; }
            .form-content h2 { font-size: 22px; }
        }
    </style>
</head>
<body>
    <main class="login-shell">
        <section class="brand-panel" aria-label="Informasi SIDOKSPI">
            <header class="brand-header">
                <div class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 32 32" fill="none">
                        <path d="M17.7 2.8c-1.6 4.1-9 10.1-9 17a7.3 7.3 0 0 0 14.6 0c0-5.3-3.1-11.4-5.6-17Z" fill="#35A8F5"/>
                        <path d="M17.3 9.5c-1.2 3.2-5.1 7.5-5.1 11.4a3.6 3.6 0 0 0 5.1 3.3c-2-3.6-1.2-8.8 0-14.7Z" fill="#C5F8FF"/>
                    </svg>
                </div>
                <div>
                    <span class="brand-tag">SISTEM INFORMASI SPI</span>
                    <div class="brand-organization">PDAM Tirta Pinang</div>
                </div>
            </header>

            <div class="brand-content">
                <h1>SIDOKSPI<span>.</span></h1>
                <p class="brand-description">Sistem Penyimpanan Dokumen dan SK Satuan Pengawas Internal PDAM Tirta Pinang</p>

                <ul class="feature-list">
                    <li class="feature-item">
                        <span class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3m-4 4v3"/></svg>
                        </span>
                        <span class="feature-copy"><strong>Arsip Digital Terenkripsi</strong><span>Proteksi repositori Surat Keputusan (SK) dan Berita Acara tingkat tinggi.</span></span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 8h8M8 12h3m2 0 1.2 1.2L17 11m-9 5h8"/></svg>
                        </span>
                        <span class="feature-copy"><strong>Pengawasan Internal Akuntabel</strong><span>Jejak audit digital komprehensif bagi auditor dan manajemen perusahaan.</span></span>
                    </li>
                    <li class="feature-item">
                        <span class="feature-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 8 3v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3Z"/><path d="m9 12 2 2 4-4"/></svg>
                        </span>
                        <span class="feature-copy"><strong>Integritas &amp; Tata Kelola Perusahaan</strong><span>Standardisasi Good Corporate Governance (GCG) sektor air minum daerah.</span></span>
                    </li>
                </ul>
            </div>

            <footer class="brand-footer">
                <span>v2.4.0 SPI Production Environment</span>
                <span>ID-PKP</span>
            </footer>
        </section>

        <section class="form-panel" aria-labelledby="login-title">
            <div class="form-content">
                <div class="portal-badge">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2 4.5 5v5.4c0 5 3.2 9.6 7.5 11.6 4.3-2 7.5-6.6 7.5-11.6V5L12 2Zm0 4 4.5 1.8v2.6c0 3.5-1.9 6.8-4.5 8.5-2.6-1.7-4.5-5-4.5-8.5V7.8L12 6Zm-1 3v6h2V9h-2Zm0 7v2h2v-2h-2Z"/></svg>
                    Portal Akses Pegawai &amp; Auditor
                </div>
                <h2 id="login-title">Selamat Datang</h2>
                <p class="intro">Silakan masuk ke akun pegawai / auditor SPI Anda untuk melanjutkan verifikasi dan arsip dokumen.</p>

                @if (session('success'))
                    <div class="alert" role="status" style="border-color:#a6e5ca;background:#effcf6;color:#08734e">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}">
                    @csrf

                    <div class="field">
                        <label for="identifier">Email / NIK Pegawai</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="4" y="6" width="16" height="14" rx="2"/><path d="M9 6V4h6v2m-9 5h12m-9 3h2m2 0h2"/></svg>
                            <input id="identifier" name="identifier" type="text" value="{{ old('identifier') }}" placeholder="nama@pdamtirtapinang.co.id atau NIK" autocomplete="username" required autofocus aria-invalid="{{ $errors->has('identifier') ? 'true' : 'false' }}">
                        </div>
                        @error('identifier')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="field">
                        <label for="password">Kata Sandi</label>
                        <div class="input-wrap">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 1 1 8 0v3m-4 4v3"/></svg>
                            <input class="password-input" id="password" name="password" type="password" placeholder="Masukkan kata sandi akun" autocomplete="current-password" required aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                            <button class="toggle-password" type="button" aria-label="Tampilkan kata sandi" aria-pressed="false" data-password-toggle>
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M2.5 12s3.2-6 9.5-6 9.5 6 9.5 6-3.2 6-9.5 6-9.5-6-9.5-6Z"/><circle cx="12" cy="12" r="2.7"/></svg>
                            </button>
                        </div>
                        @error('password')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-options">
                        <label class="remember-option" for="remember">
                            <input id="remember" name="remember" type="checkbox" value="1" @checked(old('remember'))>
                            <span>Ingat Saya</span>
                        </label>
                        <a class="help-link" href="#bantuan">Lupa Kata Sandi?</a>
                    </div>

                    <button class="submit-button" type="submit">
                        Masuk ke Sistem
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M13 5h5a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-5m-2-4 4-3-4-3m4 3H3"/></svg>
                    </button>
                </form>

                <div class="security-note">
                    <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="m12 2 2.3 1.6 2.8-.1 1.2 2.5 2.4 1.4-.5 2.8 1 2.6-2 2-1 2.6-2.8.2L12 21l-2.4-1.6-2.8.1-1.2-2.5-2.4-1.4.5-2.8-1-2.6 2-2 1-2.6 2.8-.2L12 2Zm-1 13.6 5-5-1.4-1.4-3.6 3.6-1.6-1.6L8 12.6l3 3Z"/></svg>
                    Dilindungi Enkripsi 256-bit SSL Satuan Pengawas Internal
                </div>

                <p class="help-copy" id="bantuan">Kendala akses atau akun baru? Hubungi Helpdesk SPI atau ekst. <strong>1402</strong></p>
                <p class="copyright">&copy; PDAM Tirta Pinang. Hak Cipta Dilindungi.</p>
            </div>
        </section>
    </main>

    <script>
        document.querySelector('[data-password-toggle]')?.addEventListener('click', function () {
            const password = document.getElementById('password');
            const isVisible = password.type === 'text';

            password.type = isVisible ? 'password' : 'text';
            this.setAttribute('aria-pressed', String(!isVisible));
            this.setAttribute('aria-label', isVisible ? 'Tampilkan kata sandi' : 'Sembunyikan kata sandi');
        });
    </script>
</body>
</html>
