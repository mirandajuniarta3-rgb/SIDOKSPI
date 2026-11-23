
@extends('layouts.app')

@section('title', 'Detail SK - SIDOKSPI')

@section('active-menu', 'sk-list')

@section('breadcrumb', 'Surat Keputusan (SK) / Detail SK')

@push('styles')
<link rel="stylesheet" href="{{ asset('asset/css/sk-detail.css') }}">
@endpush

@section('content')

<div class="sk-detail-page">

    {{-- HEADER --}}
    <div class="detail-heading">
        <div>
            <div class="detail-eyebrow">
                <span class="eyebrow-line"></span>
                ARSIP SURAT KEPUTUSAN
            </div>

            <h2>Detail Surat Keputusan</h2>

            <p>
                Informasi lengkap mengenai dokumen Surat Keputusan yang tersimpan
                dalam sistem SIDOKSPI.
            </p>
        </div>

        <a href="{{ route('sk.index') }}" class="detail-button secondary">
            <span class="button-icon">←</span>
            Kembali
        </a>
    </div>

    {{-- INFORMASI UTAMA --}}
    <div class="detail-card">

        <div class="detail-card-header">
            <div class="header-icon">SK</div>

            <div>
                <h3>Informasi Dokumen</h3>
                <p>Rincian data Surat Keputusan</p>
            </div>

            <div class="detail-status
                {{ $suratKeputusan->status === 'final' ? 'status-final' : ($suratKeputusan->status === 'arsip' ? 'status-arsip' : 'status-draft') }}">
                {{ ucfirst($suratKeputusan->status ?? 'Tidak diketahui') }}
            </div>
        </div>

        <div class="detail-card-body">

            <div class="detail-section-title">
                <span></span>
                Data Surat Keputusan
            </div>

            <div class="detail-information">

                <div class="information-row">
                    <div class="information-label">Nomor SK</div>
                    <div class="information-value highlight">
                        {{ $suratKeputusan->nomor_sk ?? '—' }}
                    </div>
                </div>

                <div class="information-row">
                    <div class="information-label">Judul Keputusan</div>
                    <div class="information-value">
                        {{ $suratKeputusan->judul ?? '—' }}
                    </div>
                </div>

                <div class="information-row">
                    <div class="information-label">Tentang / Perihal</div>
                    <div class="information-value">
                        {{ $suratKeputusan->tentang ?? '—' }}
                    </div>
                </div>

                <div class="information-row">
                    <div class="information-label">Tanggal Penetapan</div>
                    <div class="information-value">
                        {{ $suratKeputusan->tanggal_penetapan?->locale('id')->translatedFormat('d F Y') ?? '—' }}
                    </div>
                </div>

                <div class="information-row">
                    <div class="information-label">Tahun</div>
                    <div class="information-value">
                        {{ $suratKeputusan->tahun ?? '—' }}
                    </div>
                </div>

                <div class="information-row">
                    <div class="information-label">Pengunggah</div>
                    <div class="information-value">
                        {{ $suratKeputusan->uploader?->nama_lengkap ?? 'Tidak diketahui' }}
                    </div>
                </div>

            </div>

            <div class="detail-section-title file-section-title">
                <span></span>
                Informasi File
            </div>

            <div class="file-information">

                <div class="file-info-item">
                    <div class="file-info-icon">PDF</div>
                    <div>
                        <span class="file-info-label">Nama File</span>
                        <strong>{{ $suratKeputusan->nama_file ?? '—' }}</strong>
                    </div>
                </div>

                <div class="file-info-item">
                    <div class="file-info-icon size-icon">KB</div>
                    <div>
                        <span class="file-info-label">Ukuran File</span>
                        <strong>{{ $suratKeputusan->ukuran_kb ?? 0 }} KB</strong>
                    </div>
                </div>

            </div>

        </div>

        {{-- FOOTER --}}
        <div class="detail-card-footer">

            <div class="footer-description">
                <span class="footer-dot"></span>
                Detail dokumen Surat Keputusan SIDOKSPI
            </div>

            @if ($suratKeputusan->file_available)

                <a href="{{ route('sk.download', $suratKeputusan->id) }}"
                   class="detail-button primary">
                    <span>↓</span>
                    Unduh File PDF
                </a>

            @else

                <div class="file-warning">
                    File PDF SK tidak ditemukan.
                </div>

            @endif

        </div>

    </div>

</div>

@endsection
