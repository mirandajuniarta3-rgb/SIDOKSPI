@extends('layouts.app')

@section('title', 'Dashboard Admin - SIDOKSPI')
@section('active-menu', 'dashboard')
@section('breadcrumb', 'Beranda Administrasi')
@section('search-action', route('dashboard'))

@php
    $chartMaximum = max(4, (int) ceil($uploadsPerBulan->max('total') / 4) * 4);
    $chartMonths = $uploadsPerBulan->values();
    $chartMonthCount = max(1, $chartMonths->count());

    $activeCategoryCount = $kategori->where('dokumen_count', '>', 0)->count();
    $categoryColors = ['#2168d6', '#159b83', '#398baf', '#7655c9', '#d58a22', '#e05d79', '#0891b2', '#64748b'];
    $categoryChartData = $kategori->values()->map(fn ($item, $index) => [
        'name' => $item->nama_kategori,
        'total' => (int) $item->dokumen_count,
        'color' => $categoryColors[$index % count($categoryColors)],
    ]);
    $categoryChartTotal = $categoryChartData->sum('total');
    $periodText = now()->locale('id')->translatedFormat('F Y');
@endphp

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/dashboard-admin.css') }}">
@endpush

@section('content')
<div class="admin-dashboard">

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

    <section class="admin-analytics">

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
                <svg class="admin-upload-chart" viewBox="0 0 600 300" role="group" aria-label="Jumlah unggahan dokumen dan SK per bulan">
                    @for ($tick = 0; $tick <= 4; $tick++)
                        @php $gridY = 250 - $tick * 52; @endphp
                        <line class="admin-chart-gridline" x1="48" x2="588" y1="{{ $gridY }}" y2="{{ $gridY }}" />
                        <text class="admin-chart-text" x="40" y="{{ $gridY + 4 }}" text-anchor="end">{{ number_format($chartMaximum * $tick / 4, 0, ',', '.') }}</text>
                    @endfor
                    @foreach ($chartMonths as $index => $month)
                        @php
                            $x = 48 + ($index + .5) * 540 / $chartMonthCount;
                            $count = (int) $month['total'];
                            $y = 250 - $count / $chartMaximum * 208;
                        @endphp
                        <line class="admin-stick-track" x1="{{ $x }}" x2="{{ $x }}" y1="42" y2="250" />
                        @if ($count > 0)
                            <line class="admin-stick" x1="{{ $x }}" x2="{{ $x }}" y1="{{ $y }}" y2="250" tabindex="0" aria-label="{{ $month['label'] }}: {{ $count }} unggahan">
                                <title>{{ $month['label'] }}: {{ number_format($count, 0, ',', '.') }} unggahan</title>
                            </line>
                        @endif
                        <text class="admin-chart-number" x="{{ $x }}" y="{{ $y - 10 }}" text-anchor="middle">{{ $count }}</text>
                        <text class="admin-chart-text" x="{{ $x }}" y="275" text-anchor="middle">{{ $month['label'] }}</text>
                    @endforeach
                </svg>
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

            <div class="admin-donut-layout" id="adminCategoryDonut">
                <div class="admin-donut-chart">
                    <svg viewBox="0 0 240 240" role="group" aria-label="Diagram dokumen per kategori">
                        <circle cx="120" cy="120" r="91" fill="none" stroke="#edf1f7" stroke-width="39" />
                        <g data-segments></g>
                        <g data-labels pointer-events="none"></g>
                    </svg>
                    <div class="admin-donut-center">
                        <span>Total Dokumen</span>
                        <strong>{{ number_format($totalDokumen, 0, ',', '.') }}</strong>
                        <span data-visible aria-live="polite">{{ number_format($categoryChartTotal, 0, ',', '.') }} ditampilkan</span>
                    </div>
                    <div class="admin-donut-tooltip" data-tooltip hidden></div>
                </div>
                <div class="admin-donut-legend">
                    @forelse ($categoryChartData as $item)
                        <label class="admin-donut-row">
                            <input type="checkbox" checked>
                            <span class="admin-donut-color" style="background: {{ $item['color'] }}"></span>
                            <span class="admin-donut-name">{{ $item['name'] }}</span>
                            <strong>{{ number_format($item['total'], 0, ',', '.') }}</strong>
                            <span class="admin-donut-percent">{{ number_format($categoryChartTotal > 0 ? $item['total'] / $categoryChartTotal * 100 : 0, 1, ',', '.') }}%</span>
                        </label>
                    @empty
                        <div class="admin-empty">Belum ada kategori dokumen.</div>
                    @endforelse
                    @if ($categoryChartData->isNotEmpty())
                        <button type="button" class="admin-donut-reset">Tampilkan Semua Kategori</button>
                    @endif
                </div>
            </div>

            <div class="admin-panel-footer">
                <span class="admin-footer-dot"></span>
                <span>Total dokumen terdaftar:</span>
                <strong>
                    {{ number_format($totalDokumen, 0, ',', '.') }} dokumen
                </strong>
            </div>
        </article>

    </section>

</div>
<script>
    (() => {
        const init = () => {
            const root = document.getElementById('adminCategoryDonut');
            if (!root) return;
            const data = {{ Illuminate\Support\Js::from($categoryChartData) }};
            const checks = Array.from(root.querySelectorAll('input[type="checkbox"]'));
            const segments = root.querySelector('[data-segments]');
            const labels = root.querySelector('[data-labels]');
            const tooltip = root.querySelector('[data-tooltip]');
            const visible = root.querySelector('[data-visible]');
            const format = new Intl.NumberFormat('id-ID');
            const percent = new Intl.NumberFormat('id-ID', { maximumFractionDigits: 1 });
            const circumference = 2 * Math.PI * 91;
            const originalTotal = data.reduce((sum, item) => sum + item.total, 0);
            const element = (tag, attrs) => {
                const node = document.createElementNS('http://www.w3.org/2000/svg', tag);
                Object.entries(attrs).forEach(([key, value]) => node.setAttribute(key, value));
                return node;
            };
            const hide = () => { tooltip.hidden = true; };
            const render = () => {
                segments.replaceChildren();
                labels.replaceChildren();
                hide();
                checks.forEach(check => check.closest('label').classList.toggle('is-disabled', !check.checked));
                const active = data.filter((item, index) => checks[index].checked && item.total > 0);
                const total = active.reduce((sum, item) => sum + item.total, 0);
                visible.textContent = format.format(total) + ' ditampilkan';
                if (!total) return;
                let offset = 0;
                active.forEach(item => {
                    const length = item.total / total * circumference;
                    const gap = active.length > 1 ? Math.min(3, length * .2) : 0;
                    const description = item.name + ': ' + format.format(item.total) + ' dokumen (' + percent.format(item.total / originalTotal * 100) + '%)';
                    const circle = element('circle', {
                        cx: 120, cy: 120, r: 91, fill: 'none', stroke: item.color,
                        'stroke-width': 39, 'stroke-dasharray': `${length - gap} ${circumference}`,
                        'stroke-dashoffset': -offset, transform: 'rotate(-90 120 120)',
                        class: 'admin-donut-segment', tabindex: 0, 'aria-label': description
                    });
                    const show = () => { tooltip.textContent = description; tooltip.hidden = false; };
                    ['pointerenter', 'focus', 'click'].forEach(event => circle.addEventListener(event, show));
                    ['pointerleave', 'blur'].forEach(event => circle.addEventListener(event, hide));
                    segments.appendChild(circle);
                    if (item.total / total >= .07) {
                        const angle = (offset + length / 2) / circumference * 2 * Math.PI - Math.PI / 2;
                        const label = element('text', {
                            x: 120 + 91 * Math.cos(angle), y: 120 + 91 * Math.sin(angle),
                            fill: '#fff', 'font-size': 12, 'font-weight': 750,
                            'text-anchor': 'middle', 'dominant-baseline': 'central'
                        });
                        label.textContent = format.format(item.total);
                        labels.appendChild(label);
                    }
                    offset += length;
                });
            };
            checks.forEach(check => check.addEventListener('change', render));
            root.querySelector('.admin-donut-reset')?.addEventListener('click', () => {
                checks.forEach(check => { check.checked = true; });
                render();
            });
            document.addEventListener('pointerdown', event => {
                if (!root.querySelector('.admin-donut-chart').contains(event.target)) hide();
            });
            render();
        };
        if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init, { once: true });
        else init();
    })();
</script>
@endsection
