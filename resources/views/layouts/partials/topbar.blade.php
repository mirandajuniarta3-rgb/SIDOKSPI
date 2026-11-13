@php
    $user = auth()->user();
    $initials = collect(explode(' ', trim($user->nama_lengkap)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');
@endphp

<header class="topbar">
    <div class="breadcrumb">Portal SPI <span>›</span><strong>{{ $breadcrumb }}</strong></div>
    <form class="search-form top-search" method="GET" action="{{ $searchAction }}" role="search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg>
        <input type="search" name="search" value="{{ $search ?? '' }}" placeholder="Cari dokumen, SK, nomor surat..." aria-label="Cari dokumen">
    </form>
    <div class="topbar-spacer spacer"></div>
    <button class="icon-button" type="button" aria-label="Notifikasi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9m-8 12h4"/></svg>
        <span class="notification-dot" aria-hidden="true"></span>
    </button>
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
