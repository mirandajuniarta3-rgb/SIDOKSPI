@extends('layouts.app')

@section('title', 'Rekap SK - SIDOKSPI')
@section('active-menu', 'laporan')
@section('breadcrumb', 'Laporan / Rekap SK')

@push('styles')
<link rel="stylesheet" href="{{ asset('asset/css/laporan.css') }}">
@endpush

@section('content')
<div class="laporan-page">

    {{-- HEADER --}}
    <div class="laporan-header">
        <div class="laporan-heading">
            <div class="laporan-breadcrumb">LAPORAN / SURAT KEPUTUSAN</div>
            <h2>Rekap Surat Keputusan</h2>
            <p>Ringkasan dan rekapitulasi seluruh Surat Keputusan SPI.</p>
        </div>

        <button type="button" onclick="window.print()" class="btn-cetak">
            <i class="bi bi-printer"></i>
            Cetak Laporan
        </button>
    </div>

    {{-- STATISTIK --}}
    <div class="laporan-stats">

        <div class="stat-card stat-blue">
            <div class="stat-top">
                <span class="stat-label">Total SK</span>
                <span class="stat-icon">
                    <i class="bi bi-folder2-open"></i>
                </span>
            </div>
            <div class="stat-number">{{ $totalSk }}</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-top">
                <span class="stat-label">SK Final</span>
                <span class="stat-icon">
                    <i class="bi bi-check-circle"></i>
                </span>
            </div>
            <div class="stat-number">{{ $skFinal }}</div>
        </div>

        <div class="stat-card stat-orange">
            <div class="stat-top">
                <span class="stat-label">SK Draft</span>
                <span class="stat-icon">
                    <i class="bi bi-pencil-square"></i>
                </span>
            </div>
            <div class="stat-number">{{ $skDraft }}</div>
        </div>

        <div class="stat-card stat-gray">
            <div class="stat-top">
                <span class="stat-label">SK Arsip</span>
                <span class="stat-icon">
                    <i class="bi bi-archive"></i>
                </span>
            </div>
            <div class="stat-number">{{ $skArsip }}</div>
        </div>

    </div>

    {{-- FILTER --}}
    <div class="laporan-panel filter-panel">

        <div class="panel-heading">
            <h3>
                <i class="bi bi-funnel"></i>
                Filter Data Surat Keputusan
            </h3>
            <p>Gunakan pencarian dan filter untuk menemukan Surat Keputusan.</p>
        </div>

        <div class="panel-body">
            <form action="{{ route('laporan.sk') }}" method="GET">

                <div class="filter-grid">

                    <div class="filter-field">
                        <label for="search">Cari Surat Keputusan</label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="filter-control"
                            value="{{ $search }}"
                            placeholder="Nomor SK, judul, tentang, pengunggah..."
                        >
                    </div>

                    <div class="filter-field">
                        <label for="tahun">Tahun</label>
                        <select
                            name="tahun"
                            id="tahun"
                            class="filter-control"
                        >
                            <option value="">Semua Tahun</option>

                            @foreach ($tahun as $itemTahun)
                                <option
                                    value="{{ $itemTahun }}"
                                    {{ (string) $selectedTahun === (string) $itemTahun ? 'selected' : '' }}
                                >
                                    {{ $itemTahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-field">
                        <label for="status">Status</label>
                        <select
                            name="status"
                            id="status"
                            class="filter-control"
                        >
                            <option value="">Semua Status</option>

                            <option
                                value="final"
                                {{ $selectedStatus === 'final' ? 'selected' : '' }}
                            >
                                Final
                            </option>

                            <option
                                value="draft"
                                {{ $selectedStatus === 'draft' ? 'selected' : '' }}
                            >
                                Draft
                            </option>

                            <option
                                value="arsip"
                                {{ $selectedStatus === 'arsip' ? 'selected' : '' }}
                            >
                                Arsip
                            </option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-filter">
                            <i class="bi bi-search"></i>
                            Cari
                        </button>

                        <a href="{{ route('laporan.sk') }}" class="btn-reset">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- TABEL --}}
    <div class="laporan-panel">

        <div class="panel-heading">
            <h3>
                <i class="bi bi-table"></i>
                Data Rekap Surat Keputusan
            </h3>
            <p>Daftar Surat Keputusan berdasarkan filter yang dipilih.</p>
        </div>

        <div class="table-wrapper">
            <table class="laporan-table">

                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nomor SK</th>
                        <th>Judul SK</th>
                        <th>Tentang</th>
                        <th>Tanggal Penetapan</th>
                        <th>Tahun</th>
                        <th>Pengunggah</th>
                        <th>Nama File</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($suratKeputusan as $index => $item)
                        <tr>
                            <td>
                                {{ $suratKeputusan->firstItem() + $index }}
                            </td>

                            <td class="table-primary-text">
                                {{ $item->nomor_sk ?: '-' }}
                            </td>

                            <td class="table-primary-text">
                                {{ $item->judul }}
                            </td>

                            <td>
                                {{ $item->tentang ?: '-' }}
                            </td>

                            <td>
                                {{ $item->tanggal_penetapan
                                    ? \Illuminate\Support\Carbon::parse($item->tanggal_penetapan)->format('d-m-Y')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $item->tahun ?: '-' }}
                            </td>

                            <td>
                                {{ $item->uploader?->nama_lengkap ?? '-' }}
                            </td>

                            <td>
                                {{ $item->nama_file ?: '-' }}
                            </td>

                            <td>
                                @if ($item->status === 'final')
                                    <span class="status-badge status-final">
                                        Final
                                    </span>
                                @elseif ($item->status === 'draft')
                                    <span class="status-badge status-draft">
                                        Draft
                                    </span>
                                @elseif ($item->status === 'arsip')
                                    <span class="status-badge status-arsip">
                                        Arsip
                                    </span>
                                @else
                                    <span class="status-badge status-lain">
                                        {{ $item->status ?: '-' }}
                                    </span>
                                @endif
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="9" class="empty-data">
                                <i class="bi bi-folder-x"></i>
                                <br>
                                Tidak ada data Surat Keputusan ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- FOOTER TABEL --}}
        <div class="table-footer">

            <div class="table-info">
                Menampilkan {{ $suratKeputusan->firstItem() ?? 0 }}
                sampai {{ $suratKeputusan->lastItem() ?? 0 }}
                dari {{ $suratKeputusan->total() }} Surat Keputusan
            </div>

            <div>
                {{ $suratKeputusan->links() }}
            </div>

        </div>
    </div>

</div>
@endsection