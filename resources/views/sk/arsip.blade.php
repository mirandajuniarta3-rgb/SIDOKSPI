
@extends('layouts.app')

@section('title', 'Arsip SK - SIDOKSPI')

@section('content')

<style>
    .arsip-page {
        color: #263653;
        font-size: 12px;
    }

    .arsip-header,
    .arsip-card,
    .arsip-filter,
    .arsip-table-card {
        background: #fff;
        border: 1px solid #e8ebf3;
        border-radius: 10px;
    }

    /* HEADER */
    .arsip-header {
        padding: 20px 16px;
        margin-bottom: 14px;
    }

    .arsip-title {
        font-size: 19px;
        font-weight: 750;
        color: #172b4d;
        margin-bottom: 8px;
    }

    .arsip-subtitle {
        color: #8792a7;
        font-size: 11px;
    }

    /* STATISTIK */
    .arsip-card {
        padding: 14px 15px;
        min-height: 105px;
        box-shadow: 0 2px 8px rgba(30,55,100,.035);
    }

    .arsip-card-label {
        font-size: 9px;
        font-weight: 750;
        color: #8290a8;
        text-transform: uppercase;
        letter-spacing: .3px;
    }

    .arsip-card-number {
        font-size: 25px;
        font-weight: 800;
        color: #263653;
        margin: 4px 0;
    }

    .arsip-card small {
        color: #8995a9;
        font-size: 10px;
    }

    .arsip-icon {
        width: 35px;
        height: 35px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        background: #edf3ff;
        color: #2864d7;
    }

    .arsip-icon.green {
        background: #e5fbf3;
        color: #18a77c;
    }

    .arsip-icon.blue {
        background: #eaf4ff;
        color: #2877bb;
    }

    /* FILTER */
    .arsip-filter {
        padding: 11px;
        margin: 15px 0;
    }

    .arsip-input,
    .arsip-select {
        width: 100%;
        border: 1px solid #e1e6f0;
        background: #f9faff;
        padding: 9px 11px;
        border-radius: 6px;
        font-size: 10px;
        color: #344563;
    }

    .arsip-input:focus,
    .arsip-select:focus {
        outline: none;
        border-color: #2864d7;
        box-shadow: 0 0 0 2px rgba(40,100,215,.08);
    }

    .arsip-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        border: none;
        padding: 9px 13px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 650;
        text-decoration: none;
        cursor: pointer;
        white-space: nowrap;
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

    .arsip-btn-light:hover {
        background: #e5e9f3;
        color: #263653;
    }

    /* TABEL */
    .arsip-table-card {
        overflow: hidden;
    }

    .arsip-table-header {
        padding: 13px 15px;
        border-bottom: 1px solid #edf0f6;
    }

    .arsip-table {
        width: 100%;
        border-collapse: collapse;
    }

    .arsip-table th {
        padding: 11px 10px;
        background: #f8f9fd;
        color: #65738c;
        font-size: 9px;
        font-weight: 750;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .arsip-table td {
        padding: 11px 10px;
        border-top: 1px solid #edf0f6;
        font-size: 10px;
        vertical-align: middle;
        color: #475875;
    }

    .arsip-table tbody tr:hover {
        background: #f8faff;
    }

    .arsip-number {
        color: #1758cb;
        font-weight: 700;
        white-space: nowrap;
    }

    .arsip-status {
        display: inline-block;
        padding: 4px 9px;
        background: #edf0f7;
        color: #64718a;
        border-radius: 20px;
        font-size: 9px;
        font-weight: 700;
        white-space: nowrap;
    }

    .arsip-footer {
        padding: 12px 15px;
        border-top: 1px solid #edf0f6;
        font-size: 10px;
    }

    .arsip-info {
        padding: 12px 14px;
        background: #f2f4ff;
        border: 1px solid #e7eaff;
        border-radius: 8px;
        margin-top: 13px;
        color: #65749a;
        font-size: 10px;
    }

    /* PAGINATION */
    .arsip-footer nav p {
        display: none;
    }

    .arsip-footer nav a,
    .arsip-footer nav span {
        font-size: 10px !important;
    }

    /* RESPONSIVE */
    @media(max-width: 768px) {
        .arsip-table-wrapper {
            overflow-x: auto;
        }

        .arsip-table {
            min-width: 950px;
        }

        .arsip-header {
            padding: 15px;
        }

        .arsip-title {
            font-size: 17px;
        }

        .arsip-filter .arsip-btn {
            flex-grow: 1;
        }
    }
</style>

<div class="container-fluid py-3 arsip-page">

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
                    Kelola dan telusuri Surat Keputusan yang telah berstatus arsip dalam repositori resmi SPI.
                </p>
            </div>

            <a href="{{ route('sk.index') }}"
               class="arsip-btn arsip-btn-light">
                <i class="bi bi-arrow-left"></i>
                Daftar SK
            </a>

        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="row g-2">

        <div class="col-md-4">
            <div class="arsip-card">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <div class="arsip-card-label">
                            Total SK Diarsipkan
                        </div>

                        <div class="arsip-card-number">
                            {{ $totalArsip }}
                        </div>

                        <small>Surat Keputusan berstatus arsip</small>
                    </div>

                    <div class="arsip-icon">
                        <i class="bi bi-archive"></i>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="arsip-card">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <div class="arsip-card-label">
                            Tahun Arsip
                        </div>

                        <div class="arsip-card-number">
                            {{ $tahun->count() }}
                        </div>

                        <small>Tahun penetapan yang tercatat</small>
                    </div>

                    <div class="arsip-icon green">
                        <i class="bi bi-calendar3"></i>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="arsip-card">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <div class="arsip-card-label">
                            Hasil Ditampilkan
                        </div>

                        <div class="arsip-card-number">
                            {{ $suratKeputusan->count() }}
                        </div>

                        <small>Data pada halaman ini</small>
                    </div>

                    <div class="arsip-icon blue">
                        <i class="bi bi-file-earmark-check"></i>
                    </div>

                </div>
            </div>
        </div>

    </div>

    {{-- FILTER --}}
    <div class="arsip-filter">

        <form action="{{ route('sk.arsip') }}" method="GET">

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

                        <option value="">Semua Tahun</option>

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
                        <i class="bi bi-funnel"></i>
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
                                <strong>{{ $sk->judul }}</strong>
                            </td>

                            <td>
                                {{ \Illuminate\Support\Str::limit($sk->tentang, 65) }}
                            </td>

                            <td>
                                {{ $sk->tanggal_penetapan
                                    ? $sk->tanggal_penetapan->format('d M Y')
                                    : '-' }}
                            </td>

                            <td>{{ $sk->tahun ?? '-' }}</td>

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
                                    Belum terdapat Surat Keputusan yang berstatus arsip.
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
