
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f7ff">

    <title>@yield('title', 'SIDOKSPI')</title>

    <link rel="stylesheet" href="{{ asset('asset/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/layout.css') }}?v=13">

    {{-- Pulihkan status sidebar sebelum halaman ditampilkan --}}
    <script>
        (function () {
            try {
                if (localStorage.getItem('sidokspi_sidebar_open') === 'true') {
                    document.documentElement.classList.add('sidebar-restore-open');
                }
            } catch (e) {}
        })();
    </script>

    @stack('styles')
</head>

<body class="@yield('body-class')">

    <div class="app-layout">

        {{-- SIDEBAR --}}
        @include('layouts.partials.sidebar', [
            'activeMenu' => trim($__env->yieldContent('active-menu')),
        ])

        <div class="main-area">

            {{-- TOPBAR --}}
            @include('layouts.partials.topbar', [
                'breadcrumb' => trim($__env->yieldContent('breadcrumb')),
                'searchAction' => trim($__env->yieldContent('search-action')),
            ])

            {{-- KONTEN HALAMAN --}}
            <main class="page-content">

                @if (session('success'))
                    <div class="notice" role="status">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="notice" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')

            </main>
        </div>
    </div>

    @stack('scripts')

    {{-- SIDEBAR CONTROLLER --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const body = document.body;
            const sidebar = document.querySelector('.sidebar');
            const toggle = document.getElementById('sidebarMenuToggle');

            const SIDEBAR_KEY = 'sidokspi_sidebar_open';
            const SUBMENU_KEY = 'sidokspi_submenus';

            if (!sidebar || !toggle) return;

            // Simpan status sidebar
            function saveSidebar(open) {
                try {
                    localStorage.setItem(SIDEBAR_KEY, String(open));
                } catch (e) {}
            }

            // Atur sidebar
            function setSidebar(open) {
                body.classList.toggle('sidebar-open', open);

                saveSidebar(open);

                toggle.setAttribute('aria-expanded', String(open));
                toggle.setAttribute(
                    'aria-label',
                    open ? 'Tutup menu navigasi' : 'Buka menu navigasi'
                );
            }

            // Pulihkan status sidebar
            let sidebarWasOpen = false;

            try {
                sidebarWasOpen =
                    localStorage.getItem(SIDEBAR_KEY) === 'true';
            } catch (e) {}

            body.classList.toggle('sidebar-open', sidebarWasOpen);

            toggle.setAttribute(
                'aria-expanded',
                String(sidebarWasOpen)
            );

            toggle.setAttribute(
                'aria-label',
                sidebarWasOpen ? 'Tutup menu navigasi' : 'Buka menu navigasi'
            );

            // Pulihkan status submenu
            const dropdowns = sidebar.querySelectorAll('.nav-dropdown');

            let savedSubmenus = {};

            try {
                savedSubmenus = JSON.parse(
                    localStorage.getItem(SUBMENU_KEY) || '{}'
                );
            } catch (e) {
                savedSubmenus = {};
            }

            dropdowns.forEach(function (dropdown, index) {
                const key = 'submenu_' + index;

                // Submenu halaman aktif harus tetap terbuka
                const hasActiveLink = dropdown.querySelector(
                    '.nav-sublink.active'
                );

                if (hasActiveLink) {
                    dropdown.open = true;
                } else if (
                    Object.prototype.hasOwnProperty.call(savedSubmenus, key)
                ) {
                    dropdown.open = savedSubmenus[key];
                }

                // Simpan perubahan submenu
                dropdown.addEventListener('toggle', function () {
                    savedSubmenus[key] = dropdown.open;

                    try {
                        localStorage.setItem(
                            SUBMENU_KEY,
                            JSON.stringify(savedSubmenus)
                        );
                    } catch (e) {}
                });
            });

            // Tombol hamburger
            toggle.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();

                const isOpen = body.classList.contains('sidebar-open');

                setSidebar(!isOpen);
            });

            // Klik di luar sidebar
            document.addEventListener('click', function (event) {
                if (!body.classList.contains('sidebar-open')) return;

                const clickedSidebar = event.target.closest('.sidebar');
                const clickedToggle = event.target.closest(
                    '#sidebarMenuToggle'
                );

                if (!clickedSidebar && !clickedToggle) {
                    setSidebar(false);
                }
            });

            // Tombol Escape
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    setSidebar(false);
                }
            });

            // Reset status saat logout
            document.addEventListener('submit', function (event) {
                const action = (
                    event.target.getAttribute('action') || ''
                ).toLowerCase();

                if (action.includes('logout')) {
                    try {
                        localStorage.removeItem(SIDEBAR_KEY);
                        localStorage.removeItem(SUBMENU_KEY);
                    } catch (e) {}
                }
            });
        });
    </script>

</body>
</html>
