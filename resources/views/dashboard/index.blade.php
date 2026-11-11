<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f6f7ff">
    <title>Dashboard Admin - SIDOKSPI</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #17243b;
            background: #f7f7ff;
            font-synthesis: none;
            text-rendering: optimizeLegibility;
            -webkit-font-smoothing: antialiased;
            --blue: #0753c5;
            --muted: #78839a;
            --line: #edf0f8;
            --panel: #fff;
        }

        * { box-sizing: border-box; }
        body { min-width: 360px; min-height: 100vh; margin: 0; background: #f7f7ff; }
        button, input { font: inherit; }
        a { color: inherit; }
        .app-layout { min-height: 100vh; }

        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 5;
            display: flex;
            width: 174px;
            flex-direction: column;
            padding: 18px 10px 12px;
            border-right: 1px solid #eef0f7;
            background: #fff;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 7px;
            min-height: 42px;
            margin: 0 4px 18px;
            text-decoration: none;
        }

        .brand-mark {
            display: grid;
            width: 25px;
            height: 25px;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 7px;
            background: #0755cf;
            color: #fff;
        }

        .brand-mark svg { width: 17px; height: 17px; }
        .brand-name { display: block; color: #0849a8; font-size: 11px; font-weight: 850; letter-spacing: -.025em; }
        .brand-caption { display: block; margin-top: 1px; color: #717d91; font-size: 8px; }
        .nav-label { margin: 9px 5px 7px; color: #929bb0; font-size: 8px; font-weight: 800; letter-spacing: .075em; }
        .nav-list { display: grid; gap: 2px; margin: 0; padding: 0; list-style: none; }

        .nav-link {
            display: flex;
            min-height: 30px;
            align-items: center;
            gap: 8px;
            padding: 6px 7px;
            border-radius: 6px;
            color: #4f5c72;
            font-size: 9px;
            font-weight: 650;
            text-decoration: none;
        }

        .nav-link svg { width: 13px; height: 13px; flex: 0 0 auto; }
        .nav-link.active { background: #edf2ff; color: #064eb6; }
        .nav-link:not(.active):not(.disabled):hover { background: #f6f7fb; color: #064eb6; }
        .nav-link.disabled { color: #68748a; cursor: default; }
        .nav-chevron { width: 10px !important; height: 10px !important; margin-left: auto; }
        .sidebar-bottom { margin-top: auto; }

        .system-status {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            padding: 9px 7px;
            border: 1px solid #e8ecfa;
            border-radius: 7px;
            background: #f3f5ff;
            color: #40516b;
            font-size: 8px;
            line-height: 1.45;
        }

        .system-status svg { width: 14px; height: 14px; flex: 0 0 auto; color: #15846d; }
        .system-status strong { display: block; font-size: 8px; }
        .version { display: block; color: #8a94a8; }

        .main-area { min-height: 100vh; margin-left: 174px; }

        .topbar {
            position: sticky;
            top: 0;
            z-index: 4;
            display: flex;
            min-height: 50px;
            align-items: center;
            gap: 12px;
            padding: 8px 18px;
            border-bottom: 1px solid #eceef6;
            background: rgb(255 255 255 / 94%);
            backdrop-filter: blur(12px);
        }

        .breadcrumb { flex: 0 0 auto; color: #4e5a70; font-size: 9px; font-weight: 650; white-space: nowrap; }
        .breadcrumb span { margin: 0 5px; color: #a3adbf; }
        .breadcrumb strong { color: #0753c5; font-weight: 650; }

        .search-form {
            display: flex;
            width: min(250px, 30vw);
            min-width: 135px;
            height: 28px;
            align-items: center;
            gap: 6px;
            margin-left: 8px;
            padding: 0 8px;
            border: 1px solid #eef0f7;
            border-radius: 6px;
            background: #fbfbfe;
        }

        .search-form svg { width: 13px; height: 13px; flex: 0 0 auto; color: #8792a6; }
        .search-form input { width: 100%; min-width: 0; border: 0; outline: 0; background: transparent; color: #263650; font-size: 9px; }
        .search-form input::placeholder { color: #9aa4b6; }
        .topbar-spacer { flex: 1; }

        .icon-button {
            position: relative;
            display: grid;
            width: 25px;
            height: 25px;
            flex: 0 0 auto;
            place-items: center;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: #57647a;
        }

        .icon-button svg { width: 15px; height: 15px; }
        .notification-dot { position: absolute; top: 4px; right: 4px; width: 5px; height: 5px; border: 1px solid #fff; border-radius: 50%; background: #e44b58; }
        .role-badge { padding: 5px 8px; border-radius: 5px; background: #d7fbf2; color: #087c67; font-size: 8px; font-weight: 750; white-space: nowrap; }

        .account {
            display: flex;
            align-items: center;
            gap: 6px;
            min-width: 0;
            padding: 2px 0 2px 4px;
        }

        .avatar {
            display: grid;
            width: 24px;
            height: 24px;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 50%;
            background: #0753c5;
            color: #fff;
            font-size: 9px;
            font-weight: 800;
        }

        .account-name { display: block; max-width: 95px; overflow: hidden; color: #263650; font-size: 8px; font-weight: 750; text-overflow: ellipsis; white-space: nowrap; }
        .account-role { display: block; margin-top: 1px; color: #7e899c; font-size: 8px; }
        .logout-form { display: inline-flex; margin-left: 1px; }
        .logout-button { border: 0; background: none; color: #768196; cursor: pointer; }
        .logout-button svg { width: 11px; height: 11px; }

        .page-content { width: min(1160px, 100%); margin: 0 auto; padding: 19px 14px 28px; }
        .page-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 14px; margin-bottom: 14px; }
        .eyebrow { display: flex; align-items: center; gap: 5px; margin-bottom: 4px; color: #16816e; font-size: 8px; font-weight: 800; letter-spacing: .055em; text-transform: uppercase; }
        .eyebrow::before { width: 6px; height: 6px; border-radius: 50%; background: #1aa885; content: ""; }
        .page-heading h1 { margin: 0; color: #1a2841; font-size: clamp(20px, 2.2vw, 25px); letter-spacing: -.045em; line-height: 1.15; }
        .page-description { max-width: 510px; margin: 4px 0 0; color: #66738a; font-size: 9px; line-height: 1.5; }
        .heading-actions { display: flex; flex: 0 0 auto; flex-direction: column; align-items: flex-end; gap: 5px; }

        .period-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 8px;
            border-radius: 5px;
            background: #eef0ff;
            color: #415276;
            font-size: 8px;
            font-weight: 700;
            white-space: nowrap;
        }

        .period-badge svg { width: 11px; height: 11px; color: #3266bf; }

        .primary-button {
            display: inline-flex;
            min-height: 27px;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 0 10px;
            border: 0;
            border-radius: 6px;
            background: #0754c5;
            color: #fff;
            font-size: 9px;
            font-weight: 750;
            text-decoration: none;
            cursor: default;
        }

        .primary-button svg { width: 12px; height: 12px; }
        .notice { margin: 0 0 12px; padding: 8px 10px; border: 1px solid #bdebd9; border-radius: 7px; background: #effcf6; color: #08734e; font-size: 10px; }
        .stats-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 9px; }

        .stat-card, .panel, .table-panel {
            min-width: 0;
            border: 1px solid #f0f1f8;
            border-radius: 9px;
            background: var(--panel);
            box-shadow: 0 2px 8px rgb(34 49 86 / 3%);
        }

        .stat-card { min-height: 95px; padding: 11px; }
        .stat-top { display: flex; align-items: flex-start; justify-content: space-between; gap: 6px; }
        .stat-label { color: #667187; font-size: 8px; font-weight: 800; letter-spacing: .035em; text-transform: uppercase; }
        .stat-value { margin-top: 3px; color: #17243b; font-size: clamp(22px, 2.6vw, 27px); font-weight: 800; letter-spacing: -.055em; line-height: 1.05; }
        .stat-foot { display: flex; align-items: center; gap: 5px; margin-top: 8px; color: #7d8798; font-size: 8px; }
        .stat-foot svg { width: 11px; height: 11px; color: #5d8dcc; }
        .stat-change { padding: 3px 5px; border-radius: 4px; background: #eaf0ff; color: #3561b3; font-weight: 750; }
        .stat-change.teal { background: #d9fbf2; color: #087a68; }

        .stat-icon {
            display: grid;
            width: 31px;
            height: 31px;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 7px;
            background: #e9efff;
            color: #2860bb;
        }

        .stat-icon.teal { background: #d7faf2; color: #06836e; }
        .stat-icon.sky { background: #e2f1ff; color: #2672bd; }
        .stat-icon.gray { background: #f0f1f8; color: #657187; }
        .stat-icon svg { width: 16px; height: 16px; }

        .analytics-grid { display: grid; grid-template-columns: minmax(0, 1.18fr) minmax(0, .82fr); gap: 10px; margin-top: 11px; }
        .panel { padding: 13px; }
        .panel-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 9px; }
        .panel-heading h2 { margin: 0; color: #26344c; font-size: 12px; font-weight: 800; letter-spacing: -.015em; }
        .panel-subtitle { margin: 3px 0 0; color: #8a94a7; font-size: 8px; }
        .panel-tag { padding: 4px 6px; border-radius: 4px; background: #f0f2ff; color: #53648a; font-size: 8px; font-weight: 700; white-space: nowrap; }
        .category-list { display: grid; gap: 11px; margin-top: 16px; }
        .category-row { min-width: 0; }
        .category-meta { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 4px; color: #49566b; font-size: 8px; }
        .category-name { display: flex; min-width: 0; align-items: center; gap: 6px; overflow: hidden; font-weight: 650; text-overflow: ellipsis; white-space: nowrap; }
        .category-dot { width: 6px; height: 6px; flex: 0 0 auto; border-radius: 2px; background: #0754c5; }
        .category-count { flex: 0 0 auto; color: #68758a; font-weight: 700; }
        .progress-track { height: 6px; overflow: hidden; border-radius: 20px; background: #edf0ff; }
        .progress-value { height: 100%; border-radius: inherit; background: #0754b7; }
        .progress-value.alt-1 { background: #075b81; }
        .progress-value.alt-2 { background: #07816e; }
        .progress-value.alt-3 { background: #1762d0; }
        .progress-value.alt-4 { background: #85889a; }
        .category-empty { padding: 18px 8px; color: #8a94a7; font-size: 9px; text-align: center; }

        .chart-summary { text-align: right; }
        .chart-summary span { display: block; color: #8c96a7; font-size: 7px; }
        .chart-summary strong { display: block; margin-top: 2px; color: #0754b7; font-size: 9px; }
        .chart-wrap { margin-top: 9px; }
        .upload-chart { display: block; width: 100%; height: 135px; overflow: visible; }
        .chart-gridline { stroke: #eef1fa; stroke-width: 1; }
        .chart-area { fill: url(#chart-fill); }
        .chart-line { fill: none; stroke: #1761d1; stroke-linecap: round; stroke-linejoin: round; stroke-width: 3; }
        .chart-point { fill: #fff; stroke: #137e72; stroke-width: 2; }
        .chart-labels { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 2px; margin-top: 3px; color: #7d8799; font-size: 7px; text-align: center; }
        .chart-footer { display: flex; align-items: center; gap: 5px; margin-top: 10px; color: #748096; font-size: 8px; }
        .chart-footer strong { color: #0754b7; font-weight: 700; }
        .chart-footer-dot { width: 6px; height: 6px; border-radius: 50%; background: #168776; }

        .tables-grid { display: grid; grid-template-columns: minmax(0, 1.18fr) minmax(0, .82fr); gap: 10px; margin-top: 11px; }
        .table-panel { min-width: 0; overflow: hidden; }
        .table-panel-heading { display: flex; align-items: center; justify-content: space-between; gap: 8px; padding: 11px 12px 9px; }
        .table-title { display: flex; align-items: center; gap: 7px; }
        .table-title .stat-icon { width: 25px; height: 25px; border-radius: 6px; }
        .table-title .stat-icon svg { width: 13px; height: 13px; }
        .table-title h2 { margin: 0; color: #25334b; font-size: 11px; font-weight: 800; }
        .table-title p { margin: 2px 0 0; color: #8a94a7; font-size: 7px; }
        .view-all { display: inline-flex; flex: 0 0 auto; align-items: center; gap: 4px; color: #0754b7; font-size: 8px; font-weight: 750; text-decoration: none; }
        .view-all svg { width: 11px; height: 11px; }
        .table-scroll { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        thead { background: #f5f6ff; }
        th { padding: 7px 9px; color: #737e94; font-size: 7px; font-weight: 800; letter-spacing: .035em; white-space: nowrap; }
        td { max-width: 130px; padding: 8px 9px; border-top: 1px solid #f0f1f7; color: #4f5d73; font-size: 8px; line-height: 1.4; }
        td:first-child { color: #0754b7; font-weight: 700; }
        .cell-clip { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .category-badge { display: inline-block; max-width: 85px; overflow: hidden; padding: 3px 5px; border-radius: 4px; background: #e7edff; color: #315eb0; font-size: 7px; text-overflow: ellipsis; vertical-align: middle; white-space: nowrap; }
        .category-badge.teal { background: #d9faf2; color: #087c68; }
        .empty-cell { padding: 18px 9px; color: #8993a5 !important; font-weight: 500 !important; text-align: center; }
        .search-result { margin: 0; padding: 8px 12px; border-bottom: 1px solid #f0f1f7; color: #647189; font-size: 8px; }

        @media (min-width: 1200px) {
            .page-content { padding-right: 24px; padding-left: 24px; }
            .stats-grid { gap: 12px; }
            .analytics-grid, .tables-grid { gap: 13px; }
        }

        @media (max-width: 850px) {
            .sidebar { width: 154px; }
            .main-area { margin-left: 154px; }
            .topbar { gap: 8px; padding-right: 12px; padding-left: 12px; }
            .breadcrumb { display: none; }
            .search-form { margin-left: 0; }
            .analytics-grid, .tables-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 620px) {
            body { min-width: 320px; }
            .sidebar { position: static; width: 100%; padding: 9px 12px; border-right: 0; border-bottom: 1px solid #eceef6; }
            .brand { min-height: 30px; margin: 0 0 7px; }
            .nav-label, .sidebar-bottom { display: none; }
            .nav-list { display: flex; gap: 4px; overflow-x: auto; padding-bottom: 2px; }
            .nav-link { min-height: 30px; flex: 0 0 auto; padding: 6px 8px; }
            .nav-link.disabled { display: none; }
            .main-area { min-height: auto; margin-left: 0; }
            .topbar { min-height: 44px; padding: 7px 12px; }
            .search-form { width: 100%; min-width: 0; }
            .role-badge { display: none; }
            .account-name { max-width: 76px; }
            .page-content { padding: 16px 12px 24px; }
            .page-heading { align-items: flex-start; }
            .page-description { max-width: 360px; }
            .heading-actions .period-badge { display: none; }
            .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 8px; }
            .stat-card { min-height: 88px; padding: 10px; }
            .stat-value { font-size: 23px; }
            .panel { padding: 11px; }
        }

        @media (max-width: 390px) {
            .page-heading { flex-direction: column; }
            .heading-actions { width: 100%; flex-direction: row; align-items: center; justify-content: flex-end; }
            .heading-actions .period-badge { display: inline-flex; margin-right: auto; }
            .search-form { height: 27px; }
            .account-role { display: none; }
            .upload-chart { height: 118px; }
        }
    </style>
</head>
<body>
    @php
        $user = auth()->user();
        $initials = collect(explode(' ', trim($user->nama_lengkap)))
            ->filter()
            ->take(2)
            ->map(fn ($part) => mb_substr($part, 0, 1))
            ->implode('');
        $chartMaximum = max(1, (int) $uploadsPerBulan->max('total'));
        $chartPoints = $uploadsPerBulan->values()->map(function ($month, $index) use ($uploadsPerBulan, $chartMaximum) {
            $x = 12 + ($index * 576 / max(1, $uploadsPerBulan->count() - 1));
            $y = 158 - ($month['total'] / $chartMaximum * 120);

            return round($x, 1).','.round($y, 1);
        });
        $chartLine = $chartPoints->implode(' ');
        $chartArea = '12,168 '.$chartLine.' 588,168';
        $activeCategoryCount = $kategori->where('dokumen_count', '>', 0)->count();
        $periodText = now()->locale('id')->translatedFormat('F Y');
    @endphp

    <div class="app-layout">
        <aside class="sidebar" aria-label="Navigasi utama">
            <a class="brand" href="{{ route('dashboard') }}">
                <span class="brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3 4 6v5c0 5 3.5 8.5 8 10 4.5-1.5 8-5 8-10V6l-8-3Z"/><path d="M8.5 12h7"/></svg>
                </span>
                <span><span class="brand-name">SIDOKSPI</span><span class="brand-caption">SPI PDAM Tirta Pinang</span></span>
            </a>

            <p class="nav-label">MENU NAVIGASI</p>
            <nav aria-label="Menu dashboard">
                <ul class="nav-list">
                    <li><a class="nav-link active" href="{{ route('dashboard') }}" aria-current="page"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>Dashboard</a></li>
                    <li><span class="nav-link disabled" aria-disabled="true" title="Menu dokumen belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 3h8l5 5v13H6z"/><path d="M14 3v6h5M9 14h7m-7 3h7"/></svg>Dokumen</span></li>
                    <li><span class="nav-link disabled" aria-disabled="true" title="Menu surat keputusan belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 3h12v18H6zM9 7h6m-6 4h6m-6 4h4"/></svg>Surat Keputusan (SK)</span></li>
                    <li><span class="nav-link disabled" aria-disabled="true" title="Menu laporan belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20V4m0 16h17M8 16v-4m5 4V7m5 9v-6"/></svg>Laporan</span></li>
                </ul>

                <p class="nav-label">MASTER DATA</p>
                <ul class="nav-list">
                    <li><span class="nav-link disabled" aria-disabled="true" title="Menu kategori belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 12 12 20l-9-9V4h7l10 8Z"/><circle cx="7.5" cy="8" r="1"/></svg>Kategori Dokumen</span></li>
                    <li><span class="nav-link disabled" aria-disabled="true" title="Menu pengguna belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="4"/><path d="M2 21v-2a7 7 0 0 1 14 0v2m3-12a4 4 0 0 1 0 8m2 4v-2a7 7 0 0 0-3-5.7"/></svg>Pengguna</span></li>
                    <li><span class="nav-link disabled" aria-disabled="true" title="Menu log aktivitas belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 12a9 9 0 1 0 2.6-6.4L3 8m0-5v5h5m4-1v5l3 2"/></svg>Log Aktivitas</span></li>
                </ul>
            </nav>

            <div class="sidebar-bottom">
                <div class="system-status">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 8 3v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3Z"/><path d="m9 12 2 2 4-4"/></svg>
                    <span><strong>Sistem SPI Aktif</strong><span class="version">Dashboard Administrasi</span></span>
                </div>
            </div>
        </aside>

        <div class="main-area">
            <header class="topbar">
                <div class="breadcrumb">Portal SPI <span>›</span><strong>Beranda Administrasi</strong></div>
                <form class="search-form" method="GET" action="{{ route('dashboard') }}" role="search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg>
                    <input type="search" name="search" value="{{ $search }}" placeholder="Cari dokumen, SK, nomor surat..." aria-label="Cari dokumen atau surat keputusan">
                </form>
                <div class="topbar-spacer"></div>
                <span class="role-badge">Administrator SPI</span>
                <div class="account">
                    <span class="avatar" aria-hidden="true">{{ mb_strtoupper($initials ?: 'A') }}</span>
                    <span><span class="account-name">{{ $user->nama_lengkap }}</span><span class="account-role">Administrator</span></span>
                </div>
                <form class="logout-form" method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="icon-button logout-button" type="submit" aria-label="Keluar dari akun" title="Logout">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M10 17l5-5-5-5m5 5H3m9-9h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/></svg>
                    </button>
                </form>
            </header>

            <main class="page-content">
                @if (session('success'))
                    <div class="notice" role="status">{{ session('success') }}</div>
                @endif

                <section class="page-heading" aria-labelledby="page-title">
                    <div>
                        <div class="eyebrow">Sistem Pengawasan Internal Terintegrasi</div>
                        <h1 id="page-title">Dashboard Utama SPI</h1>
                        <p class="page-description">Ringkasan operasional kepatuhan, pengawasan internal, dan repositori arsip digital PDAM Tirta Pinang.</p>
                    </div>
                    <div class="heading-actions">
                        <span class="period-badge">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg>
                            Periode: {{ $periodText }}
                        </span>
                        <span class="primary-button" aria-disabled="true" title="Fitur unggah dokumen belum tersedia">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 16V4m-5 5 5-5 5 5M4 16v4h16v-4"/></svg>
                            Unggah Dokumen
                        </span>
                    </div>
                </section>

                <section class="stats-grid" aria-label="Statistik dokumen">
                    <article class="stat-card">
                        <div class="stat-top"><div><div class="stat-label">Total Dokumen</div><div class="stat-value">{{ number_format($totalDokumen, 0, ',', '.') }}</div></div><span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h6l2 2h8v11H4z"/><path d="M4 8h16"/></svg></span></div>
                        <div class="stat-foot"><span class="stat-change">Arsip SPI</span><span>dokumen terdaftar</span></div>
                    </article>
                    <article class="stat-card">
                        <div class="stat-top"><div><div class="stat-label">Total SK Direksi</div><div class="stat-value">{{ number_format($totalSk, 0, ',', '.') }}</div></div><span class="stat-icon teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m4 5 2-2 2 2-2 2-2-2Zm12 14 2-2 2 2-2 2-2-2Z"/><path d="m7.5 7.5 9 9M14 4l6 6m-2-8 4 4M2 18l4 4"/></svg></span></div>
                        <div class="stat-foot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 12h16m-6-6 6 6-6 6"/></svg><span>{{ number_format($totalSk, 0, ',', '.') }} surat keputusan tersimpan</span></div>
                    </article>
                    <article class="stat-card">
                        <div class="stat-top"><div><div class="stat-label">Dokumen Bulan Ini</div><div class="stat-value">{{ number_format($dokumenBulanIni, 0, ',', '.') }}</div></div><span class="stat-icon sky"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 18 9 12l4 3 7-9"/><path d="M14 6h6v6"/></svg></span></div>
                        <div class="stat-foot"><span class="stat-change teal">{{ now()->locale('id')->translatedFormat('F') }}</span><span>unggahan dokumen</span></div>
                    </article>
                    <article class="stat-card">
                        <div class="stat-top"><div><div class="stat-label">Dokumen Diarsipkan</div><div class="stat-value">{{ number_format($dokumenDiarsipkan, 0, ',', '.') }}</div></div><span class="stat-icon gray"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 8h8m-8 4h8m-8 4h5"/></svg></span></div>
                        <div class="stat-foot"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 12a8 8 0 1 0 2.3-5.7L4 9m0-5v5h5"/></svg><span>status arsip</span></div>
                    </article>
                </section>

                <section class="analytics-grid" aria-label="Analisis dokumen">
                    <article class="panel">
                        <div class="panel-heading">
                            <div><h2>Dokumen per Kategori</h2><p class="panel-subtitle">Klasifikasi repositori pengawasan operasional &amp; manajerial</p></div>
                            <span class="panel-tag">{{ $activeCategoryCount }} Kategori Aktif</span>
                        </div>

                        @if ($kategori->isNotEmpty())
                            <div class="category-list">
                                @foreach ($kategori->take(5) as $index => $item)
                                    @php
                                        $categoryWidth = $item->dokumen_count > 0
                                            ? max(4, ($item->dokumen_count / $kategoriTerbanyak) * 100)
                                            : 0;
                                    @endphp
                                    <div class="category-row">
                                        <div class="category-meta">
                                            <span class="category-name"><span class="category-dot"></span>{{ $item->nama_kategori }}</span>
                                            <span class="category-count">{{ number_format($item->dokumen_count, 0, ',', '.') }} dok</span>
                                        </div>
                                        <div class="progress-track" role="progressbar" aria-label="Dokumen {{ $item->nama_kategori }}" aria-valuemin="0" aria-valuemax="{{ $kategoriTerbanyak }}" aria-valuenow="{{ $item->dokumen_count }}">
                                            <div class="progress-value alt-{{ $index % 5 }}" style="width: {{ $categoryWidth }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="category-empty">Belum ada kategori dokumen.</div>
                        @endif

                        <div class="chart-footer"><span class="chart-footer-dot"></span><span>Total dokumen terklasifikasi:</span><strong>{{ number_format($totalDokumen, 0, ',', '.') }} dokumen</strong></div>
                    </article>

                    <article class="panel">
                        <div class="panel-heading">
                            <div><h2>Unggahan per Bulan</h2><p class="panel-subtitle">Tren unggahan dokumen dan SK selama 12 bulan</p></div>
                            <div class="chart-summary"><span>Rata-rata</span><strong>{{ number_format($rataRataUnggahan, 0, ',', '.') }} Dok/Bulan</strong></div>
                        </div>
                        <div class="chart-wrap">
                            <svg class="upload-chart" viewBox="0 0 600 180" role="img" aria-label="Grafik unggahan per bulan, total {{ number_format($uploadsPerBulan->sum('total'), 0, ',', '.') }} dokumen dalam dua belas bulan terakhir">
                                <defs><linearGradient id="chart-fill" x1="0" x2="0" y1="0" y2="1"><stop offset="0%" stop-color="#2168d6" stop-opacity=".23"/><stop offset="100%" stop-color="#2168d6" stop-opacity=".015"/></linearGradient></defs>
                                <path class="chart-gridline" d="M12 48H588M12 108H588M12 168H588"/>
                                <polygon class="chart-area" points="{{ $chartArea }}"/>
                                <polyline class="chart-line" points="{{ $chartLine }}"/>
                                @if ($uploadsPerBulan->isNotEmpty())
                                    @php $lastPoint = explode(',', $chartPoints->last()); @endphp
                                    <circle class="chart-point" cx="{{ $lastPoint[0] }}" cy="{{ $lastPoint[1] }}" r="4"/>
                                @endif
                            </svg>
                            <div class="chart-labels">
                                @foreach ($uploadsPerBulan as $month)
                                    <span>{{ $month['label'] }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="chart-footer"><span class="chart-footer-dot"></span><span>Total unggahan 12 bulan:</span><strong>{{ number_format($uploadsPerBulan->sum('total'), 0, ',', '.') }} dokumen &amp; SK</strong></div>
                    </article>
                </section>

                <section class="tables-grid" aria-label="Dokumen terbaru">
                    <article class="table-panel">
                        <div class="table-panel-heading">
                            <div class="table-title"><span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 3h8l5 5v13H6z"/><path d="M14 3v6h5M9 14h7m-7 3h5"/></svg></span><span><h2>Dokumen Terbaru</h2><p>Registrasi berkas audit SPI termutakhir</p></span></div>
                            <span class="view-all" aria-disabled="true">Lihat Semua <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span>
                        </div>
                        @if ($search !== '')
                            <p class="search-result">Hasil pencarian dokumen untuk “{{ $search }}”</p>
                        @endif
                        <div class="table-scroll">
                            <table>
                                <thead><tr><th>NOMOR BERKAS</th><th>JUDUL DOKUMEN</th><th>KATEGORI</th><th>TANGGAL</th></tr></thead>
                                <tbody>
                                    @forelse ($dokumenTerbaru as $dokumen)
                                        <tr>
                                            <td><span class="cell-clip">{{ $dokumen->nomor_dokumen ?: '—' }}</span></td>
                                            <td><span class="cell-clip" title="{{ $dokumen->judul }}">{{ $dokumen->judul }}</span></td>
                                            <td><span class="category-badge">{{ $dokumen->kategori?->nama_kategori ?? 'Tanpa kategori' }}</span></td>
                                            <td>{{ $dokumen->tanggal_dokumen?->format('d M Y') ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td class="empty-cell" colspan="4">{{ $search !== '' ? 'Tidak ada dokumen yang cocok.' : 'Belum ada dokumen untuk ditampilkan.' }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>

                    <article class="table-panel">
                        <div class="table-panel-heading">
                            <div class="table-title"><span class="stat-icon teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 3h12v18H6z"/><path d="M9 7h6m-6 4h6m-6 4h4"/></svg></span><span><h2>SK Terbaru</h2><p>Surat Keputusan Direksi PDAM</p></span></div>
                            <span class="view-all" aria-disabled="true">Lihat Semua SK <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14m-6-6 6 6-6 6"/></svg></span>
                        </div>
                        @if ($search !== '')
                            <p class="search-result">Hasil pencarian SK untuk “{{ $search }}”</p>
                        @endif
                        <div class="table-scroll">
                            <table>
                                <thead><tr><th>NOMOR SK</th><th>JUDUL KEPUTUSAN</th><th>TANGGAL</th></tr></thead>
                                <tbody>
                                    @forelse ($skTerbaru as $sk)
                                        <tr>
                                            <td><span class="cell-clip">{{ $sk->nomor_sk }}</span></td>
                                            <td><span class="cell-clip" title="{{ $sk->judul }}">{{ $sk->judul }}</span></td>
                                            <td>{{ $sk->tanggal_penetapan?->format('d M Y') ?? '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td class="empty-cell" colspan="3">{{ $search !== '' ? 'Tidak ada SK yang cocok.' : 'Belum ada SK untuk ditampilkan.' }}</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </article>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
