
@extends('layouts.app')

@section('title', 'Rekap Dokumen - SIDOKSPI')
@section('active-menu', 'laporan')
@section('breadcrumb', 'Laporan / Rekap Dokumen')

@push('styles')
<link rel="stylesheet" href="{{ asset('asset/css/laporan.css') }}">
@endpush

@section('content')
<div class="laporan-page">

    {{-- HEADER --}}
    <div class="laporan-header">
        <div class="laporan-heading">
            <div class="laporan-breadcrumb">LAPORAN / DOKUMEN</div>
            <h2>Rekap Dokumen</h2>
            <p>Ringkasan dan rekapitulasi seluruh dokumen SPI.</p>
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
                <span class="stat-label">Total Dokumen</span>
                <span class="stat-icon"><i class="bi bi-folder2-open"></i></span>
            </div>
            <div class="stat-number">{{ $totalDokumen }}</div>
        </div>

        <div class="stat-card stat-green">
            <div class="stat-top">
                <span class="stat-label">Dokumen Final</span>
                <span class="stat-icon"><i class="bi bi-check-circle"></i></span>
            </div>
            <div class="stat-number">{{ $dokumenFinal }}</div>
        </div>

        <div class="stat-card stat-orange">
            <div class="stat-top">
                <span class="stat-label">Dokumen Draft</span>
                <span class="stat-icon"><i class="bi bi-pencil-square"></i></span>
            </div>
            <div class="stat-number">{{ $dokumenDraft }}</div>
        </div>

        <div class="stat-card stat-gray">
            <div class="stat-top">
                <span class="stat-label">Dokumen Arsip</span>
                <span class="stat-icon"><i class="bi bi-archive"></i></span>
            </div>
            <div class="stat-number">{{ $dokumenArsip }}</div>
        </div>

    </div>

    {{-- FILTER --}}
    <div class="laporan-panel filter-panel">
        <div class="panel-heading">
            <h3><i class="bi bi-funnel"></i> Filter Data Dokumen</h3>
            <p>Gunakan pencarian dan filter untuk menemukan dokumen.</p>
        </div>

        <div class="panel-body">
            <form action="{{ route('laporan.dokumen') }}" method="GET">
                <div class="filter-grid">

                    <div class="filter-field">
                        <label for="search">Cari Dokumen</label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            class="filter-control"
                            value="{{ $search }}"
                            placeholder="Nomor, judul, bidang, pengunggah..."
                        >
                    </div>

                    <div class="filter-field">
                        <label for="tahun">Tahun</label>
                        <select name="tahun" id="tahun" class="filter-control">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahun as $itemTahun)
                                <option value="{{ $itemTahun }}"
                                    {{ (string) $selectedTahun === (string) $itemTahun ? 'selected' : '' }}>
                                    {{ $itemTahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filter-field">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="filter-control">
                            <option value="">Semua Status</option>
                            <option value="final" {{ $selectedStatus === 'final' ? 'selected' : '' }}>Final</option>
                            <option value="draft" {{ $selectedStatus === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="arsip" {{ $selectedStatus === 'arsip' ? 'selected' : '' }}>Arsip</option>
                        </select>
                    </div>

                    <div class="filter-actions">
                        <button type="submit" class="btn-filter">
                            <i class="bi bi-search"></i> Cari
                        </button>

                        <a href="{{ route('laporan.dokumen') }}" class="btn-reset">
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
            <h3><i class="bi bi-table"></i> Data Rekap Dokumen</h3>
            <p>Daftar dokumen berdasarkan filter yang dipilih.</p>
        </div>

        <div class="table-wrapper">
            <table class="laporan-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nomor Dokumen</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Bidang</th>
                        <th>Tanggal</th>
                        <th>Tahun</th>
                        <th>Pengunggah</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($dokumen as $index => $item)
                        <tr>
                            <td>{{ $dokumen->firstItem() + $index }}</td>

                            <td class="table-primary-text">
                                {{ $item->nomor_dokumen ?: '-' }}
                            </td>

                            <td class="table-primary-text">
                                {{ $item->judul }}
                            </td>

                            <td>
                                {{ $item->kategori?->nama_kategori ?? '-' }}
                            </td>

                            <td>{{ $item->bidang ?: '-' }}</td>

                            <td>
                                {{ $item->tanggal_dokumen
                                    ? \Illuminate\Support\Carbon::parse($item->tanggal_dokumen)->format('d-m-Y')
                                    : '-' }}
                            </td>

                            <td>{{ $item->tahun ?: '-' }}</td>

                            <td>{{ $item->uploader?->nama_lengkap ?? '-' }}</td>

                            <td>
                                @if ($item->status === 'final')
                                    <span class="status-badge status-final">Final</span>
                                @elseif ($item->status === 'draft')
                                    <span class="status-badge status-draft">Draft</span>
                                @elseif ($item->status === 'arsip')
                                    <span class="status-badge status-arsip">Arsip</span>
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
                                Tidak ada data dokumen ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="table-footer">
            <div class="table-info">
                Menampilkan {{ $dokumen->firstItem() ?? 0 }}
                sampai {{ $dokumen->lastItem() ?? 0 }}
                dari {{ $dokumen->total() }} dokumen
            </div>

            <div>
                {{ $dokumen->links() }}
            </div>
        </div>
    </div>

</div>
@endsection
