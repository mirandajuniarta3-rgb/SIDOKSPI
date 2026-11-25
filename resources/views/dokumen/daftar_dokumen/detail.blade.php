
@extends('layouts.app')

@section('title', 'Detail Dokumen - SIDOKSPI')

@section('active-menu', 'documents')

@section('breadcrumb', 'Dokumen / Detail Dokumen')

@push('styles')
<style>
    .document-detail {
        width: 100%;
        max-width: 1500px;
        margin: 0 auto;
        color: #17243b;
    }

    .detail-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 18px;
        margin-bottom: 22px;
    }

    .detail-heading h1 {
        margin: 5px 0 7px;
        font-size: 25px;
        font-weight: 750;
        color: #17243b;
    }

    .detail-heading p {
        margin: 0;
        color: #78839a;
        font-size: 13px;
    }

    .detail-eyebrow {
        color: #0753c5;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
    }

    /* TOMBOL KEMBALI BAGIAN ATAS */
    .detail-back {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 40px;
        padding: 10px 17px;
        margin-bottom: 18px;
        color: #0753c5;
        background: #edf4ff;
        border: 1px solid #d5e5ff;
        border-radius: 9px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 700;
        transition: all .25s ease;
    }

    .detail-back:hover {
        color: #fff;
        background: #0753c5;
        border-color: #0753c5;
        transform: translateX(-3px);
        box-shadow: 0 5px 13px rgba(7, 83, 197, .18);
    }

    .detail-heading-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }

    .detail-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 36px;
        padding: 9px 13px;
        border: 1px solid #dce4f0;
        border-radius: 8px;
        background: #fff;
        color: #34445c;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .detail-btn:hover {
        background: #f3f6fc;
        border-color: #b9c9e3;
    }

    .detail-btn.primary {
        background: #0753c5;
        border-color: #0753c5;
        color: #fff;
    }

    .detail-btn.primary:hover {
        background: #0645a5;
    }

    .detail-btn.danger {
        color: #d92d45;
        border-color: #f2cbd1;
    }

    .detail-btn.danger:hover {
        background: #fff1f2;
    }

    .detail-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.65fr) minmax(310px, .9fr);
        gap: 18px;
        align-items: start;
    }

    .preview-card,
    .information-card {
        background: #fff;
        border: 1px solid #e5eaf2;
        border-radius: 13px;
        box-shadow: 0 4px 15px rgba(27, 48, 82, .045);
        overflow: hidden;
    }

    .preview-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 13px 16px;
        border-bottom: 1px solid #e9edf4;
        background: #fff;
    }

    .preview-title {
        display: flex;
        align-items: center;
        gap: 9px;
        font-size: 12px;
        font-weight: 750;
        color: #263650;
    }

    .preview-title-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #edf4ff;
        color: #0753c5;
    }

    .preview-file-name {
        max-width: 230px;
        overflow: hidden;
        color: #7b879b;
        font-size: 10px;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .preview-content {
        min-height: 650px;
        padding: 14px;
        background: #edf1f8;
    }

    .preview-frame {
        display: block;
        width: 100%;
        height: 760px;
        border: 0;
        border-radius: 5px;
        background: #fff;
    }

    .preview-empty {
        display: flex;
        min-height: 550px;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 35px;
        text-align: center;
        background: #fff;
        border: 1px dashed #d5deeb;
        border-radius: 9px;
    }

    .preview-empty-icon {
        display: flex;
        width: 65px;
        height: 65px;
        align-items: center;
        justify-content: center;
        margin-bottom: 17px;
        border-radius: 18px;
        background: #edf4ff;
        color: #0753c5;
        font-size: 27px;
    }

    .preview-empty h3 {
        margin: 0 0 8px;
        color: #243247;
        font-size: 16px;
    }

    .preview-empty p {
        max-width: 340px;
        margin: 0 0 18px;
        color: #7b879b;
        font-size: 12px;
        line-height: 1.7;
    }

    .detail-right {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .information-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding: 15px 17px;
        border-bottom: 1px solid #edf0f5;
    }

    .information-heading {
        display: flex;
        align-items: center;
        gap: 9px;
        color: #263650;
        font-size: 13px;
        font-weight: 750;
    }

    .information-heading-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 29px;
        height: 29px;
        border-radius: 8px;
        background: #edf4ff;
        color: #0753c5;
    }

    .information-body {
        padding: 16px 17px;
    }

    .metadata-number {
        margin-bottom: 16px;
    }

    .metadata-label {
        display: block;
        margin-bottom: 5px;
        color: #8994a7;
        font-size: 9px;
        font-weight: 750;
        letter-spacing: .55px;
        text-transform: uppercase;
    }

    .metadata-value {
        color: #253650;
        font-size: 12px;
        font-weight: 650;
        line-height: 1.6;
        overflow-wrap: anywhere;
    }

    .metadata-number .metadata-value {
        color: #0753c5;
        font-size: 13px;
    }

    .metadata-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 15px 12px;
    }

    .metadata-item {
        min-width: 0;
    }

    .metadata-description {
        grid-column: 1 / -1;
        padding-top: 13px;
        border-top: 1px solid #edf0f5;
    }

    .metadata-uploader {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .uploader-avatar {
        display: flex;
        width: 32px;
        height: 32px;
        flex-shrink: 0;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #e8f0ff;
        color: #0753c5;
        font-size: 13px;
        font-weight: 800;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 9px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 750;
    }

    .status-final {
        color: #16845c;
        background: #e7f8ef;
    }

    .status-draft {
        color: #b7791f;
        background: #fff5df;
    }

    .status-arsip {
        color: #64748b;
        background: #edf0f5;
    }

    .integrity-content {
        display: flex;
        flex-direction: column;
        gap: 13px;
    }

    .integrity-row {
        padding-bottom: 12px;
        border-bottom: 1px solid #edf0f5;
    }

    .integrity-row:last-child {
        padding-bottom: 0;
        border-bottom: 0;
    }

    .integrity-value {
        padding: 9px;
        border: 1px solid #e5eaf2;
        border-radius: 7px;
        background: #f8faff;
        color: #52627a;
        font-family: monospace;
        font-size: 10px;
        line-height: 1.7;
        overflow-wrap: anywhere;
    }

    .integrity-valid {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #16845c;
        font-size: 11px;
        font-weight: 700;
    }

    .detail-actions-panel {
        padding: 16px 17px;
    }

    .detail-actions-panel .information-heading {
        margin-bottom: 14px;
    }

    .detail-action-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .detail-action-list .detail-btn {
        width: 100%;
        min-height: 37px;
    }

    .detail-action-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .detail-action-row form {
        min-width: 0;
    }

    .detail-action-row form .detail-btn {
        width: 100%;
    }

    /* TOMBOL KEMBALI DI PANEL TINDAKAN */
    .detail-action-list .btn-back-document {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        width: 100%;
        min-height: 42px;
        padding: 11px 14px;
        color: #0753c5;
        background: #edf4ff;
        border: 1px solid #d5e5ff;
        border-radius: 9px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: all .25s ease;
    }

    .detail-action-list .btn-back-document:hover {
        color: #fff;
        background: #0753c5;
        border-color: #0753c5;
        transform: translateY(-2px);
        box-shadow: 0 5px 13px rgba(7, 83, 197, .18);
    }

    .detail-action-note {
        margin: 12px 0 0;
        color: #8a95a8;
        font-size: 10px;
        line-height: 1.6;
        text-align: center;
    }

    @media (max-width: 1050px) {
        .detail-layout {
            grid-template-columns: minmax(0, 1.2fr) minmax(280px, .9fr);
        }

        .preview-frame {
            height: 650px;
        }
    }

    @media (max-width: 800px) {
        .detail-layout {
            grid-template-columns: 1fr;
        }

        .preview-content {
            min-height: 450px;
        }

        .preview-frame {
            height: 650px;
        }
    }

    @media (max-width: 550px) {
        .detail-heading {
            flex-direction: column;
        }

        .detail-heading h1 {
            font-size: 21px;
        }

        .detail-heading-actions {
            width: 100%;
        }

        .detail-btn {
            flex: 1;
        }

        .metadata-grid {
            grid-template-columns: 1fr 1fr;
        }

        .preview-content {
            padding: 8px;
        }

        .preview-frame {
            height: 500px;
        }
    }
</style>
@endpush

@section('content')

@php
    $statusClass = match ($dokumen->status) {
        'final' => 'status-final',
        'arsip' => 'status-arsip',
        default => 'status-draft',
    };

    $namaPengunggah = $dokumen->uploader?->nama_lengkap
        ?? 'Tidak diketahui';

    $ekstensi = strtolower(pathinfo($dokumen->nama_file ?? '', PATHINFO_EXTENSION));

    $isPdf = $ekstensi === 'pdf';

    $fileTersedia = (bool) $dokumen->file_available;
@endphp

<div class="document-detail">

    {{-- TOMBOL KEMBALI BAGIAN ATAS --}}
    <a href="{{ route('dokumen.index') }}" class="detail-back">
        <span>←</span>
        <span>Kembali ke Daftar Dokumen</span>
    </a>

    <section class="detail-heading">

        <div>
            <div class="detail-eyebrow">
                Portal SPI / Beranda Administrasi
            </div>

            <h1>Detail Dokumen</h1>

            <p>
                Informasi lengkap, metadata, dan pratinjau dokumen pengawasan SPI.
            </p>
        </div>

        <div class="detail-heading-actions">

            @if ($fileTersedia && $isPdf)
                <a href="{{ route('dokumen.preview', $dokumen->id) }}"
                   target="_blank"
                   class="detail-btn primary">
                    ↗ Preview PDF
                </a>
            @endif

            @if ($dokumen->status === 'arsip')
                <a href="{{ route('dokumen.download', $dokumen->id) }}"
                   class="detail-btn">
                    ↓ Unduh Berkas
                </a>
            @endif

        </div>

    </section>

    <div class="detail-layout">

        {{-- KOLOM KIRI: PREVIEW DOKUMEN --}}
        <section class="preview-card">

            <div class="preview-toolbar">
                <div class="preview-title">
                    <span class="preview-title-icon">▤</span>
                    Preview Dokumen
                </div>

                <span class="preview-file-name">
                    {{ $dokumen->nama_file ?? 'Tidak ada nama file' }}
                </span>
            </div>

            <div class="preview-content">

                @if (! $fileTersedia)

                    <div class="preview-empty">
                        <div class="preview-empty-icon">!</div>
                        <h3>File Tidak Ditemukan</h3>
                        <p>
                            File dokumen tidak tersedia pada penyimpanan.
                            Silakan periksa kembali berkas yang diunggah.
                        </p>
                    </div>

                @elseif ($isPdf)

                    <iframe
                        class="preview-frame"
                        src="{{ route('dokumen.preview', $dokumen->id) }}"
                        title="Preview PDF {{ $dokumen->nama_file }}"
                    ></iframe>

                @else

                    <div class="preview-empty">
                        <div class="preview-empty-icon">▤</div>
                        <h3>Preview Tidak Tersedia</h3>
                        <p>
                            Format file {{ strtoupper($ekstensi) }} belum
                            dapat ditampilkan langsung di halaman ini.
                            Informasi file tetap tersedia di panel metadata.
                        </p>
                    </div>

                @endif

            </div>

        </section>

        {{-- KOLOM KANAN --}}
        <aside class="detail-right">

            {{-- INFORMASI DAN METADATA --}}
            <section class="information-card">

                <div class="information-header">
                    <div class="information-heading">
                        <span class="information-heading-icon">▣</span>
                        Informasi & Metadata Dokumen
                    </div>

                    <span class="status-badge {{ $statusClass }}">
                        {{ ucfirst($dokumen->status) }}
                    </span>
                </div>

                <div class="information-body">

                    <div class="metadata-number">
                        <span class="metadata-label">Nomor Dokumen</span>
                        <div class="metadata-value">
                            {{ $dokumen->nomor_dokumen ?: '—' }}
                        </div>
                    </div>

                    <div class="metadata-item" style="margin-bottom: 17px;">
                        <span class="metadata-label">Judul Dokumen</span>
                        <div class="metadata-value">
                            {{ $dokumen->judul }}
                        </div>
                    </div>

                    <div class="metadata-grid">

                        <div class="metadata-item">
                            <span class="metadata-label">Kategori</span>
                            <div class="metadata-value">
                                {{ $dokumen->kategori?->nama_kategori ?? 'Tanpa kategori' }}
                            </div>
                        </div>

                        <div class="metadata-item">
                            <span class="metadata-label">Tahun</span>
                            <div class="metadata-value">
                                {{ $dokumen->tahun ?? '—' }}
                            </div>
                        </div>

                        <div class="metadata-item">
                            <span class="metadata-label">Tanggal Dokumen</span>
                            <div class="metadata-value">
                                {{ $dokumen->tanggal_dokumen?->locale('id')->translatedFormat('d F Y') ?? '—' }}
                            </div>
                        </div>

                        <div class="metadata-item">
                            <span class="metadata-label">Ukuran File</span>
                            <div class="metadata-value">
                                {{ $dokumen->ukuran_kb
                                    ? number_format($dokumen->ukuran_kb / 1024, 2, ',', '.') . ' MB'
                                    : '—' }}
                            </div>
                        </div>

                        <div class="metadata-item">
                            <span class="metadata-label">Nama File</span>
                            <div class="metadata-value">
                                {{ $dokumen->nama_file ?? '—' }}
                            </div>
                        </div>

                        <div class="metadata-item">
                            <span class="metadata-label">Kerahasiaan</span>
                            <div class="metadata-value">
                                {{ ucfirst($dokumen->tingkat_kerahasiaan ?? '—') }}
                            </div>
                        </div>

                        <div class="metadata-item" style="grid-column: 1 / -1;">
                            <span class="metadata-label">Pengunggah Dokumen</span>

                            <div class="metadata-uploader">
                                <span class="uploader-avatar">
                                    {{ strtoupper(substr($namaPengunggah, 0, 1)) }}
                                </span>

                                <div class="metadata-value">
                                    {{ $namaPengunggah }}
                                </div>
                            </div>
                        </div>

                        @if ($dokumen->status === 'arsip')
                            <div class="metadata-item" style="grid-column: 1 / -1;">
                                <span class="metadata-label">Tanggal Diarsipkan</span>
                                <div class="metadata-value">
                                    {{ $dokumen->archived_at?->locale('id')->translatedFormat('d F Y H:i') ?? '—' }}
                                </div>
                            </div>
                        @endif

                        <div class="metadata-description">
                            <span class="metadata-label">Deskripsi Dokumen</span>
                            <div class="metadata-value">
                                {{ $dokumen->deskripsi ?: 'Tidak ada deskripsi dokumen.' }}
                            </div>
                        </div>

                    </div>
                </div>

            </section>

            {{-- INTEGRITAS DOKUMEN --}}
            <section class="information-card">

                <div class="information-header">
                    <div class="information-heading">
                        <span class="information-heading-icon">✓</span>
                        Integritas Dokumen
                    </div>

                    @if ($fileTersedia)
                        <span class="integrity-valid">● File tersedia</span>
                    @else
                        <span class="status-badge status-arsip">File tidak tersedia</span>
                    @endif
                </div>

                <div class="information-body integrity-content">

                    <div class="integrity-row">
                        <span class="metadata-label">Checksum SHA-256</span>

                        <div class="integrity-value">
                            {{ $dokumen->checksum_sha256 ?? 'Checksum belum tersedia' }}
                        </div>
                    </div>

                    <div class="integrity-row">
                        <span class="metadata-label">Status Penyimpanan</span>

                        <div class="metadata-value">
                            {{ $fileTersedia ? 'File ditemukan pada penyimpanan sistem.' : 'File tidak ditemukan pada penyimpanan sistem.' }}
                        </div>
                    </div>

                    <div class="integrity-row">
                        <span class="metadata-label">Terakhir Diperbarui</span>

                        <div class="metadata-value">
                            {{ $dokumen->updated_at?->locale('id')->translatedFormat('d F Y, H:i') ?? '—' }}
                        </div>
                    </div>

                </div>

            </section>

            {{-- TINDAKAN DOKUMEN --}}
            <section class="information-card detail-actions-panel">

                <div class="information-heading">
                    <span class="information-heading-icon">⚙</span>
                    Tindakan Dokumen
                </div>

                <div class="detail-action-list">

                    @if ($dokumen->status === 'arsip')
                        <a href="{{ route('dokumen.download', $dokumen->id) }}"
                           class="detail-btn primary">
                            ↓ Unduh PDF Resmi
                        </a>
                    @elseif ($fileTersedia && $isPdf)
                        <a href="{{ route('dokumen.preview', $dokumen->id) }}"
                           target="_blank"
                           class="detail-btn primary">
                            ↗ Buka PDF
                        </a>
                    @endif

                    @if ($dokumen->status !== 'arsip')
                        <form action="{{ route('dokumen.archive', $dokumen->id) }}"
                              method="POST"
                              onsubmit="return confirm('Apakah kamu yakin ingin mengarsipkan dokumen ini?')">
                            @csrf

                            <button type="submit" class="detail-btn" style="width:100%;">
                                ▣ Arsipkan Dokumen
                            </button>
                        </form>
                    @endif

                    <form action="{{ route('dokumen.destroy', $dokumen->id) }}"
                          method="POST"
                          onsubmit="return confirm('Apakah kamu yakin ingin menghapus dokumen ini secara permanen? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="detail-btn danger">
                            Hapus Dokumen
                        </button>
                    </form>

                    {{-- TOMBOL KEMBALI --}}
                    <a href="{{ route('dokumen.index') }}" class="btn-back-document">
                        <span>←</span>
                        <span>Kembali ke Daftar Dokumen</span>
                    </a>

                </div>

                <p class="detail-action-note">
                    Pastikan tindakan yang dipilih sudah benar sebelum melanjutkan.
                </p>

            </section>

        </aside>

    </div>

</div>

@endsection
