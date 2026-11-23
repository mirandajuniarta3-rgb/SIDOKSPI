
@php
    $user = auth()->user();

    $role = strtolower(trim($user->role ?? ''));

    $nama = $user->nama_lengkap
        ?? $user->name
        ?? 'Pengguna SIDOKSPI';

    $roleBadge = match ($role) {
        'admin' => 'Administrator SPI',
        'staf_spi' => 'Staf SPI',
        'auditee' => 'Auditee',
        default => 'Pengguna',
    };

    $jabatan = match ($role) {
        'admin' => 'Administrator',
        'staf_spi' => 'Staf SPI',
        'auditee' => 'Auditee',
        default => 'Pengguna',
    };

    $initials = collect(explode(' ', trim($nama)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');
@endphp

<header class="topbar">

    <div class="breadcrumb">
        Portal SPI
        <span>›</span>
        <strong>{{ $breadcrumb ?? 'Dashboard' }}</strong>
    </div>

    <form
        class="search-form top-search"
        method="GET"
        action="{{ $searchAction ?: route('dashboard') }}"
        role="search"
    >
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="10.8" cy="10.8" r="6.8"/>
            <path d="m16 16 5 5"/>
        </svg>

        <input
            type="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari dokumen, SK, nomor surat..."
            aria-label="Cari dokumen"
        >
    </form>

    <div class="topbar-spacer"></div>

    <button class="icon-button notification-button" type="button" aria-label="Notifikasi">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
            <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9m-8 12h4"/>
        </svg>
        <span class="notification-dot"></span>
    </button>

    <span class="role-badge">{{ $roleBadge }}</span>

    <details class="account-switcher">
        <summary class="account-switch-trigger">

            <span class="account">
                <span class="avatar">
                    {{ mb_strtoupper($initials ?: 'U') }}
                </span>

                <span class="account-text">
                    <span class="account-name">{{ $nama }}</span>
                    <span class="account-role">{{ $jabatan }}</span>
                </span>
            </span>

            <svg class="account-switch-chevron"
                 viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2">
                <path d="m6 9 6 6 6-6"/>
            </svg>

        </summary>

        <div class="account-switch-dropdown">

            <div class="dropdown-user-info">
                <span class="avatar dropdown-avatar">
                    {{ mb_strtoupper($initials ?: 'U') }}
                </span>

                <span>
                    <strong>{{ $nama }}</strong>
                    <small>{{ $user->email }}</small>
                </span>
            </div>

            <div class="dropdown-divider"></div>

            <a href="{{ route('profil.index') }}" class="account-menu-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="12" cy="8" r="4"/>
                    <path d="M4 21v-2a8 8 0 0 1 16 0v2"/>
                </svg>
                Profil Saya
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="account-menu-link logout-menu-link">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M10 17l5-5-5-5m5 5H3m9-9h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7"/>
                    </svg>
                    Logout
                </button>
            </form>

        </div>
    </details>

</header>
