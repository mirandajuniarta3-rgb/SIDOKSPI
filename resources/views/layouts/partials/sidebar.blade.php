
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

{{-- PULIHKAN STATUS SIDEBAR --}}
<script>
    try {
        if (localStorage.getItem('sidokspi_sidebar_open') === 'true') {
            document.body.classList.add('sidebar-open');
        }

        if (
            window.innerWidth > 620 &&
            localStorage.getItem('sidokspi_sidebar_collapsed') === 'true'
        ) {
            document.body.classList.add('sidebar-collapsed');
        }
    } catch (e) {}
</script>

<aside class="sidebar" aria-label="Navigasi utama">

    {{-- IDENTITAS SIDOKSPI DAN HAMBURGER --}}
    <div class="sidebar-brand-row">

        <a class="brand" href="{{ route($dashboardRoute) }}">
            <span class="brand-mark">
                <img
                    src="{{ asset('asset/img/logo-pdam.png') }}"
                    alt="Logo PDAM"
                    class="logo-pdam"
                >
            </span>

            <span class="brand-text">
                <span class="brand-name">SIDOKSPI</span>
                <span class="brand-caption">SPI PDAM Tirta Pinang</span>
            </span>
        </a>

        <button
            type="button"
            class="sidebar-desktop-toggle"
            id="sidebarDesktopToggle"
            aria-expanded="true"
            aria-controls="sidebarNavigation"
            aria-label="Tutup menu navigasi"
            title="Tutup atau buka menu navigasi"
        >
            <svg viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2"
                 stroke-linecap="round">
                <path d="M4 6h16"/>
                <path d="M4 12h16"/>
                <path d="M4 18h16"/>
            </svg>
        </button>

    </div>

    {{-- NAVIGASI --}}
    <div class="sidebar-navigation" id="sidebarNavigation">

        <p class="nav-label">MENU NAVIGASI</p>

        <nav aria-label="Menu dashboard">
            <ul class="nav-list">

                {{-- DASHBOARD --}}
                <li>
                    <a
                        class="nav-link {{ request()->routeIs('dashboard', 'dashboard.admin', 'dashboard.staf', 'home') ? 'active' : '' }}"
                        href="{{ route($dashboardRoute) }}"
                        title="Dashboard"
                    >
                        <svg viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="8" height="8" rx="2"/>
                            <rect x="13" y="3" width="8" height="5" rx="2"/>
                            <rect x="13" y="10" width="8" height="11" rx="2"/>
                            <rect x="3" y="13" width="8" height="8" rx="2"/>
                        </svg>

                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- DOKUMEN --}}
                <li>
                    <details
                        class="nav-dropdown"
                        {{ request()->routeIs('dokumen.*') ? 'open' : '' }}
                    >
                        <summary class="nav-link nav-dropdown-toggle {{ request()->routeIs('dokumen.*') ? 'active' : '' }}">

                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 7h7l2 2h9v10H3z"/>
                                <path d="M3 7V5h7l2 2"/>
                            </svg>

                            <span>Dokumen</span>

                            <svg class="nav-chevron" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </summary>

                        <ul class="nav-sublist">

                            {{-- DAFTAR DOKUMEN --}}
                            <li>
                                <a
                                    class="nav-sublink {{ request()->routeIs('dokumen.index') ? 'active' : '' }}"
                                    href="{{ route('dokumen.index') }}"
                                    title="Daftar Dokumen"
                                >
                                    <svg viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 3h9l5 5v13H6z"/>
                                        <path d="M14 3v6h6"/>
                                        <path d="M9 13h8M9 17h8"/>
                                    </svg>

                                    <span>Daftar Dokumen</span>
                                </a>
                            </li>

                            {{-- UNGGAH DOKUMEN --}}
                            <li>
                                <a
                                    class="nav-sublink {{ request()->routeIs('dokumen.create') ? 'active' : '' }}"
                                    href="{{ route('dokumen.create') }}"
                                    title="Unggah Dokumen"
                                >
                                    <svg viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 16V4"/>
                                        <path d="m7 9 5-5 5 5"/>
                                        <path d="M4 16v4h16v-4"/>
                                    </svg>

                                    <span>Unggah Dokumen</span>
                                </a>
                            </li>

                            {{-- ARSIP DOKUMEN --}}
                            <li>
                                <a
                                    class="nav-sublink {{ request()->routeIs('dokumen.arsip') ? 'active' : '' }}"
                                    href="{{ route('dokumen.arsip') }}"
                                    title="Arsip Dokumen"
                                >
                                    <svg viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="5" rx="1"/>
                                        <path d="M5 9v11h14V9"/>
                                        <path d="M9 13h6"/>
                                    </svg>

                                    <span>Arsip</span>
                                </a>
                            </li>

                        </ul>
                    </details>
                </li>

                {{-- SURAT KEPUTUSAN --}}
                <li>
                    <details
                        class="nav-dropdown"
                        {{ request()->routeIs('sk.*') ? 'open' : '' }}
                    >
                        <summary class="nav-link nav-dropdown-toggle {{ request()->routeIs('sk.*') ? 'active' : '' }}">

                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 3h12v18H6z"/>
                                <path d="M9 7h6M9 11h6M9 15h4"/>
                            </svg>

                            <span>Surat Keputusan (SK)</span>

                            <svg class="nav-chevron" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </summary>

                        <ul class="nav-sublist">

                            {{-- DAFTAR SK --}}
                            <li>
                                <a
                                    class="nav-sublink {{ request()->routeIs('sk.index') ? 'active' : '' }}"
                                    href="{{ route('sk.index') }}"
                                    title="Daftar SK"
                                >
                                    <svg viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="4" y="3" width="16" height="18" rx="2"/>
                                        <path d="M8 8h8M8 12h8M8 16h5"/>
                                    </svg>

                                    <span>Daftar SK</span>
                                </a>
                            </li>

                            {{-- TAMBAH SK --}}
                            <li>
                                <a
                                    class="nav-sublink {{ request()->routeIs('sk.create') ? 'active' : '' }}"
                                    href="{{ route('sk.create') }}"
                                    title="Tambah SK"
                                >
                                    <svg viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 3h9l5 5v13H6z"/>
                                        <path d="M14 3v6h6"/>
                                        <path d="M12 12v6m-3-3h6"/>
                                    </svg>

                                    <span>Tambah SK</span>
                                </a>
                            </li>

                            {{-- ARSIP SK --}}
                            <li>
                                <a
                                    class="nav-sublink {{ request()->routeIs('sk.arsip') ? 'active' : '' }}"
                                    href="{{ route('sk.arsip') }}"
                                    title="Arsip SK"
                                >
                                    <svg viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16v5H4z"/>
                                        <path d="M6 9v11h12V9"/>
                                        <path d="M9 13h6"/>
                                    </svg>

                                    <span>Arsip SK</span>
                                </a>
                            </li>

                        </ul>
                    </details>
                </li>

                {{-- LAPORAN --}}
                <li>
                    <details
                        class="nav-dropdown"
                        {{ request()->routeIs('laporan.*') ? 'open' : '' }}
                    >
                        <summary class="nav-link nav-dropdown-toggle {{ request()->routeIs('laporan.*') ? 'active' : '' }}">

                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 20V4"/>
                                <path d="M4 20h17"/>
                                <rect x="7" y="12" width="3" height="5" rx="1"/>
                                <rect x="13" y="8" width="3" height="9" rx="1"/>
                                <rect x="19" y="4" width="3" height="13" rx="1"/>
                            </svg>

                            <span>Laporan</span>

                            <svg class="nav-chevron" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="2">
                                <path d="m6 9 6 6 6-6"/>
                            </svg>
                        </summary>

                        <ul class="nav-sublist">

                            {{-- REKAP DOKUMEN --}}
                            <li>
                                <a
                                    class="nav-sublink {{ request()->routeIs('laporan.dokumen') ? 'active' : '' }}"
                                    href="{{ route('laporan.dokumen') }}"
                                    title="Rekap Dokumen"
                                >
                                    <svg viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M5 3h10l4 4v14H5z"/>
                                        <path d="M14 3v5h5"/>
                                        <path d="M8 17v-3m4 3v-6m4 6v-4"/>
                                    </svg>

                                    <span>Rekap Dokumen</span>
                                </a>
                            </li>

                            {{-- REKAP SK --}}
                            <li>
                                <a
                                    class="nav-sublink {{ request()->routeIs('laporan.sk') ? 'active' : '' }}"
                                    href="{{ route('laporan.sk') }}"
                                    title="Rekap SK"
                                >
                                    <svg viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="1.8"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 19V5"/>
                                        <path d="M4 19h17"/>
                                        <path d="M8 15v-4m5 4V7m5 8V9"/>
                                    </svg>

                                    <span>Rekap SK</span>
                                </a>
                            </li>

                        </ul>
                    </details>
                </li>

            </ul>

            {{-- MASTER DATA ADMIN --}}
            @if ($isAdmin)

                <p class="nav-label">MASTER DATA</p>

                <ul class="nav-list">

                    {{-- KATEGORI DOKUMEN --}}
                    <li>
                        <a
                            href="{{ route('kategori.index') }}"
                            class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}"
                            title="Kategori Dokumen"
                        >
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 12 12 20l-9-9V4h7l10 8Z"/>
                                <circle cx="7.5" cy="8" r="1"/>
                            </svg>

                            <span>Kategori Dokumen</span>
                        </a>
                    </li>

                    {{-- PENGGUNA --}}
                    <li>
                        <a
                            href="{{ route('pengguna.index') }}"
                            class="nav-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}"
                            title="Pengguna"
                        >
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="8" r="4"/>
                                <path d="M2 21v-2a7 7 0 0 1 14 0v2"/>
                                <path d="M19 8a4 4 0 0 1 0 8"/>
                                <path d="M22 21v-2a7 7 0 0 0-3-5.7"/>
                            </svg>

                            <span>Pengguna</span>
                        </a>
                    </li>

                    {{-- LOG AKTIVITAS --}}
                    <li>
                        <a
                            href="{{ route('log-aktivitas.index') }}"
                            class="nav-link {{ request()->routeIs('log-aktivitas.*') ? 'active' : '' }}"
                            title="Log Aktivitas"
                        >
                            <svg viewBox="0 0 24 24" fill="none"
                                 stroke="currentColor" stroke-width="1.8"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 1 0 2.6-6.4L3 8"/>
                                <path d="M3 3v5h5"/>
                                <path d="M12 7v5l3 2"/>
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

                <svg viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="1.8"
                     stroke-linecap="round" stroke-linejoin="round">
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

{{-- SCRIPT SIDEBAR DESKTOP DAN MOBILE --}}
<script>
(function () {
    const DESKTOP_KEY = 'sidokspi_sidebar_collapsed';
    const MOBILE_KEY = 'sidokspi_sidebar_open';

    function syncButtons() {
        const desktopButton = document.getElementById('sidebarDesktopToggle');
        const mobileButton = document.getElementById('sidebarMenuToggle');

        const desktopCollapsed = document.body.classList.contains('sidebar-collapsed');
        const mobileOpen = document.body.classList.contains('sidebar-open');

        if (desktopButton) {
            desktopButton.setAttribute('aria-expanded', String(!desktopCollapsed));
            desktopButton.setAttribute(
                'aria-label',
                desktopCollapsed ? 'Buka menu navigasi' : 'Tutup menu navigasi'
            );
            desktopButton.setAttribute(
                'title',
                desktopCollapsed ? 'Buka menu navigasi' : 'Tutup menu navigasi'
            );
        }

        if (mobileButton) {
            mobileButton.setAttribute('aria-expanded', String(mobileOpen));
            mobileButton.setAttribute(
                'aria-label',
                mobileOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi'
            );
        }
    }

    document.addEventListener('click', function (event) {
        const desktopButton = event.target.closest('#sidebarDesktopToggle');

        if (desktopButton) {
            const collapsed = document.body.classList.toggle('sidebar-collapsed');

            try {
                localStorage.setItem(DESKTOP_KEY, String(collapsed));
            } catch (error) {}

            syncButtons();
            return;
        }

        const mobileButton = event.target.closest('#sidebarMenuToggle');

        if (mobileButton) {
            const opened = document.body.classList.toggle('sidebar-open');

            try {
                localStorage.setItem(MOBILE_KEY, String(opened));
            } catch (error) {}

            syncButtons();
        }
    });

    document.addEventListener('submit', function (event) {
        const action = (event.target.getAttribute('action') || '').toLowerCase();

        if (action.includes('logout')) {
            try {
                localStorage.removeItem(DESKTOP_KEY);
                localStorage.removeItem(MOBILE_KEY);
            } catch (error) {}
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth <= 620) {
            document.body.classList.remove('sidebar-collapsed');
        } else {
            document.body.classList.remove('sidebar-open');
        }

        syncButtons();
    });

    syncButtons();
})();
</script>
