
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

    {{-- HAMBURGER --}}
    <button
        type="button"
        class="sidebar-hamburger"
        id="sidebarMenuToggle"
        aria-expanded="false"
        aria-controls="sidebarNavigation"
        aria-label="Buka menu navigasi"
    >
        <svg viewBox="0 0 24 24" fill="none"
             stroke="currentColor"
             stroke-width="2.2"
             stroke-linecap="round">
            <line x1="4" y1="6" x2="20" y2="6"/>
            <line x1="4" y1="12" x2="20" y2="12"/>
            <line x1="4" y1="18" x2="20" y2="18"/>
        </svg>
    </button>

    {{-- BREADCRUMB --}}
    <div class="breadcrumb">
        Portal SPI
        <span>›</span>
        <strong>{{ $breadcrumb ?? 'Dashboard' }}</strong>
    </div>

    {{-- PENCARIAN --}}
    <form
        class="search-form top-search"
        method="GET"
        action="{{ $searchAction ?: route('dashboard') }}"
        role="search"
    >
        <svg viewBox="0 0 24 24" fill="none"
             stroke="currentColor"
             stroke-width="2">
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

    {{-- LONCENG NOTIFIKASI --}}
    <div
        class="notification-wrapper"
        id="notificationWrapper"
        data-index-url="{{ route('notifikasi.index') }}"
        data-read-all-url="{{ route('notifikasi.read-all') }}"
        data-read-url="{{ url('/notifikasi') }}"
    >

        <button
            type="button"
            class="notification-button"
            id="notificationToggle"
            aria-label="Buka notifikasi"
            aria-expanded="false"
            aria-controls="notificationPanel"
        >

            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.8">
                <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                <path d="M10 21h4"/>
            </svg>

            <span
                class="notification-count"
                id="notificationCount"
                hidden
            >0</span>

        </button>

        {{-- DROPDOWN NOTIFIKASI --}}
        <div
            class="notification-panel"
            id="notificationPanel"
            aria-hidden="true"
        >

            {{-- HEADER --}}
            <div class="notification-panel-header">

                <div class="notification-header-left">

                    <div class="notification-header-icon">
                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8">
                            <path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9"/>
                            <path d="M10 21h4"/>
                        </svg>
                    </div>

                    <div>
                        <h3>Notifikasi</h3>
                        <p id="notificationSubtitle">
                            Pemberitahuan terbaru
                        </p>
                    </div>

                </div>

                <button
                    type="button"
                    class="notification-close"
                    id="notificationClose"
                    aria-label="Tutup notifikasi"
                >
                    &times;
                </button>

            </div>

            {{-- TOOLBAR --}}
            <div class="notification-toolbar">

                <div class="notification-section-title">
                    <span class="notification-status-dot"></span>
                    Aktivitas terbaru
                </div>

                <button
                    type="button"
                    class="mark-all-button"
                    id="markAllNotifications"
                >
                    Tandai semua dibaca
                </button>

            </div>

            {{-- DAFTAR NOTIFIKASI --}}
            <div
                class="notification-list"
                id="notificationList"
                aria-live="polite"
            >
                <div class="notification-loading">
                    Memuat notifikasi...
                </div>
            </div>

            {{-- FOOTER --}}
            <div class="notification-footer">
                <span>SIDOKSPI</span>
                <span>Sistem Informasi SPI</span>
            </div>

        </div>
    </div>

    {{-- ROLE --}}
    <span class="role-badge">{{ $roleBadge }}</span>

    {{-- AKUN PENGGUNA --}}
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

            <a
                href="{{ route('profil.index') }}"
                class="account-menu-link"
            >
                Profil Saya
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="account-menu-link logout-menu-link"
                >
                    Logout
                </button>
            </form>

        </div>
    </details>

</header>


{{-- =====================================================
     DESAIN CSS LONCENG NOTIFIKASI SIDOKSPI
===================================================== --}}

<style>
.notification-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    flex: 0 0 auto;
    z-index: 1000;
}

/* TOMBOL LONCENG */
.notification-button {
    position: relative;

    width: 42px;
    height: 42px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 1px solid #e6edf7;
    border-radius: 12px;

    background: #ffffff;
    color: #52647d;

    cursor: pointer;
    transition: all .2s ease;
}

.notification-button svg {
    width: 21px;
    height: 21px;
}

.notification-button:hover {
    color: #2168d6;
    background: #f0f6ff;
    border-color: #c9dcfa;
}

/* BADGE JUMLAH NOTIFIKASI */
.notification-count {
    position: absolute;
    top: -6px;
    right: -6px;

    min-width: 19px;
    height: 19px;
    padding: 0 5px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: 2px solid #ffffff;
    border-radius: 20px;

    background: #ef4444;
    color: #ffffff;

    font-size: 10px;
    font-weight: 800;
}

.notification-count[hidden] {
    display: none;
}

/* PANEL DROPDOWN */
.notification-panel {
    position: absolute;
    top: calc(100% + 13px);
    right: 0;

    z-index: 99999;

    width: 380px;
    max-width: calc(100vw - 24px);

    background: #ffffff;

    border: 1px solid #e7edf5;
    border-radius: 17px;

    box-shadow: 0 18px 50px rgba(25, 55, 100, .17);

    overflow: hidden;

    opacity: 0;
    visibility: hidden;
    pointer-events: none;

    transform: translateY(10px);

    transition: .22s ease;
}

.notification-wrapper.open .notification-panel {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
    transform: translateY(0);
}

/* HEADER PANEL */
.notification-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;

    padding: 19px 20px;

    background: linear-gradient(135deg, #f2f7ff, #ffffff);

    border-bottom: 1px solid #edf1f7;
}

.notification-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.notification-header-icon {
    width: 40px;
    height: 40px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 12px;

    background: #e4efff;
    color: #2168d6;
}

.notification-header-icon svg {
    width: 21px;
    height: 21px;
}

.notification-panel-header h3 {
    margin: 0;

    color: #18345d;

    font-size: 15px;
    font-weight: 800;
}

.notification-panel-header p {
    margin: 5px 0 0;

    color: #8291a7;

    font-size: 11px;
}

.notification-close {
    width: 30px;
    height: 30px;

    display: flex;
    align-items: center;
    justify-content: center;

    border: none;
    border-radius: 8px;

    background: #edf3fc;
    color: #718096;

    font-size: 22px;

    cursor: pointer;
    transition: .2s ease;
}

.notification-close:hover {
    background: #dceaff;
    color: #2168d6;
}

/* TOOLBAR */
.notification-toolbar {
    min-height: 48px;

    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;

    padding: 10px 18px;

    background: #ffffff;
    border-bottom: 1px solid #edf1f6;
}

.notification-section-title {
    display: flex;
    align-items: center;
    gap: 7px;

    color: #52647d;

    font-size: 11px;
    font-weight: 800;
}

.notification-status-dot {
    width: 7px;
    height: 7px;

    border-radius: 50%;
    background: #2168d6;
}

.mark-all-button {
    padding: 6px 9px;

    border: none;
    border-radius: 7px;

    background: #edf4ff;
    color: #2168d6;

    font-family: inherit;
    font-size: 10px;
    font-weight: 800;

    cursor: pointer;
    transition: .2s ease;
}

.mark-all-button:hover {
    background: #dceaff;
}

.mark-all-button:disabled {
    opacity: .5;
    cursor: not-allowed;
}

/* LIST NOTIFIKASI */
.notification-list {
    max-height: 360px;
    overflow-y: auto;
    background: #ffffff;
}

.notification-loading,
.notification-empty,
.notification-error {
    padding: 35px 18px;

    color: #8795a8;

    font-size: 12px;
    text-align: center;
}

/* ITEM NOTIFIKASI */
.notification-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;

    padding: 15px 18px;

    border-bottom: 1px solid #f0f3f8;

    color: inherit;
    text-decoration: none;

    transition: background .2s ease;
}

.notification-item:last-child {
    border-bottom: none;
}

.notification-item:hover {
    background: #f7faff;
}

.notification-item.unread {
    background: #f0f6ff;
}

.notification-item.unread:hover {
    background: #e8f1ff;
}

.notification-item-icon {
    flex: 0 0 37px;

    width: 37px;
    height: 37px;

    display: flex;
    align-items: center;
    justify-content: center;

    border-radius: 11px;

    background: #e4efff;
    color: #2168d6;

    font-size: 14px;
    font-weight: 800;
}

.notification-item-content {
    min-width: 0;
    flex: 1;
}

.notification-item-content strong {
    display: block;

    color: #233b5d;

    font-size: 12px;
    font-weight: 800;
    line-height: 1.5;
}

.notification-item-content p {
    margin: 5px 0;

    color: #718096;

    font-size: 11px;
    line-height: 1.6;

    overflow-wrap: anywhere;
}

.notification-item-content small {
    display: block;

    color: #9aa7b8;

    font-size: 10px;
}

.notification-unread-dot {
    flex: 0 0 7px;

    width: 7px;
    height: 7px;

    margin-top: 5px;

    border-radius: 50%;
    background: #2168d6;
}

/* FOOTER */
.notification-footer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;

    padding: 13px;

    border-top: 1px solid #edf1f6;

    background: #f9fbff;

    color: #98a5b6;

    font-size: 10px;
}

.notification-footer span:first-child {
    color: #2168d6;
    font-weight: 800;
}

/* SCROLLBAR */
.notification-list::-webkit-scrollbar {
    width: 5px;
}

.notification-list::-webkit-scrollbar-thumb {
    background: #d8e2f0;
    border-radius: 10px;
}

/* RESPONSIVE */
@media (max-width: 600px) {
    .notification-panel {
        position: fixed;

        top: 70px;
        right: 12px;

        width: 380px;
        max-width: calc(100vw - 24px);
    }
}

@media (max-width: 380px) {
    .notification-panel {
        right: 8px;
        width: calc(100vw - 16px);
        max-width: none;
    }

    .notification-toolbar {
        padding-left: 12px;
        padding-right: 12px;
    }
}
</style>


{{-- =====================================================
     JAVASCRIPT NOTIFIKASI
===================================================== --}}

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const wrapper = document.getElementById('notificationWrapper');

    if (!wrapper) return;

    const toggle = document.getElementById('notificationToggle');
    const panel = document.getElementById('notificationPanel');
    const closeButton = document.getElementById('notificationClose');
    const list = document.getElementById('notificationList');
    const count = document.getElementById('notificationCount');
    const subtitle = document.getElementById('notificationSubtitle');
    const markAllButton = document.getElementById('markAllNotifications');

    const indexUrl = wrapper.dataset.indexUrl;
    const readAllUrl = wrapper.dataset.readAllUrl;
    const readUrl = wrapper.dataset.readUrl;

    const csrfToken = document.querySelector('meta[name="csrf-token"]')
        ?.getAttribute('content');

    let notifications = [];
    let loading = false;

    function updateCount(total) {

        const unread = Number(total) || 0;

        if (unread > 0) {
            count.hidden = false;
            count.textContent = unread > 99 ? '99+' : unread;
        } else {
            count.hidden = true;
            count.textContent = '0';
        }

        subtitle.textContent = unread > 0
            ? unread + ' notifikasi belum dibaca'
            : 'Tidak ada notifikasi baru';
    }

    function closePanel() {

        wrapper.classList.remove('open');

        toggle.setAttribute('aria-expanded', 'false');
        panel.setAttribute('aria-hidden', 'true');
    }

    function openPanel() {

        wrapper.classList.add('open');

        toggle.setAttribute('aria-expanded', 'true');
        panel.setAttribute('aria-hidden', 'false');

        loadNotifications();
    }

    function showMessage(message, className) {

        list.replaceChildren();

        const element = document.createElement('div');

        element.className = className;
        element.textContent = message;

        list.appendChild(element);
    }

    function renderNotifications() {

        list.replaceChildren();

        if (!notifications.length) {

            showMessage(
                'Belum ada notifikasi untuk Anda.',
                'notification-empty'
            );

            return;
        }

        notifications.forEach(function (item) {

            const link = document.createElement('a');

            link.className = 'notification-item' +
                (item.is_read ? '' : ' unread');

            const icon = document.createElement('span');

            icon.className = 'notification-item-icon';
            icon.textContent = 'N';

            const content = document.createElement('span');

            content.className = 'notification-item-content';

            const title = document.createElement('strong');

            title.textContent = item.title || 'Notifikasi SIDOKSPI';

            const message = document.createElement('p');

            message.textContent = item.message || '';

            const time = document.createElement('small');

            time.textContent = item.created_at || '';

            content.append(title, message, time);

            link.appendChild(icon);
            link.appendChild(content);

            if (!item.is_read) {

                const dot = document.createElement('span');

                dot.className = 'notification-unread-dot';

                link.appendChild(dot);
            }

            link.href = item.url || '#';

            link.addEventListener('click', async function (event) {

                if (!item.is_read) {

                    event.preventDefault();

                    try {

                        const response = await fetch(
                            readUrl + '/' + encodeURIComponent(item.id) + '/baca',
                            {
                                method: 'PATCH',
                                credentials: 'same-origin',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Accept': 'application/json'
                                }
                            }
                        );

                        if (!response.ok) {
                            throw new Error('Gagal menandai notifikasi.');
                        }

                        item.is_read = true;

                        const unreadCount = notifications.filter(
                            notification => !notification.is_read
                        ).length;

                        updateCount(unreadCount);

                    } catch (error) {

                        console.error(
                            'Gagal menandai notifikasi:',
                            error
                        );

                        return;
                    }

                    if (item.url) {
                        window.location.href = item.url;
                    } else {
                        renderNotifications();
                    }

                } else if (!item.url) {

                    event.preventDefault();
                }
            });

            list.appendChild(link);
        });
    }

    async function loadNotifications() {

        if (loading) return;

        loading = true;

        showMessage(
            'Memuat notifikasi...',
            'notification-loading'
        );

        try {

            const response = await fetch(indexUrl, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal mengambil notifikasi.');
            }

            const data = await response.json();

            notifications = data.notifications || [];

            updateCount(data.unread_count || 0);

            renderNotifications();

        } catch (error) {

            showMessage(
                'Notifikasi gagal dimuat. Silakan coba lagi.',
                'notification-error'
            );

            console.error(error);

        } finally {

            loading = false;
        }
    }

    toggle.addEventListener('click', function () {

        if (wrapper.classList.contains('open')) {
            closePanel();
        } else {
            openPanel();
        }
    });

    closeButton.addEventListener('click', closePanel);

    document.addEventListener('click', function (event) {

        if (!wrapper.contains(event.target)) {
            closePanel();
        }
    });

    document.addEventListener('keydown', function (event) {

        if (event.key === 'Escape') {
            closePanel();
        }
    });

    markAllButton.addEventListener('click', async function () {

        markAllButton.disabled = true;

        try {

            const response = await fetch(readAllUrl, {
                method: 'PATCH',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error('Gagal memperbarui notifikasi.');
            }

            notifications.forEach(function (item) {
                item.is_read = true;
            });

            updateCount(0);

            renderNotifications();

        } catch (error) {

            alert('Gagal menandai notifikasi. Silakan coba lagi.');

            console.error(error);

        } finally {

            markAllButton.disabled = false;
        }
    });

    loadNotifications();

});
</script>
@endpush
