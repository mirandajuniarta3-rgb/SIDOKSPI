<aside class="sidebar" aria-label="Navigasi utama">
    <a class="brand" href="{{ route('dashboard') }}">
        <span class="brand-mark" aria-hidden="true">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3 4 6v5c0 5 3.5 8.5 8 10 4.5-1.5 8-5 8-10V6l-8-3Z"/><path d="M8.5 12h7"/></svg>
        </span>
        <span><span class="brand-name">SIDOKSPI</span><span class="brand-caption">SPI PDAM Tirta Pinang</span></span>
    </a>

    <p class="nav-label">MENU NAVIGASI</p>
    <nav aria-label="Menu dashboard">
        <ul class="nav-list">
            <li>
                <a class="nav-link {{ $activeMenu === 'dashboard' ? 'active' : '' }}" href="{{ route('dashboard') }}" @if ($activeMenu === 'dashboard') aria-current="page" @endif>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>
            </li>
            <li>
                <details class="nav-dropdown" @if (in_array($activeMenu, ['documents', 'document-create', 'document-archive'], true)) open @endif>
                    <summary class="nav-link nav-dropdown-toggle {{ in_array($activeMenu, ['documents', 'document-create', 'document-archive'], true) ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 7h7l2 2h9v10H3z"/><path d="M3 7V5h7l2 2"/></svg>
                        <span>Dokumen</span>
                        <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <ul class="nav-sublist" aria-label="Menu dokumen">
                        <li><a class="nav-sublink {{ $activeMenu === 'documents' ? 'active' : '' }}" href="{{ route('dokumen.index') }}" @if ($activeMenu === 'documents') aria-current="page" @endif>Daftar Dokumen</a></li>
                        <li><a class="nav-sublink {{ $activeMenu === 'document-create' ? 'active' : '' }}" href="{{ route('dokumen.create') }}" @if ($activeMenu === 'document-create') aria-current="page" @endif>Unggah Dokumen</a></li>
                        <li><a class="nav-sublink {{ $activeMenu === 'document-archive' ? 'active' : '' }}" href="{{ route('dokumen.arsip') }}" @if ($activeMenu === 'document-archive') aria-current="page" @endif>Arsip</a></li>
                    </ul>
                </details>
            </li>
            <li>
                <details class="nav-dropdown" @if (in_array($activeMenu, ['sk-list', 'sk-create', 'sk-archive'], true)) open @endif>
                    <summary class="nav-link nav-dropdown-toggle {{ in_array($activeMenu, ['sk-list', 'sk-create', 'sk-archive'], true) ? 'active' : '' }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 3h12v18H6zM9 7h6m-6 4h6m-6 4h4"/></svg>
                        <span>Surat Keputusan (SK)</span>
                        <svg class="nav-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m6 9 6 6 6-6"/></svg>
                    </summary>
                    <ul class="nav-sublist" aria-label="Menu surat keputusan">
                        <li><a class="nav-sublink {{ $activeMenu === 'sk-list' ? 'active' : '' }}" href="{{ route('sk.index') }}" @if ($activeMenu === 'sk-list') aria-current="page" @endif>Daftar SK</a></li>
                        <li><span class="nav-sublink disabled" aria-disabled="true" title="Halaman tambah SK belum tersedia">Tambah SK</span></li>
                        <li><span class="nav-sublink disabled" aria-disabled="true" title="Halaman arsip SK belum tersedia">Arsip SK</span></li>
                    </ul>
                </details>
            </li>
            <li><span class="nav-link disabled" aria-disabled="true" title="Menu laporan belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20V4m0 16h17M8 16v-4m5 4V7m5 9v-6"/></svg>Laporan</span></li>
        </ul>

        <p class="nav-label">MASTER DATA</p>
        <ul class="nav-list">
            <li><span class="nav-link disabled" aria-disabled="true" title="Menu kategori belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M20 12 12 20l-9-9V4h7l10 8Z"/><circle cx="7.5" cy="8" r="1"/></svg>Kategori Dokumen</span></li>
            <li><span class="nav-link disabled" aria-disabled="true" title="Menu pengguna belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="9" cy="8" r="4"/><path d="M2 21v-2a7 7 0 0 1 14 0v2m3-12a4 4 0 0 1 0 8m2 4v-2a7 7 0 0 0-3-5.7"/></svg>Pengguna</span></li>
            <li><span class="nav-link disabled" aria-disabled="true" title="Menu log aktivitas belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 12a9 9 0 1 0 2.6-6.4L3 8m0-5v5h5m4-1v5l3 2"/></svg>Log Aktivitas</span></li>
        </ul>
    </nav>

    <div class="sidebar-bottom">
        <div class="system-status">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 8 3v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3Z"/><path d="m9 12 2 2 4-4"/></svg>
            <span><strong>Sistem SPI Aktif</strong><span class="version">v2.4 Audit Secure</span></span>
        </div>
    </div>
</aside>
