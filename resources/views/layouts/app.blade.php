<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f7f7ff">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SIDOKSPI')</title>

   <link rel="stylesheet" href="{{ asset('asset/css/app.css') }}">

@stack('styles')

<link rel="stylesheet" href="{{ asset('asset/css/layout.css') }}?v=11">

<link rel="stylesheet" href="{{ asset('css/typography.css') }}?v=1">
</head>

<body class="@yield('body-class')">

    <div class="app-layout">

        @include('layouts.partials.sidebar', [
            'activeMenu' => trim($__env->yieldContent('active-menu')),
        ])

        <div class="main-area">

            @include('layouts.partials.topbar', [
                'breadcrumb' => trim($__env->yieldContent('breadcrumb')),
                'searchAction' => trim($__env->yieldContent('search-action')),
            ])

            <main class="page-content">

                @unless(request()->routeIs('kategori.*', 'pengguna.*'))

                    @if(session('success'))
                        <div class="notice" role="status">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="notice" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                @endunless

                @yield('content')

            </main>

        </div>

    </div>

    @stack('scripts')

</body>
</html>
