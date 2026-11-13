@extends('layouts.app')

@section('title', 'Daftar Dokumen - SIDOKSPI')
@section('active-menu', 'documents')
@section('breadcrumb', 'Dokumen / Daftar Dokumen')
@section('search-action', route('dokumen.index'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/daftar-dokumen.css') }}">
@endpush

@section('content')
<section class="page-heading" aria-labelledby="page-title">
                <div>
                    <div class="eyebrow">Portal SPI <span aria-hidden="true">›</span> Beranda Administrasi</div>
                    <h1 id="page-title">Daftar Dokumen Pengawasan</h1>
                    <p class="page-description">Kelola dan telusuri seluruh berkas audit operasional, laporan hasil audit (LHA), KKP, dan arsip dokumen SPI PDAM Tirta Pinang dalam repositori terenkripsi berbasis kepatuhan ISO 27001.</p>
                </div>
                <div class="heading-actions">
                    <button class="button export-button" type="button" disabled title="Fitur ekspor belum tersedia">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4"/></svg>Ekspor Data
                    </button>
                    <a class="button primary" href="{{ route('dokumen.create') }}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 16V4m-5 5 5-5 5 5M4 16v4h16v-4"/></svg>Unggah Dokumen
                    </a>
                </div>
            </section>

            <section class="stats-grid" aria-label="Statistik dokumen">
                <article class="stat-card">
                    <div><div class="stat-label">Total Dokumen Terdaftar</div><div class="stat-value">{{ number_format($totalDokumen, 0, ',', '.') }}</div><span class="stat-note">Dokumen SPI</span></div>
                    <span class="stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 6h6l2 2h8v11H4z"/><path d="M4 8h16"/></svg></span>
                </article>
                <article class="stat-card">
                    <div><div class="stat-label">Dokumen Aktif &amp; Terbuka</div><div class="stat-value" style="color:#087c68">{{ number_format($dokumenAktif, 0, ',', '.') }}</div><span class="stat-note">Dokumen tidak diarsipkan</span></div>
                    <span class="stat-icon teal"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 2.2 2 3-.1.8 2.9 2.5 1.7-1 2.8 1 2.8-2.5 1.7-.8 2.9-3-.1-2.2 2-2.2-2-3 .1-.8-2.9-2.5-1.7 1-2.8-1-2.8 2.5-1.7.8-2.9 3 .1L12 3Z"/><path d="m8.5 12 2.2 2.2 4.8-4.8"/></svg></span>
                </article>
                <article class="stat-card">
                    <div><div class="stat-label">Dokumen Diarsipkan</div><div class="stat-value" style="color:#78839a">{{ number_format($dokumenDiarsipkan, 0, ',', '.') }}</div><span class="stat-note">Status arsip</span></div>
                    <span class="stat-icon gray"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 8h8m-8 4h8m-8 4h5"/></svg></span>
                </article>
            </section>

            <section class="table-panel" aria-label="Daftar dokumen">
                <div class="audit-strip">
                    <span class="audit-title"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 3 8 3v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3Z"/><path d="m9 12 2 2 4-4"/></svg>Audit Ledger Kearsipan Digital SPI — Terenkripsi Server Mandiri Tirta Pinang</span>
                    <span class="audit-updated">Terakhir diperbarui: {{ now()->locale('id')->translatedFormat('d M Y H:i') }} WIB</span>
                </div>
                <form class="filter-bar" method="GET" action="{{ route('dokumen.index') }}">
                    <label class="filter-control">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg>
                        <input type="search" name="search" value="{{ $search }}" placeholder="Cari nomor dokumen, judul berkas, atau pengunggah..." aria-label="Cari nomor dokumen, judul, atau pengunggah">
                    </label>
                    <label class="filter-control">
                        <select name="kategori" aria-label="Filter kategori">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}" @selected((string) $selectedKategori === (string) $item->id)>{{ $item->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="filter-control">
                        <select name="tahun" aria-label="Filter tahun">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahun as $year)
                                <option value="{{ $year }}" @selected((string) $selectedTahun === (string) $year)>{{ $year }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="filter-control">
                        <select name="status" aria-label="Filter status">
                            <option value="">Semua Status</option>
                            <option value="final" @selected($selectedStatus === 'final')>Aktif</option>
                            <option value="draft" @selected($selectedStatus === 'draft')>Draft</option>
                            <option value="arsip" @selected($selectedStatus === 'arsip')>Arsip</option>
                        </select>
                    </label>
                    <button class="button primary" type="submit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 5h16M7 12h10m-7 7h4"/></svg>Terapkan
                    </button>
                    <a class="reset-link" href="{{ route('dokumen.index') }}">Reset</a>
                </form>
                <div class="table-scroll">
                    <table>
                        <thead>
                            <tr><th>NO</th><th>NOMOR DOKUMEN</th><th>JUDUL BERKAS DOKUMEN</th><th>KATEGORI</th><th>TANGGAL</th><th>PENGUNGGAH</th><th>STATUS</th><th>AKSI MANAJEMEN</th></tr>
                        </thead>
                        <tbody>
                            @forelse ($dokumen as $item)
                                @php
                                    $uploaderName = $item->uploader?->nama_lengkap ?? 'Tidak diketahui';
                                    $uploaderInitials = collect(explode(' ', trim($uploaderName)))
                                        ->filter()
                                        ->take(2)
                                        ->map(fn ($part) => mb_substr($part, 0, 1))
                                        ->implode('');
                                    $statusClass = $item->status === 'arsip' ? 'archived' : ($item->status === 'draft' ? 'draft' : '');
                                    $statusLabel = ['final' => 'Aktif', 'draft' => 'Draft', 'arsip' => 'Arsip'][$item->status] ?? ucfirst($item->status);
                                @endphp
                                <tr>
                                    <td class="number-cell">{{ $dokumen->firstItem() + $loop->index }}</td>
                                    <td><span class="doc-number">{{ $item->nomor_dokumen ?: '—' }}</span></td>
                                    <td>
                                        <span class="doc-title" title="{{ $item->judul }}">{{ $item->judul }}</span>
                                        <span class="file-meta">{{ $item->nama_file ?: 'Dokumen digital' }}@if ($item->ukuran_kb) · {{ number_format($item->ukuran_kb / 1024, 1, ',', '.') }} MB @endif</span>
                                    </td>
                                    <td><span class="category-badge">{{ $item->kategori?->nama_kategori ?? 'Tanpa kategori' }}</span></td>
                                    <td>{{ $item->tanggal_dokumen?->locale('id')->translatedFormat('d M Y') ?? '—' }}</td>
                                    <td><span class="uploader"><span class="uploader-avatar">{{ mb_strtoupper($uploaderInitials ?: 'U') }}</span>{{ $uploaderName }}</span></td>
                                    <td><span class="status {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                    <td>
                                        <span class="actions">
                                            <button class="action-button" type="button" disabled aria-label="Lihat dokumen" title="Fitur lihat dokumen belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/><circle cx="12" cy="12" r="3"/></svg></button>
                                            <button class="action-button" type="button" disabled aria-label="Unduh dokumen" title="Fitur unduh dokumen belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4"/></svg></button>
                                            <button class="action-button" type="button" disabled aria-label="Kelola dokumen" title="Fitur manajemen dokumen belum tersedia"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M7 4h10M6 7l1 14h10l1-14M10 11v6m4-6v6"/></svg></button>
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td class="empty-cell" colspan="8">{{ $search !== '' || $selectedKategori !== '' || $selectedTahun !== '' || $selectedStatus !== '' ? 'Tidak ada dokumen yang sesuai dengan filter.' : 'Belum ada dokumen untuk ditampilkan.' }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="table-footer">
                    <span class="pagination-summary">
                        @if ($dokumen->total() > 0)
                            Menampilkan <strong>{{ $dokumen->firstItem() }}–{{ $dokumen->lastItem() }}</strong> dari <strong>{{ number_format($dokumen->total(), 0, ',', '.') }}</strong> total data
                        @else
                            Menampilkan <strong>0</strong> dari <strong>0</strong> total data
                        @endif
                    </span>
                    <div class="pagination">{{ $dokumen->onEachSide(1)->links() }}</div>
                </div>
            </section>

            <aside class="integrity" aria-label="Integritas dokumen">
                <div class="integrity-copy">
                    <span class="integrity-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 8 3v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3Z"/><path d="m9 12 2 2 4-4"/></svg></span>
                    <span><strong>Integritas Audit Internal SPI PDAM Tirta Pinang</strong><span>Setiap berkas audit dilindungi tanda tangan elektronik dan checksum hash SHA-256 untuk menjamin orisinalitas data bukti pengawasan.</span></span>
                </div>
                <span class="button" aria-disabled="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/></svg>Validasi Integritas Dokumen</span>
            </aside>

@endsection
