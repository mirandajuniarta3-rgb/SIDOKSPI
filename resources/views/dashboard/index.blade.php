@extends('layouts.app')

@section('title', 'Dashboard Admin - SIDOKSPI')
@section('active-menu', 'dashboard')
@section('breadcrumb', 'Beranda Administrasi')
@section('search-action', route('dashboard'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/dashboard.css') }}">
@endpush

@php
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

@section('content')
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
                        <a id="unggah-dokumen" class="primary-button" href="{{ route('dokumen.create') }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 16V4m-5 5 5-5 5 5M4 16v4h16v-4"/></svg>
                            Unggah Dokumen
                        </a>
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

                <section id="dokumen-terbaru" class="tables-grid" aria-label="Dokumen terbaru">
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

@endsection
