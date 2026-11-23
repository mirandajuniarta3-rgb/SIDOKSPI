@extends('layouts.app')

@section('title', 'Dashboard Admin - SIDOKSPI')
@section('active-menu', 'dashboard')
@section('breadcrumb', 'Beranda Administrasi')
@section('search-action', route('dashboard'))

@php
    $chartMaximum = max(1, (int) $uploadsPerBulan->max('total'));

    $chartPoints = $uploadsPerBulan->values()->map(function ($month, $index) use ($uploadsPerBulan, $chartMaximum) {
        $x = 12 + ($index * 576 / max(1, $uploadsPerBulan->count() - 1));
        $y = 158 - ((int) $month['total'] / $chartMaximum * 120);

        return round($x, 1) . ',' . round($y, 1);
    });

    $chartLine = $chartPoints->implode(' ');
    $chartArea = $chartPoints->isNotEmpty()
        ? '12,168 ' . $chartLine . ' 588,168'
        : '';

    $activeCategoryCount = $kategori->where('dokumen_count', '>', 0)->count();
    $periodText = now()->locale('id')->translatedFormat('F Y');
@endphp

@push('styles')
<style>
    .admin-dashboard {
        display: flex;
        flex-direction: column;
        gap: 20px;
        color: #263650;
    }

    .admin-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        flex-wrap: wrap;
    }

    .admin-eyebrow {
        color: #159b83;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 1px;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .admin-heading h1 {
        margin: 0;
        color: #17243b;
        font-size: 25px;
        font-weight: 800;
    }

    .admin-description {
        margin: 7px 0 0;
        color: #8290a8;
        font-size: 12px;
        line-height: 1.6;
    }

    .admin-heading-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    .admin-period {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 13px;
        border-radius: 9px;
        background: #fff;
        border: 1px solid #e8edf6;
        color: #53627d;
        font-size: 11px;
        font-weight: 650;
    }

    .admin-period svg,
    .admin-button svg {
        width: 16px;
        height: 16px;
    }

    .admin-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 11px 15px;
        border-radius: 9px;
        background: #2168d6;
        color: #fff;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: .2s;
    }

    .admin-button:hover {
        background: #1555ba;
        color: #fff;
        transform: translateY(-1px);
    }

    /* STATISTIK */

    .admin-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
    }

    .admin-stat-card {
        min-width: 0;
        background: #fff;
        border: 1px solid #e9edf5;
        border-radius: 13px;
        padding: 17px;
        box-shadow: 0 3px 12px rgba(28, 48, 85, .025);
    }

    .admin-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .admin-stat-label {
        color: #78869e;
        font-size: 11px;
        font-weight: 650;
    }

    .admin-stat-icon {
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        background: #edf3ff;
        color: #2168d6;
    }

    .admin-stat-icon svg {
        width: 19px;
        height: 19px;
    }

    .admin-stat-icon.green {
        background: #e7f8f3;
        color: #159b83;
    }

    .admin-stat-icon.purple {
        background: #f0edff;
        color: #7655c9;
    }

    .admin-stat-icon.orange {
        background: #fff4e6;
        color: #d58a22;
    }

    .admin-stat-value {
        display: block;
        margin-top: 12px;
        color: #17243b;
        font-size: 27px;
        line-height: 1.2;
        font-weight: 850;
    }

    .admin-stat-foot {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 9px;
        color: #8b97aa;
        font-size: 10px;
    }

    .admin-stat-foot strong {
        color: #159b83;
        font-weight: 750;
    }

    /* PANEL ANALISIS */

    .admin-analytics {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        gap: 16px;
    }

    .admin-panel {
        min-width: 0;
        padding: 19px;
        background: #fff;
        border: 1px solid #e9edf5;
        border-radius: 13px;
        box-shadow: 0 3px 12px rgba(28, 48, 85, .025);
    }

    .admin-panel-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 19px;
    }

    .admin-panel-heading h2 {
        margin: 0;
        color: #263650;
        font-size: 14px;
        font-weight: 800;
    }

    .admin-panel-heading p {
        margin: 5px 0 0;
        color: #8b97aa;
        font-size: 10px;
        line-height: 1.5;
    }

    .admin-panel-tag {
        flex-shrink: 0;
        padding: 6px 9px;
        background: #edf4ff;
        color: #2168d6;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 750;
    }

    /* KATEGORI */

    .admin-category-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .admin-category-row {
        display: flex;
        flex-direction: column;
        gap: 7px;
    }

    .admin-category-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        font-size: 10px;
    }

    .admin-category-name {
        color: #44536d;
        font-weight: 650;
        overflow-wrap: anywhere;
    }

    .admin-category-count {
        color: #7d8aa0;
        white-space: nowrap;
    }

    .admin-progress-track {
        height: 7px;
        overflow: hidden;
        border-radius: 20px;
        background: #edf1f7;
    }

    .admin-progress-value {
        height: 100%;
        border-radius: 20px;
        background: #2168d6;
    }

    .admin-progress-value.alt-1 { background: #159b83; }
    .admin-progress-value.alt-2 { background: #398baf; }
    .admin-progress-value.alt-3 { background: #7655c9; }
    .admin-progress-value.alt-4 { background: #d58a22; }

    .admin-empty {
        padding: 25px 10px;
        text-align: center;
        color: #8b97aa;
        font-size: 11px;
    }

    .admin-panel-footer {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 7px;
        margin-top: 19px;
        padding: 11px;
        border-radius: 8px;
        background: #f5f7fc;
        color: #718099;
        font-size: 10px;
    }

    .admin-panel-footer strong {
        color: #34445e;
    }

    .admin-footer-dot {
        width: 7px;
        height: 7px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #159b83;
    }

    /* GRAFIK */

    .admin-chart-summary {
        text-align: right;
    }

    .admin-chart-summary span {
        display: block;
        color: #8b97aa;
        font-size: 9px;
    }

    .admin-chart-summary strong {
        display: block;
        margin-top: 4px;
        color: #2168d6;
        font-size: 12px;
    }

    .admin-chart-wrap {
        width: 100%;
        overflow: hidden;
    }

    .admin-upload-chart {
        display: block;
        width: 100%;
        height: auto;
        min-height: 140px;
    }

    .admin-chart-gridline {
        fill: none;
        stroke: #edf1f7;
        stroke-width: 1;
        stroke-dasharray: 4 5;
    }

    .admin-chart-area {
        fill: url(#admin-chart-fill);
    }

    .admin-chart-line {
        fill: none;
        stroke: #2168d6;
        stroke-width: 3;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .admin-chart-point {
        fill: #fff;
        stroke: #2168d6;
        stroke-width: 3;
    }

    .admin-chart-labels {
        display: grid;
        grid-template-columns: repeat(12, minmax(0, 1fr));
        gap: 2px;
        margin-top: 5px;
        color: #8b97aa;
        font-size: 9px;
        text-align: center;
    }

    /* TABEL */

    .admin-tables {
        display: grid;
        grid-template-columns: minmax(0, 1.2fr) minmax(0, .9fr);
        gap: 16px;
    }

    .admin-table-panel {
        min-width: 0;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e9edf5;
        border-radius: 13px;
        box-shadow: 0 3px 12px rgba(28, 48, 85, .025);
    }

    .admin-table-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding: 17px 18px;
        border-bottom: 1px solid #edf1f7;
    }

    .admin-table-title {
        display: flex;
        align-items: center;
        gap: 10px;
        min-width: 0;
    }

    .admin-table-title-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 34px;
        height: 34px;
        flex-shrink: 0;
        border-radius: 9px;
        background: #edf4ff;
        color: #2168d6;
    }

    .admin-table-title-icon.green {
        background: #e7f8f3;
        color: #159b83;
    }

    .admin-table-title-icon svg {
        width: 17px;
        height: 17px;
    }

    .admin-table-title h2 {
        margin: 0;
        color: #263650;
        font-size: 12px;
        font-weight: 800;
    }

    .admin-table-title p {
        margin: 4px 0 0;
        color: #8b97aa;
        font-size: 9px;
    }

    .admin-view-all {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        flex-shrink: 0;
        color: #2168d6;
        text-decoration: none;
        font-size: 10px;
        font-weight: 750;
    }

    .admin-view-all:hover {
        color: #1555ba;
    }

    .admin-table-scroll {
        width: 100%;
        overflow-x: auto;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10px;
    }

    .admin-table th {
        padding: 11px 12px;
        background: #f7f9fc;
        color: #7d8aa0;
        font-size: 9px;
        font-weight: 750;
        text-align: left;
        white-space: nowrap;
    }

    .admin-table td {
        padding: 12px;
        border-bottom: 1px solid #edf1f7;
        color: #465570;
        vertical-align: middle;
    }

    .admin-table tr:last-child td {
        border-bottom: none;
    }

    .admin-cell-clip {
        display: block;
        max-width: 180px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .admin-category-badge {
        display: inline-block;
        padding: 5px 8px;
        border-radius: 6px;
        background: #edf4ff;
        color: #2168d6;
        font-size: 9px;
        font-weight: 700;
        white-space: nowrap;
    }

    .admin-search-result {
        margin: 12px 15px 0;
        color: #7d8aa0;
        font-size: 10px;
    }

    .admin-table-empty {
        padding: 25px 12px !important;
        text-align: center;
        color: #8b97aa !important;
    }

    /* RESPONSIVE */

    @media (max-width: 1100px) {
        .admin-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .admin-tables {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 750px) {
        .admin-analytics {
            grid-template-columns: 1fr;
        }

        .admin-heading h1 {
            font-size: 21px;
        }
    }

    @media (max-width: 520px) {
        .admin-stats {
            grid-template-columns: 1fr 1fr;
            gap: 9px;
        }

        .admin-stat-card {
            padding: 13px;
        }

        .admin-stat-value {
            font-size: 22px;
        }

        .admin-stat-label {
            font-size: 10px;
        }

        .admin-panel {
            padding: 14px;
        }

        .admin-table-heading {
            padding: 13px;
        }

        .admin-heading-actions {
            width: 100%;
        }

        .admin-period,
        .admin-button {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')
<div class="admin-dashboard">

    {{-- JUDUL DASHBOARD --}}
    <section class="admin-heading">
        <div>
            <div class="admin-eyebrow">
                Sistem Pengawasan Internal Terintegrasi
            </div>

            <h1>Dashboard Utama SPI</h1>

            <p class="admin-description">
                Ringkasan pengawasan, kepatuhan, dan pengelolaan arsip digital
                PDAM Tirta Pinang.
            </p>
        </div>

        <div class="admin-heading-actions">
            <span class="admin-period">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="5" width="18" height="16" rx="2"/>
                    <path d="M16 3v4M8 3v4M3 10h18"/>
                </svg>
                Periode: {{ $periodText }}
            </span>

            <a href="{{ route('dokumen.create') }}" class="admin-button">
                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2">
                    <path d="M12 16V4m-5 5 5-5 5 5M4 16v4h16v-4"/>
                </svg>
                Unggah Dokumen
            </a>
        </div>
    </section>

    {{-- KARTU STATISTIK ADMIN --}}
    <section class="admin-stats">

        <article class="admin-stat-card">
            <div class="admin-stat-top">
                <span class="admin-stat-label">Total Dokumen</span>

                <span class="admin-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path d="M4 6h6l2 2h8v11H4z"/>
                        <path d="M4 8h16"/>
                    </svg>
                </span>
            </div>

            <strong class="admin-stat-value">
                {{ number_format($totalDokumen, 0, ',', '.') }}
            </strong>

            <div class="admin-stat-foot">
                <strong>Seluruh data</strong>
                <span>dokumen terdaftar</span>
            </div>
        </article>

        <article class="admin-stat-card">
            <div class="admin-stat-top">
                <span class="admin-stat-label">Total SK Direksi</span>

                <span class="admin-stat-icon green">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path d="M6 3h12v18H6z"/>
                        <path d="M9 7h6m-6 4h6m-6 4h4"/>
                    </svg>
                </span>
            </div>

            <strong class="admin-stat-value">
                {{ number_format($totalSk, 0, ',', '.') }}
            </strong>

            <div class="admin-stat-foot">
                <strong>{{ number_format($skArsip, 0, ',', '.') }} arsip</strong>
                <span>surat keputusan</span>
            </div>
        </article>

        <article class="admin-stat-card">
            <div class="admin-stat-top">
                <span class="admin-stat-label">Dokumen Bulan Ini</span>

                <span class="admin-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <path d="M4 18 9 12l4 3 7-9"/>
                        <path d="M14 6h6v6"/>
                    </svg>
                </span>
            </div>

            <strong class="admin-stat-value">
                {{ number_format($dokumenBulanIni, 0, ',', '.') }}
            </strong>

            <div class="admin-stat-foot">
                <strong>{{ now()->locale('id')->translatedFormat('F') }}</strong>
                <span>unggahan dokumen</span>
            </div>
        </article>

        <article class="admin-stat-card">
            <div class="admin-stat-top">
                <span class="admin-stat-label">Dokumen Diarsipkan</span>

                <span class="admin-stat-icon purple">
                    <svg viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.8">
                        <rect x="4" y="4" width="16" height="16" rx="2"/>
                        <path d="M8 8h8m-8 4h8m-8 4h5"/>
                    </svg>
                </span>
            </div>

            <strong class="admin-stat-value">
                {{ number_format($dokumenDiarsipkan, 0, ',', '.') }}
            </strong>

            <div class="admin-stat-foot">
                <strong>Status arsip</strong>
                <span>dokumen tersimpan</span>
            </div>
        </article>

    </section>

    {{-- ANALISIS KATEGORI DAN GRAFIK --}}
    <section class="admin-analytics">

        {{-- KATEGORI --}}
        <article class="admin-panel">
            <div class="admin-panel-heading">
                <div>
                    <h2>Dokumen per Kategori</h2>
                    <p>
                        Klasifikasi repositori pengawasan operasional
                        dan manajerial
                    </p>
                </div>

                <span class="admin-panel-tag">
                    {{ $activeCategoryCount }} Kategori Aktif
                </span>
            </div>

            @if ($kategori->isNotEmpty())
                <div class="admin-category-list">
                    @foreach ($kategori->take(6) as $index => $item)
                        @php
                            $categoryWidth = $item->dokumen_count > 0
                                ? max(4, ($item->dokumen_count / $kategoriTerbanyak) * 100)
                                : 0;
                        @endphp

                        <div class="admin-category-row">
                            <div class="admin-category-meta">
                                <span class="admin-category-name">
                                    {{ $item->nama_kategori }}
                                </span>

                                <span class="admin-category-count">
                                    {{ number_format($item->dokumen_count, 0, ',', '.') }}
                                    dok
                                </span>
                            </div>

                            <div class="admin-progress-track">
                                <div
                                    class="admin-progress-value alt-{{ $index % 5 }}"
                                    style="width: {{ $categoryWidth }}%">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="admin-empty">
                    Belum ada kategori dokumen.
                </div>
            @endif

            <div class="admin-panel-footer">
                <span class="admin-footer-dot"></span>
                <span>Total dokumen terdaftar:</span>
                <strong>
                    {{ number_format($totalDokumen, 0, ',', '.') }} dokumen
                </strong>
            </div>
        </article>

        {{-- GRAFIK --}}
        <article class="admin-panel">
            <div class="admin-panel-heading">
                <div>
                    <h2>Unggahan per Bulan</h2>
                    <p>Tren unggahan dokumen dan SK selama 12 bulan</p>
                </div>

                <div class="admin-chart-summary">
                    <span>Rata-rata</span>
                    <strong>
                        {{ number_format($rataRataUnggahan, 1, ',', '.') }}
                        Dok/Bulan
                    </strong>
                </div>
            </div>

            <div class="admin-chart-wrap">
                <svg class="admin-upload-chart"
                     viewBox="0 0 600 180"
                     role="img"
                     aria-label="Grafik unggahan dokumen dan SK">

                    <defs>
                        <linearGradient id="admin-chart-fill"
                                        x1="0" x2="0" y1="0" y2="1">
                            <stop offset="0%"
                                  stop-color="#2168d6"
                                  stop-opacity=".23"/>
                            <stop offset="100%"
                                  stop-color="#2168d6"
                                  stop-opacity=".015"/>
                        </linearGradient>
                    </defs>

                    <path class="admin-chart-gridline"
                          d="M12 48H588M12 108H588M12 168H588"/>

                    @if ($chartPoints->isNotEmpty())
                        <polygon class="admin-chart-area"
                                 points="{{ $chartArea }}"/>

                        <polyline class="admin-chart-line"
                                  points="{{ $chartLine }}"/>

                        @php
                            $lastPoint = explode(',', $chartPoints->last());
                        @endphp

                        <circle class="admin-chart-point"
                                cx="{{ $lastPoint[0] }}"
                                cy="{{ $lastPoint[1] }}"
                                r="5"/>
                    @endif
                </svg>

                <div class="admin-chart-labels">
                    @foreach ($uploadsPerBulan as $month)
                        <span>{{ $month['label'] }}</span>
                    @endforeach
                </div>
            </div>

            <div class="admin-panel-footer">
                <span class="admin-footer-dot"></span>
                <span>Total unggahan 12 bulan:</span>
                <strong>
                    {{ number_format($uploadsPerBulan->sum('total'), 0, ',', '.') }}
                    dokumen &amp; SK
                </strong>
            </div>
        </article>

    </section>

    {{-- DOKUMEN TERBARU DAN SK TERBARU --}}
    <section class="admin-tables">

        {{-- DOKUMEN TERBARU --}}
        <article class="admin-table-panel">
            <div class="admin-table-heading">
                <div class="admin-table-title">
                    <span class="admin-table-title-icon">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path d="M6 3h8l5 5v13H6z"/>
                            <path d="M14 3v6h5M9 14h7m-7 3h5"/>
                        </svg>
                    </span>

                    <span>
                        <h2>Dokumen Terbaru</h2>
                        <p>Registrasi berkas audit SPI termutakhir</p>
                    </span>
                </div>

                <a href="{{ route('dokumen.index') }}"
                   class="admin-view-all">
                    Lihat Semua →
                </a>
            </div>

            @if ($search !== '')
                <p class="admin-search-result">
                    Hasil pencarian dokumen untuk "{{ $search }}"
                </p>
            @endif

            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>NOMOR BERKAS</th>
                            <th>JUDUL DOKUMEN</th>
                            <th>KATEGORI</th>
                            <th>TANGGAL</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($dokumenTerbaru as $dokumen)
                            <tr>
                                <td>
                                    <span class="admin-cell-clip">
                                        {{ $dokumen->nomor_dokumen ?: '—' }}
                                    </span>
                                </td>

                                <td>
                                    <span class="admin-cell-clip"
                                          title="{{ $dokumen->judul }}">
                                        {{ $dokumen->judul }}
                                    </span>
                                </td>

                                <td>
                                    <span class="admin-category-badge">
                                        {{ $dokumen->kategori?->nama_kategori ?? 'Tanpa kategori' }}
                                    </span>
                                </td>

                                <td>
                                    {{ $dokumen->tanggal_dokumen?->format('d M Y') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="admin-table-empty">
                                    Belum ada dokumen untuk ditampilkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        {{-- SK TERBARU --}}
        <article class="admin-table-panel">
            <div class="admin-table-heading">
                <div class="admin-table-title">
                    <span class="admin-table-title-icon green">
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8">
                            <path d="M6 3h12v18H6z"/>
                            <path d="M9 7h6m-6 4h6m-6 4h4"/>
                        </svg>
                    </span>

                    <span>
                        <h2>SK Terbaru</h2>
                        <p>Surat Keputusan Direksi PDAM</p>
                    </span>
                </div>

                <a href="{{ route('sk.index') }}"
                   class="admin-view-all">
                    Lihat Semua SK →
                </a>
            </div>

            @if ($search !== '')
                <p class="admin-search-result">
                    Hasil pencarian SK untuk "{{ $search }}"
                </p>
            @endif

            <div class="admin-table-scroll">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>NOMOR SK</th>
                            <th>JUDUL KEPUTUSAN</th>
                            <th>TANGGAL</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($skTerbaru as $sk)
                            <tr>
                                <td>
                                    <span class="admin-cell-clip">
                                        {{ $sk->nomor_sk }}
                                    </span>
                                </td>

                                <td>
                                    <span class="admin-cell-clip"
                                          title="{{ $sk->judul }}">
                                        {{ $sk->judul }}
                                    </span>
                                </td>

                                <td>
                                    {{ $sk->tanggal_penetapan?->format('d M Y') ?? '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="admin-table-empty">
                                    Belum ada SK untuk ditampilkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

    </section>

</div>
@endsection
