<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f7ff">

    <title>@yield('title', 'SIDOKSPI')</title>

    <link rel="stylesheet" href="{{ asset('asset/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/css/layout.css') }}?v=10">

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

</body>
</html>