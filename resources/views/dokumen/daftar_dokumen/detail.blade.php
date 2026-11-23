@extends('layouts.app')

@section('title', 'Detail Dokumen - SIDOKSPI')

@section('active-menu', 'documents')

@section('breadcrumb', 'Dokumen / Detail Dokumen')

@push('styles')
<style>
    .detail-panel {
        background: #fff;
        border: 1px solid #e5eaf1;
        border-radius: 14px;
        padding: 25px;
        margin-top: 20px;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .detail-item {
        padding-bottom: 14px;
        border-bottom: 1px solid #edf0f5;
    }

    .detail-label {
        display: block;
        color: #78839a;
        font-size: 12px;
        margin-bottom: 7px;
    }

    .detail-value {
        color: #243247;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    .detail-description {
        grid-column: 1 / -1;
    }

    .detail-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 25px;
    }

    @media (max-width: 650px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<section class="page-heading">
    <div>
        <div class="eyebrow">
            Portal SPI <span aria-hidden="true">›</span> Detail Dokumen
        </div>

        <h1 id="page-title">Detail Dokumen</h1>

        <p class="page-description">
            Informasi lengkap dokumen pengawasan SPI.
        </p>
    </div>
</section>

<section class="detail-panel">

    <div class="detail-grid">

        <div class="detail-item">
            <span class="detail-label">Nomor Dokumen</span>
            <div class="detail-value">
                {{ $dokumen->nomor_dokumen ?: '—' }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Judul Dokumen</span>
            <div class="detail-value">
                {{ $dokumen->judul }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Kategori</span>
            <div class="detail-value">
                {{ $dokumen->kategori?->nama_kategori ?? 'Tanpa kategori' }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Tahun</span>
            <div class="detail-value">
                {{ $dokumen->tahun ?? '—' }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Tanggal Dokumen</span>
            <div class="detail-value">
                {{ $dokumen->tanggal_dokumen?->locale('id')->translatedFormat('d F Y') ?? '—' }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Pengunggah</span>
            <div class="detail-value">
                {{ $dokumen->uploader?->nama_lengkap ?? 'Tidak diketahui' }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Nama File</span>
            <div class="detail-value">
                {{ $dokumen->nama_file ?? '—' }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Ukuran File</span>
            <div class="detail-value">
                {{ $dokumen->ukuran_kb
                    ? number_format($dokumen->ukuran_kb / 1024, 2, ',', '.') . ' MB'
                    : '—' }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Status</span>
            <div class="detail-value">
                {{ ucfirst($dokumen->status) }}
            </div>
        </div>

        <div class="detail-item">
            <span class="detail-label">Tingkat Kerahasiaan</span>
            <div class="detail-value">
                {{ ucfirst($dokumen->tingkat_kerahasiaan ?? '—') }}
            </div>
        </div>

        <div class="detail-item detail-description">
            <span class="detail-label">Deskripsi</span>
            <div class="detail-value">
                {{ $dokumen->deskripsi ?: 'Tidak ada deskripsi.' }}
            </div>
        </div>

        @if ($dokumen->status === 'arsip')
            <div class="detail-item">
                <span class="detail-label">Tanggal Diarsipkan</span>
                <div class="detail-value">
                    {{ $dokumen->archived_at?->locale('id')->translatedFormat('d F Y H:i') ?? '—' }}
                </div>
            </div>
        @endif

    </div>

    <div class="detail-actions">

        <a
            href="{{ route('dokumen.download', $dokumen->id) }}"
            class="button primary"
        >
            Unduh Dokumen
        </a>

        <a
            href="{{ route('dokumen.index') }}"
            class="button"
        >
            Kembali
        </a>

    </div>

</section>

@endsection