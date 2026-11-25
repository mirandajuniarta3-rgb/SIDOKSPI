@extends('layouts.app')

@section('title', 'Detail Dokumen - SIDOKSPI')
@section('active-menu', 'documents')
@section('breadcrumb', 'Beranda Administrasi')
@section('body-class', 'document-detail-page')

@php
    $statusLabel = match ($dokumen->status) {
        'draft' => 'Draft',
        'final' => 'Aktif',
        'arsip' => 'Diarsipkan',
        default => ucfirst($dokumen->status),
    };

    $privacyLabel = match ($dokumen->tingkat_kerahasiaan) {
        'terbatas' => 'Rahasia / Terbatas',
        'umum' => 'Umum',
        default => ucfirst($dokumen->tingkat_kerahasiaan ?? 'Tidak ditentukan'),
    };

    $uploaderName = $dokumen->uploader?->nama_lengkap
        ?? $dokumen->uploader?->name
        ?? 'Tidak diketahui';

    $uploaderInitials = collect(explode(' ', trim($uploaderName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');

    $fileExtension = strtoupper(pathinfo($dokumen->nama_file ?? '', PATHINFO_EXTENSION) ?: 'FILE');
    $fileSize = $dokumen->ukuran_kb
        ? number_format($dokumen->ukuran_kb / 1024, 1, ',', '.') . ' MB'
        : '—';
@endphp

@push('styles')
<style>
    body.document-detail-page .page-content {
        padding: 4px 14px 18px;
    }

    body.document-detail-page .topbar {
        min-height: 44px;
        gap: 8px;
        padding: 5px 14px;
    }

    body.document-detail-page .top-search {
        width: min(270px, 30vw);
        height: 30px;
        min-width: 100px;
    }

    .document-detail {
        display: grid;
        gap: 10px;
        color: #263650;
    }

    .detail-topline,
    .detail-identity {
        display: flex;
        align-items: center;
        gap: 7px;
        flex-wrap: wrap;
    }

    .detail-topline {
        min-height: 20px;
    }

    .detail-back {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        color: #2462bd;
        font-size: 10px;
        font-weight: 650;
        text-decoration: none;
        white-space: nowrap;
    }

    .detail-back svg {
        width: 12px;
        height: 12px;
    }

    .detail-number,
    .detail-badge {
        display: inline-flex;
        min-height: 20px;
        align-items: center;
        gap: 4px;
        padding: 3px 7px;
        border-radius: 6px;
        font-size: 9px;
        font-weight: 750;
        white-space: nowrap;
    }

    .detail-number {
        background: #eaf0ff;
        color: #2456a6;
    }

    .detail-badge.is-active {
        background: #e4faf3;
        color: #087c67;
    }

    .detail-badge.is-restricted {
        background: #fff0ef;
        color: #ba3e45;
    }

    .detail-badge.is-archived {
        background: #fff5df;
        color: #94620a;
    }

    .detail-heading-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .detail-heading-row h1 {
        min-width: 0;
        margin: 0;
        overflow: hidden;
        color: #17243b;
        font-size: clamp(18px, 2.1vw, 22px);
        font-weight: 800;
        letter-spacing: -.035em;
        line-height: 1.25;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .detail-header-actions {
        display: flex;
        flex: 0 0 auto;
        align-items: center;
        gap: 6px;
    }

    .detail-header-actions form,
    .document-actions-content form {
        margin: 0;
    }

    .detail-button {
        display: inline-flex;
        min-height: 32px;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 6px 9px;
        border: 1px solid #e5eaf2;
        border-radius: 7px;
        background: #fff;
        color: #43516a;
        font-size: 10px;
        font-weight: 700;
        line-height: 1.2;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
    }

    .detail-button:hover {
        border-color: #cbd9ef;
        background: #f7f9ff;
        color: #0753c5;
    }

    .detail-button svg {
        width: 13px;
        height: 13px;
        flex: 0 0 13px;
    }

    .detail-button.is-primary {
        border-color: #0753c5;
        background: #0753c5;
        color: #fff;
    }

    .detail-button.is-danger {
        color: #c33b45;
    }

    .detail-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.4fr) minmax(290px, .98fr);
        align-items: start;
        gap: 14px;
    }

    .preview-card,
    .metadata-card,
    .integrity-card,
    .document-actions-card {
        min-width: 0;
        overflow: hidden;
        border: 1px solid #e8ecf4;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(27, 46, 82, .035);
    }

    .preview-toolbar {
        display: flex;
        min-height: 38px;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 6px 10px;
        border-bottom: 1px solid #e8ecf4;
        color: #52617a;
        font-size: 10px;
    }

    .preview-file {
        min-width: 0;
        overflow: hidden;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .preview-type {
        flex: 0 0 auto;
        padding: 3px 6px;
        border-radius: 5px;
        background: #edf2ff;
        color: #315a9b;
        font-size: 9px;
        font-weight: 750;
    }

    .preview-frame-wrap {
        padding: 10px;
        background: #eef1f8;
    }

    .preview-frame {
        display: block;
        width: 100%;
        height: min(72vh, 830px);
        min-height: 540px;
        border: 0;
        border-radius: 2px;
        background: #fff;
    }

    .preview-fallback {
        display: flex;
        min-height: 540px;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 9px;
        padding: 24px;
        background: #f4f6fb;
        color: #748198;
        text-align: center;
    }

    .preview-fallback svg {
        width: 40px;
        height: 40px;
        color: #7890b4;
    }

    .preview-fallback strong {
        color: #35445e;
        font-size: 13px;
    }

    .preview-fallback p {
        max-width: 320px;
        margin: 0;
        font-size: 11px;
        line-height: 1.6;
    }

    .preview-footnote {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        color: #6b7890;
        font-size: 9px;
    }

    .preview-indicator {
        width: 6px;
        height: 6px;
        flex: 0 0 6px;
        border-radius: 50%;
        background: #159b83;
    }

    .detail-sidebar {
        display: grid;
        gap: 10px;
    }

    .detail-card-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 11px 12px 8px;
    }

    .detail-card-heading h2 {
        display: flex;
        align-items: center;
        gap: 6px;
        margin: 0;
        color: #263650;
        font-size: 12px;
        font-weight: 800;
    }

    .detail-card-heading svg {
        width: 14px;
        height: 14px;
        flex: 0 0 14px;
        color: #2462bd;
    }

    .category-chip {
        flex: 0 0 auto;
        padding: 3px 6px;
        border-radius: 5px;
        background: #edf2ff;
        color: #315a9b;
        font-size: 9px;
        font-weight: 750;
    }

    .metadata-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px 12px;
        margin: 0;
        padding: 3px 12px 12px;
    }

    .metadata-item {
        min-width: 0;
        margin: 0;
    }

    .metadata-item.is-wide {
        grid-column: 1 / -1;
    }

    .metadata-label {
        display: block;
        margin-bottom: 3px;
        color: #8290a5;
        font-size: 8px;
        font-weight: 750;
        letter-spacing: .04em;
        text-transform: uppercase;
    }

    .metadata-value {
        margin: 0;
        color: #34435d;
        font-size: 10px;
        font-weight: 650;
        line-height: 1.45;
        overflow-wrap: anywhere;
    }

    .metadata-value.is-description {
        color: #5e6b80;
        font-weight: 500;
    }

    .uploader {
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .uploader-avatar {
        display: grid;
        width: 26px;
        height: 26px;
        flex: 0 0 26px;
        place-items: center;
        border-radius: 50%;
        background: #eaf0ff;
        color: #2456a6;
        font-size: 9px;
        font-weight: 800;
    }

    .uploader-name,
    .uploader-field {
        display: block;
    }

    .uploader-name {
        color: #34435d;
        font-size: 9px;
        font-weight: 750;
    }

    .uploader-field {
        margin-top: 2px;
        color: #8290a5;
        font-size: 8px;
    }

    .inline-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 6px;
        border-radius: 999px;
        background: #e4faf3;
        color: #087c67;
        font-size: 8px;
        font-weight: 750;
    }

    .inline-status.is-archived {
        background: #fff5df;
        color: #94620a;
    }

    .integrity-content,
    .document-actions-content {
        display: grid;
        gap: 7px;
        padding: 0 10px 10px;
    }

    .checksum-box,
    .last-download {
        padding: 7px 8px;
        border: 1px solid #e9edf7;
        border-radius: 7px;
        background: #f8f9fd;
    }

    .checksum-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 7px;
        margin-bottom: 4px;
        color: #8290a5;
        font-size: 8px;
        font-weight: 750;
        text-transform: uppercase;
    }

    .checksum-state {
        color: #11836f;
        text-transform: none;
    }

    .checksum-value {
        display: block;
        color: #34435d;
        font-family: ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
        font-size: 8px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .last-download {
        display: flex;
        align-items: flex-start;
        gap: 6px;
        color: #5e6b80;
        font-size: 9px;
        line-height: 1.45;
    }

    .last-download svg {
        width: 13px;
        height: 13px;
        flex: 0 0 13px;
        color: #2462bd;
    }

    .last-download strong {
        color: #34435d;
    }

    .document-actions-content .detail-button {
        width: 100%;
        min-height: 34px;
    }

    .document-actions-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 6px;
    }

    .audit-link {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        padding: 1px 2px 0;
        color: #2462bd;
        font-size: 9px;
        font-weight: 700;
        text-decoration: none;
    }

    .audit-link:hover {
        text-decoration: underline;
    }

    @media (max-width: 850px) {
        body.document-detail-page .topbar {
            gap: 5px;
            padding-right: 8px;
            padding-left: 8px;
        }

        body.document-detail-page .top-search {
            width: min(160px, 24vw);
            min-width: 85px;
        }

        body.document-detail-page .role-badge {
            display: none;
        }

        .detail-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        .detail-heading-row {
            align-items: flex-start;
            flex-direction: column;
        }

        .detail-heading-row h1 {
            width: 100%;
            white-space: normal;
        }

        .detail-header-actions {
            width: 100%;
            flex-wrap: wrap;
        }

        .preview-frame {
            height: 62vh;
            min-height: 420px;
        }
    }
</style>
@endpush

@section('content')
<main class="document-detail" aria-labelledby="documentTitle">
    <header>
        <div class="detail-topline">
            <a class="detail-back" href="{{ route('dokumen.index') }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 18-6-6 6-6"/>
                    <path d="M20 12H9"/>
                </svg>
                Kembali ke Daftar Dokumen
            </a>

            <div class="detail-identity">
                <span class="detail-number">{{ $dokumen->nomor_dokumen ?: 'Nomor belum tersedia' }}</span>
                <span class="detail-badge {{ $dokumen->status === 'arsip' ? 'is-archived' : 'is-active' }}">
                    {{ $statusLabel }}
                </span>
                <span class="detail-badge is-restricted">{{ $privacyLabel }}</span>
            </div>
        </div>

        <div class="detail-heading-row">
            <h1 id="documentTitle">Detail Dokumen Pengawasan: {{ $dokumen->judul }}</h1>

            <div class="detail-header-actions">
                @if ($dokumen->file_available)
                    <a class="detail-button is-primary" href="{{ route('dokumen.download', $dokumen) }}">
                        Unduh Berkas
                    </a>
                @endif

                @if ($dokumen->status === 'arsip')
                    <form action="{{ route('dokumen.restore', $dokumen) }}" method="POST">
                        @csrf
                        <button class="detail-button" type="submit">Pulihkan</button>
                    </form>
                @else
                    <form action="{{ route('dokumen.archive', $dokumen) }}" method="POST"
                          onsubmit="return confirm('Arsipkan dokumen ini?');">
                        @csrf
                        <button class="detail-button" type="submit">Arsipkan</button>
                    </form>
                @endif

                <form action="{{ route('dokumen.destroy', $dokumen) }}" method="POST"
                      onsubmit="return confirm('Hapus dokumen ini secara permanen?');">
                    @csrf
                    @method('DELETE')
                    <button class="detail-button is-danger" type="submit">Hapus</button>
                </form>
            </div>
        </div>
    </header>

    <div class="detail-layout">
        <section class="preview-card" aria-label="Pratinjau dokumen">
            <div class="preview-toolbar">
                <span class="preview-file">{{ $dokumen->nama_file ?: 'Berkas dokumen' }}</span>
                <span class="preview-type">{{ $fileExtension }}</span>
            </div>

            @if ($dokumen->file_available && $fileExtension === 'PDF')
                <div class="preview-frame-wrap">
                    <iframe
                        class="preview-frame"
                        src="{{ route('dokumen.preview', $dokumen) }}"
                        title="Pratinjau {{ $dokumen->nama_file }}"
                        loading="lazy"
                    ></iframe>
                </div>
                <div class="preview-footnote">
                    <span class="preview-indicator"></span>
                    Pratinjau PDF dokumen
                </div>
            @else
                <div class="preview-fallback">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M6 2h8l5 5v15H6a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2Z"/>
                        <path d="M14 2v6h6M8 13h8m-8 4h8"/>
                    </svg>
                    @if ($dokumen->file_available)
                        <strong>Pratinjau tidak tersedia</strong>
                        <p>Format berkas ini tidak dapat ditampilkan langsung. Unduh berkas untuk membukanya.</p>
                    @else
                        <strong>Berkas tidak tersedia</strong>
                        <p>File mungkin telah dipindahkan atau dihapus dari penyimpanan.</p>
                    @endif
                </div>
            @endif
        </section>

        <aside class="detail-sidebar" aria-label="Informasi dokumen">
            <section class="metadata-card">
                <div class="detail-card-heading">
                    <h2>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 11v5m0-8h.01"/>
                        </svg>
                        Informasi &amp; Metadata Dokumen
                    </h2>
                    <span class="category-chip">{{ $dokumen->kategori?->kode_kategori ?? 'Dokumen' }}</span>
                </div>

                <dl class="metadata-grid">
                    <div class="metadata-item is-wide">
                        <dt class="metadata-label">Nomor Dokumen</dt>
                        <dd class="metadata-value">{{ $dokumen->nomor_dokumen ?: '—' }}</dd>
                    </div>

                    <div class="metadata-item is-wide">
                        <dt class="metadata-label">Judul Dokumen</dt>
                        <dd class="metadata-value">{{ $dokumen->judul }}</dd>
                    </div>

                    <div class="metadata-item">
                        <dt class="metadata-label">Kategori</dt>
                        <dd class="metadata-value">{{ $dokumen->kategori?->nama_kategori ?? 'Tanpa kategori' }}</dd>
                    </div>

                    <div class="metadata-item">
                        <dt class="metadata-label">Tahun Anggaran</dt>
                        <dd class="metadata-value">{{ $dokumen->tahun ?? '—' }}</dd>
                    </div>

                    <div class="metadata-item">
                        <dt class="metadata-label">Tanggal Dokumen</dt>
                        <dd class="metadata-value">
                            {{ $dokumen->tanggal_dokumen?->locale('id')->translatedFormat('d F Y') ?? '—' }}
                        </dd>
                    </div>

                    <div class="metadata-item">
                        <dt class="metadata-label">Tanggal Unggah</dt>
                        <dd class="metadata-value">
                            {{ $dokumen->created_at?->locale('id')->translatedFormat('d M Y, H:i') ?? '—' }} WIB
                        </dd>
                    </div>

                    <div class="metadata-item is-wide">
                        <dt class="metadata-label">Deskripsi Pengawasan</dt>
                        <dd class="metadata-value is-description">
                            {{ $dokumen->deskripsi ?: 'Tidak ada deskripsi.' }}
                        </dd>
                    </div>

                    <div class="metadata-item is-wide">
                        <dt class="metadata-label">Pengunggah Dokumen</dt>
                        <dd class="metadata-value uploader">
                            <span class="uploader-avatar">{{ mb_strtoupper($uploaderInitials ?: 'U') }}</span>
                            <span>
                                <span class="uploader-name">{{ $uploaderName }}</span>
                                <span class="uploader-field">{{ $dokumen->bidang ?: 'Bidang tidak dicantumkan' }}</span>
                            </span>
                        </dd>
                    </div>

                    <div class="metadata-item">
                        <dt class="metadata-label">Status Dokumen</dt>
                        <dd class="metadata-value">
                            <span class="inline-status {{ $dokumen->status === 'arsip' ? 'is-archived' : '' }}">
                                {{ $statusLabel }}
                            </span>
                        </dd>
                    </div>

                    <div class="metadata-item">
                        <dt class="metadata-label">Ukuran File</dt>
                        <dd class="metadata-value">{{ $fileSize }}</dd>
                    </div>

                    @if ($dokumen->status === 'arsip')
                        <div class="metadata-item is-wide">
                            <dt class="metadata-label">Tanggal Diarsipkan</dt>
                            <dd class="metadata-value">
                                {{ $dokumen->archived_at?->locale('id')->translatedFormat('d F Y, H:i') ?? '—' }}
                            </dd>
                        </div>
                    @endif
                </dl>
            </section>

            <section class="integrity-card">
                <div class="detail-card-heading">
                    <h2>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="m12 3 8 3v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3Z"/>
                            <path d="m9 12 2 2 4-4"/>
                        </svg>
                        Integritas Dokumen &amp; Jejak Audit
                    </h2>
                </div>

                <div class="integrity-content">
                    <div class="checksum-box">
                        <div class="checksum-heading">
                            <span>Checksum SHA-256</span>
                            <span class="checksum-state">{{ $fileChecksum ? 'Tersedia' : 'Tidak tersedia' }}</span>
                        </div>
                        <code class="checksum-value">{{ $fileChecksum ?: 'Checksum tidak dapat dihitung.' }}</code>
                    </div>

                    <div class="last-download">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                             stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="9"/>
                            <path d="M12 7v5l3 2"/>
                        </svg>
                        <span>
                            <strong>Riwayat akses terakhir</strong><br>
                            @if ($lastDownload)
                                Terakhir diunduh oleh
                                {{ $lastDownload->user?->nama_lengkap ?? $lastDownload->user?->name ?? 'Pengguna' }}
                                ({{ $lastDownload->created_at?->locale('id')->translatedFormat('d M Y, H:i') }} WIB)
                            @else
                                Belum ada unduhan tercatat.
                            @endif
                        </span>
                    </div>

                    <a class="audit-link" href="{{ route('log-aktivitas.index') }}">
                        Lihat Log Aktivitas
                        <span aria-hidden="true">›</span>
                    </a>
                </div>
            </section>

            <section class="document-actions-card">
                <div class="detail-card-heading">
                    <h2>Tindakan Dokumen</h2>
                </div>

                <div class="document-actions-content">
                    @if ($dokumen->file_available)
                        <a class="detail-button is-primary" href="{{ route('dokumen.download', $dokumen) }}">
                            Unduh PDF Resmi
                        </a>
                    @endif

                    <div class="document-actions-row">
                        @if ($dokumen->status === 'arsip')
                            <form action="{{ route('dokumen.restore', $dokumen) }}" method="POST">
                                @csrf
                                <button class="detail-button" type="submit">Pulihkan Dokumen</button>
                            </form>
                        @else
                            <form action="{{ route('dokumen.archive', $dokumen) }}" method="POST"
                                  onsubmit="return confirm('Arsipkan dokumen ini?');">
                                @csrf
                                <button class="detail-button" type="submit">Arsipkan Dokumen</button>
                            </form>
                        @endif

                        <form action="{{ route('dokumen.destroy', $dokumen) }}" method="POST"
                              onsubmit="return confirm('Hapus dokumen ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button class="detail-button is-danger" type="submit">Hapus Dokumen</button>
                        </form>
                    </div>
                </div>
            </section>
        </aside>
    </div>
</main>
@endsection
