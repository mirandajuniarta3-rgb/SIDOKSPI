@php
    $user = auth()->user();
    $role = strtolower(trim($user->role ?? ''));

    $dashboardRoute = match ($role) {
        'admin' => 'dashboard.admin',
        'staf_spi' => 'dashboard.staf',
        default => 'dashboard',
    };

    $isAdmin = $role === 'admin';
@endphp

{{-- Terapkan status sidebar sebelum tampil, supaya tidak berkedip --}}
<script>
    try {
        if (localStorage.getItem('sidokspi_sidebar_open') === 'true') {
            document.body.classList.add('sidebar-open');
        }
    } catch (e) {}
</script>

<aside class="sidebar" aria-label="Navigasi utama">

    {{-- LOGO (selalu terlihat di pojok kiri atas) --}}
    <a class="brand" href="{{ route($dashboardRoute) }}">
        <span class="brand-mark">
            <img src="{{ asset('asset/img/logo-pdam.png') }}" alt="Logo PDAM Tirta Pinang">
        </span>

        <span class="brand-text">
            <span class="brand-name">SIDOKSPI</span>
            <span class="brand-caption">SPI PDAM Tirta Pinang</span>
        </span>
    </a>

    {{-- MENU NAVIGASI (muncul saat tombol tiga garis di topbar diklik) --}}
    <div class="sidebar-navigation" id="sidebarNavigation">

        <p class="nav-label">MENU NAVIGASI</p>

        <nav aria-label="Menu dashboard">
            <ul class="nav-list">

                {{-- DASHBOARD --}}
                <li>
                    <a
                        class="nav-link {{ request()->routeIs('dashboard', 'dashboard.admin', 'dashboard.staf', 'home') ? 'active' : '' }}"
                        href="{{ route($dashboardRoute) }}"
                    >
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <rect x="3" y="3" width="7" height="7" rx="1"/>
                            <rect x="14" y="3" width="7" height="7" rx="1"/>
                            <rect x="3" y="14" width="7" height="7" rx="1"/>
                            <rect x="14" y="14" width="7" height="7" rx="1"/>
                        </svg>
                        Dashboard
                    </a>
                </li>

                {{-- DOKUMEN --}}
                <li>
                    <details class="nav-dropdown" {{ request()->routeIs('dokumen.*') ? 'open' : '' }}>
                        <summary class="nav-link nav-dropdown-toggle {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M3 7h7l2 2h9v10H3z"/>
                                <path d="M3 7V5h7l2 2"/>
                            </svg>

                            <span>Dokumen</span>

                            <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </summary>

                        <ul class="nav-sublist">
                            <li>
                                <a class="nav-sublink {{ request()->routeIs('dokumen.index') ? 'active' : '' }}"
                                   href="{{ route('dokumen.index') }}">
                                    Daftar Dokumen
                                </a>
                            </li>

                            <li>
                                <a class="nav-sublink {{ request()->routeIs('dokumen.create') ? 'active' : '' }}"
                                   href="{{ route('dokumen.create') }}">
                                    Unggah Dokumen
                                </a>
                            </li>

                            <li>
                                <a class="nav-sublink {{ request()->routeIs('dokumen.arsip') ? 'active' : '' }}"
                                   href="{{ route('dokumen.arsip') }}">
                                    Arsip
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                {{-- SURAT KEPUTUSAN --}}
                <li>
                    <details class="nav-dropdown" {{ request()->routeIs('sk.*') ? 'open' : '' }}>
                        <summary class="nav-link nav-dropdown-toggle {{ request()->routeIs('sk.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M6 3h12v18H6zM9 7h6m-6 4h6m-6 4h4"/>
                            </svg>

                            <span>Surat Keputusan (SK)</span>

                            <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </summary>

                        <ul class="nav-sublist">
                            <li>
                                <a class="nav-sublink {{ request()->routeIs('sk.index') ? 'active' : '' }}"
                                   href="{{ route('sk.index') }}">
                                    Daftar SK
                                </a>
                            </li>

                            <li>
                                <a class="nav-sublink {{ request()->routeIs('sk.create') ? 'active' : '' }}"
                                   href="{{ route('sk.create') }}">
                                    Tambah SK
                                </a>
                            </li>

                            <li>
                                <a class="nav-sublink {{ request()->routeIs('sk.arsip') ? 'active' : '' }}"
                                   href="{{ route('sk.arsip') }}">
                                    Arsip SK
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

                {{-- LAPORAN --}}
                <li>
                    <details class="nav-dropdown" {{ request()->routeIs('laporan.*') ? 'open' : '' }}>
                        <summary class="nav-link nav-dropdown-toggle {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 20V4m0 16h17M8 16v-4m5 4V7m5 9v-6"/>
                            </svg>

                            <span>Laporan</span>

                            <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </summary>

                        <ul class="nav-sublist">
                            <li>
                                <a class="nav-sublink {{ request()->routeIs('laporan.dokumen') ? 'active' : '' }}"
                                   href="{{ route('laporan.dokumen') }}">
                                    Rekap Dokumen
                                </a>
                            </li>

                            <li>
                                <a class="nav-sublink {{ request()->routeIs('laporan.sk') ? 'active' : '' }}"
                                   href="{{ route('laporan.sk') }}">
                                    Rekap SK
                                </a>
                            </li>
                        </ul>
                    </details>
                </li>

            </ul>

            {{-- MASTER DATA --}}
            @if ($isAdmin)
                <p class="nav-label">MASTER DATA</p>

                <ul class="nav-list">
                    <li>
                        <a href="{{ route('kategori.index') }}"
                           class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M20 12 12 20l-9-9V4h7l10 8Z"/>
                                <circle cx="7.5" cy="8" r="1"/>
                            </svg>
                            <span>Kategori Dokumen</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('pengguna.index') }}"
                           class="nav-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="9" cy="8" r="4"/>
                                <path d="M2 21v-2a7 7 0 0 1 14 0v2m3-12a4 4 0 0 1 0 8m2 4v-2a7 7 0 0 0-3-5.7"/>
                            </svg>
                            <span>Pengguna</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('log-aktivitas.index') }}"
                           class="nav-link {{ request()->routeIs('log-aktivitas.*') ? 'active' : '' }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M3 12a9 9 0 1 0 2.6-6.4L3 8m0-5v5h5m4-1v5l3 2"/>
                            </svg>
                            <span>Log Aktivitas</span>
                        </a>
                    </li>
                </ul>
            @endif

        </nav>

        {{-- STATUS SISTEM --}}
        <div class="sidebar-bottom">
            <div class="system-status">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="m12 3 8 3v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3Z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>

                <span>
                    <strong>Sistem SPI Aktif</strong>
                    <span class="version">v2.4 Audit Secure</span>
                </span>
            </div>
        </div>

    </div>

</aside>

{{-- SCRIPT: buka/tutup menu lewat tombol tiga garis, tertutup secara default --}}
<script>
(function () {
    const STORAGE_KEY = 'sidokspi_sidebar_open';

    function syncButton() {
        const btn = document.getElementById('sidebarMenuToggle');
        if (!btn) return;

        const isOpen = document.body.classList.contains('sidebar-open');
        btn.setAttribute('aria-expanded', String(isOpen));
        btn.setAttribute('aria-label', isOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi');
    }

    document.addEventListener('DOMContentLoaded', syncButton);

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('#sidebarMenuToggle');
        if (!btn) return;

        const isOpen = document.body.classList.toggle('sidebar-open');

        try {
            localStorage.setItem(STORAGE_KEY, String(isOpen));
        } catch (err) {}

        syncButton();
    });

    // Saat logout, reset supaya login berikutnya menu kembali tertutup
    document.addEventListener('submit', function (e) {
        const action = (e.target.getAttribute('action') || '').toLowerCase();
        if (action.indexOf('logout') !== -1) {
            try {
                localStorage.removeItem(STORAGE_KEY);
            } catch (err) {}
        }
    });
})();
</script>