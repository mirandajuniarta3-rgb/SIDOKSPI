
@extends('layouts.app')

@section('title', 'Arsip SK - SIDOKSPI')

@section('content')

<style>
    .arsip-page {
        color: #263653;
        font-size: 13px;
    }

    .arsip-header,
    .arsip-card,
    .arsip-table-card {
        background: #fff;
        border: 1px solid #e8ebf3;
        border-radius: 12px;
    }

    .arsip-header {
        padding: 22px;
        margin-bottom: 18px;
    }

    .arsip-title {
        font-size: 23px;
        font-weight: 750;
        color: #172b4d;
    }

    .arsip-subtitle {
        color: #8792a7;
        font-size: 12px;
    }

    .arsip-card {
        padding: 17px;
        height: 100%;
        box-shadow: 0 3px 12px rgba(30,55,100,.04);
    }

    .arsip-card-label {
        font-size: 10px;
        font-weight: 700;
        color: #7d8ba3;
        text-transform: uppercase;
    }

    .arsip-card-number {
        font-size: 28px;
        font-weight: 800;
        color: #263653;
        margin-top: 7px;
    }

    .arsip-icon {
        width: 40px;
        height: 40px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #eef1fa;
        color: #53678d;
        font-size: 19px;
    }

    .arsip-filter {
        background: white;
        border: 1px solid #e8ebf3;
        padding: 13px;
        border-radius: 10px;
        margin: 18px 0;
    }

    .arsip-input,
    .arsip-select {
        width: 100%;
        border: 1px solid #e1e6f0;
        background: #f8f9fd;
        padding: 10px 12px;
        border-radius: 7px;
        font-size: 11px;
        color: #344563;
    }

    .arsip-input:focus,
    .arsip-select:focus {
        outline: none;
        border-color: #2864d7;
    }

    .arsip-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        border: none;
        padding: 10px 15px;
        border-radius: 7px;
        font-size: 11px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
    }

    .arsip-btn-primary {
        color: white;
        background: #1758cb;
    }

    .arsip-btn-primary:hover {
        color: white;
        background: #1046a9;
    }

    .arsip-btn-light {
        background: #f0f2f8;
        color: #53627a;
    }

    .arsip-table-card {
        overflow: hidden;
    }

    .arsip-table-header {
        padding: 16px 18px;
        border-bottom: 1px solid #edf0f6;
    }

    .arsip-table {
        width: 100%;
        border-collapse: collapse;
    }

    .arsip-table th {
        padding: 13px 12px;
        background: #f8f9fd;
        color: #65738c;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .arsip-table td {
        padding: 13px 12px;
        border-top: 1px solid #edf0f6;
        font-size: 11px;
        vertical-align: middle;
    }

    .arsip-table tbody tr:hover {
        background: #f8faff;
    }

    .arsip-number {
        color: #1758cb;
        font-weight: 700;
    }

    .arsip-status {
        display: inline-block;
        padding: 5px 10px;
        background: #edf0f7;
        color: #64718a;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
    }

    .arsip-footer {
        padding: 14px 17px;
        border-top: 1px solid #edf0f6;
        font-size: 11px;
    }

    .arsip-info {
        padding: 14px;
        background: #f2f4ff;
        border: 1px solid #e7eaff;
        border-radius: 9px;
        margin-top: 15px;
        color: #65749a;
        font-size: 11px;
    }

    @media(max-width: 768px) {
        .arsip-table-wrapper {
            overflow-x: auto;
        }
    }
</style>

<div class="container-fluid py-4 arsip-page">

    {{-- HEADER --}}
    <div class="arsip-header">

        <div class="small text-muted mb-2">
            Portal SPI &nbsp;›&nbsp;
            Surat Keputusan (SK) &nbsp;›&nbsp;
            <span class="text-primary fw-bold">Arsip SK</span>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

            <div>
                <h3 class="arsip-title">
                    Arsip Surat Keputusan (SK)
                </h3>

                <p class="arsip-subtitle mb-0">
                    Kelola dan telusuri Surat Keputusan yang telah
                    berstatus arsip dalam repositori resmi SPI.
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap">

                <a href="{{ route('sk.index') }}"
                   class="arsip-btn arsip-btn-light">
                    <i class="bi bi-arrow-left"></i>
                    Daftar SK
                </a>

            </div>

        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-3">

        <div class="col-md-4">

            <div class="arsip-card">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="arsip-card-label">
                            Total SK Diarsipkan
                        </div>

                        <div class="arsip-card-number">
                            {{ $totalArsip }}
                        </div>

                        <small class="text-muted">
                            Surat Keputusan berstatus arsip
                        </small>
                    </div>

                    <div class="arsip-icon">
                        <i class="bi bi-archive"></i>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="arsip-card">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="arsip-card-label">
                            Tahun Arsip
                        </div>

                        <div class="arsip-card-number">
                            {{ $tahun->count() }}
                        </div>

                        <small class="text-muted">
                            Tahun penetapan yang tercatat
                        </small>
                    </div>

                    <div class="arsip-icon">
                        <i class="bi bi-calendar3"></i>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="arsip-card">

                <div class="d-flex justify-content-between">

                    <div>
                        <div class="arsip-card-label">
                            Hasil Ditampilkan
                        </div>

                        <div class="arsip-card-number">
                            {{ $suratKeputusan->count() }}
                        </div>

                        <small class="text-muted">
                            Data pada halaman ini
                        </small>
                    </div>

                    <div class="arsip-icon">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- FILTER --}}
    <div class="arsip-filter">

        <form action="{{ route('sk.arsip') }}"
              method="GET">

            <div class="row g-2 align-items-center">

                <div class="col-lg-6">

                    <input type="text"
                           name="search"
                           class="arsip-input"
                           placeholder="Cari nomor SK, judul keputusan, tentang..."
                           value="{{ $search }}">

                </div>

                <div class="col-lg-3">

                    <select name="tahun" class="arsip-select">

                        <option value="">
                            Semua Tahun
                        </option>

                        @foreach($tahun as $item)
                            <option value="{{ $item }}"
                                {{ (string) $selectedTahun === (string) $item ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach

                    </select>

                </div>

                <div class="col-lg-3 d-flex gap-2">

                    <button type="submit"
                            class="arsip-btn arsip-btn-primary flex-grow-1">

                        <i class="bi bi-search"></i>
                        Terapkan

                    </button>

                    <a href="{{ route('sk.arsip') }}"
                       class="arsip-btn arsip-btn-light">

                        <i class="bi bi-arrow-counterclockwise"></i>
                        Reset

                    </a>

                </div>

            </div>
        </form>
    </div>

    {{-- TABEL --}}
    <div class="arsip-table-card">

        <div class="arsip-table-header">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div>
                    <strong>
                        <i class="bi bi-archive me-2 text-primary"></i>
                        Register Arsip Surat Keputusan
                    </strong>

                    <div class="small text-muted mt-1">
                        Daftar seluruh SK yang telah diarsipkan.
                    </div>
                </div>

                <span class="arsip-status">
                    {{ $totalArsip }} Data Arsip
                </span>

            </div>

        </div>

        <div class="arsip-table-wrapper">

            <table class="arsip-table">

                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nomor SK</th>
                        <th>Judul Keputusan</th>
                        <th>Tentang / Perihal</th>
                        <th>Tanggal Penetapan</th>
                        <th>Tahun</th>
                        <th>Pengunggah</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($suratKeputusan as $index => $sk)

                        <tr>

                            <td>
                                {{ $suratKeputusan->firstItem() + $index }}
                            </td>

                            <td>
                                <span class="arsip-number">
                                    <i class="bi bi-file-earmark-pdf text-danger me-1"></i>
                                    {{ $sk->nomor_sk }}
                                </span>
                            </td>

                            <td>
                                <strong>
                                    {{ $sk->judul }}
                                </strong>
                            </td>

                            <td>
                                {{ \Illuminate\Support\Str::limit($sk->tentang, 65) }}
                            </td>

                            <td>
                                {{ $sk->tanggal_penetapan
                                    ? $sk->tanggal_penetapan->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $sk->tahun ?? '-' }}
                            </td>

                            <td>
                                {{ $sk->uploader->nama_lengkap ?? '-' }}
                            </td>

                            <td>
                                <span class="arsip-status">
                                    <i class="bi bi-archive me-1"></i>
                                    Arsip
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="text-center py-5">

                                <i class="bi bi-archive text-secondary"
                                   style="font-size:35px;"></i>

                                <h6 class="mt-3 fw-bold">
                                    Belum Ada Arsip SK
                                </h6>

                                <p class="text-muted mb-0">
                                    Belum terdapat Surat Keputusan
                                    yang berstatus arsip.
                                </p>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        <div class="arsip-footer">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                <div class="text-muted">
                    Menampilkan
                    {{ $suratKeputusan->firstItem() ?? 0 }}
                    -
                    {{ $suratKeputusan->lastItem() ?? 0 }}
                    dari {{ $suratKeputusan->total() }} data SK
                </div>

                <div>
                    {{ $suratKeputusan->links() }}
                </div>

            </div>

        </div>

    </div>

    {{-- INFORMASI --}}
    <div class="arsip-info">

        <strong>
            <i class="bi bi-shield-check me-1"></i>
            Ketentuan Pengelolaan Arsip SK
        </strong>

        <p class="mb-0 mt-2">
            Surat Keputusan yang berstatus arsip tetap tersimpan
            dalam register SK dan dapat ditelusuri melalui
            pencarian serta filter tahun penetapan.
        </p>

    </div>

</div>

@endsection
