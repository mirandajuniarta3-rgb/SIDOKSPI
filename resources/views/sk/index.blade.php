
@extends('layouts.app')

@section('title', 'Daftar SK - SIDOKSPI')

@section('active-menu', 'sk-list')

@section('breadcrumb', 'Surat Keputusan (SK) / Daftar SK')

@section('search-action', route('sk.index'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/sk-index.css') }}">
@endpush

@section('content')

<div class="sk-page">

    {{-- HEADER HALAMAN --}}
    <section class="page-heading" aria-labelledby="sk-page-title">
        <div>
            <div class="eyebrow">
                Portal SPI <span aria-hidden="true">›</span>
                Surat Keputusan (SK) <span aria-hidden="true">›</span>
                Daftar SK
            </div>

            <h1 id="sk-page-title">
                Daftar Surat Keputusan (SK)
            </h1>

            <p class="page-description">
                Kelola, pantau keabsahan hukum, dan telusuri arsip Surat
                Keputusan Direksi serta Dewan Pengawas Perumda Air Minum
                Tirta Pinang.
            </p>
        </div>

        <div class="heading-actions">
            <button class="button export-button" type="button" disabled
                title="Fitur ekspor belum tersedia">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5"/>
                </svg>
                Ekspor Data SK
            </button>

            <a href="{{ route('sk.create') }}" class="button primary">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14m-7-7h14"/>
                </svg>
                Tambah SK
            </a>
        </div>
    </section>

    {{-- STATISTIK SK --}}
    <section class="stats-grid" aria-label="Statistik Surat Keputusan">

        <article class="stat-card">
            <div>
                <div class="stat-label">Total Surat Keputusan</div>
                <div class="stat-value">
                    {{ number_format($totalSk, 0, ',', '.') }}
                </div>
                <span class="stat-note">
                    Tercatat dalam basis data hukum SPI
                </span>
            </div>

            <span class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8">
                    <path d="M5 3h10l4 4v14H5z"/>
                    <path d="M14 3v5h5M8 12h8m-8 4h8"/>
                </svg>
            </span>
        </article>

        <article class="stat-card">
            <div>
                <div class="stat-label">SK Berlaku (Aktif)</div>
                <div class="stat-value" style="color:#087c68">
                    {{ number_format($skBerlaku, 0, ',', '.') }}
                </div>
                <span class="stat-note">
                    Memiliki status berlaku
                </span>
            </div>

            <span class="stat-icon teal">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8">
                    <path d="m12 3 2 2 3-.2.8 2.9 2.5 1.7-1 2.8 1 2.8-2.5 1.7-.8 2.9-3-.2-2 2-2-2-3 .2-.8-2.9-2.5-1.7 1-2.8-1-2.8 2.5-1.7.8-2.9 3 .2z"/>
                    <path d="m8.5 12 2.2 2.2 4.8-4.8"/>
                </svg>
            </span>
        </article>

        <article class="stat-card">
            <div>
                <div class="stat-label">SK Berakhir / Arsip</div>
                <div class="stat-value" style="color:#78839a">
                    {{ number_format($skArsip, 0, ',', '.') }}
                </div>
                <span class="stat-note">
                    Berstatus arsip atau tidak berlaku
                </span>
            </div>

            <span class="stat-icon gray">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8">
                    <rect x="4" y="4" width="16" height="16" rx="2"/>
                    <path d="M8 8h8m-8 4h8m-8 4h5"/>
                </svg>
            </span>
        </article>

    </section>

    {{-- PANEL DAFTAR SK --}}
    <section class="table-panel" aria-label="Daftar Surat Keputusan">

        {{-- INFORMASI AUDIT --}}
        <div class="audit-strip">
            <span class="audit-title">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="m12 3 8 3v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3Z"/>
                    <path d="m9 12 2 2 4-4"/>
                </svg>
                Register Surat Keputusan Digital SPI — Tirta Pinang
            </span>

            <span class="audit-updated">
                Terakhir diperbarui:
                {{ now()->locale('id')->translatedFormat('d M Y H:i') }} WIB
            </span>
        </div>

        {{-- FILTER SK --}}
        <form class="filter-bar" method="GET" action="{{ route('sk.index') }}">

            <label class="filter-control">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <circle cx="10.8" cy="10.8" r="6.8"/>
                    <path d="m16 16 5 5"/>
                </svg>

                <input
                    type="search"
                    name="search"
                    value="{{ $search }}"
                    placeholder="Cari nomor SK, judul keputusan, atau perihal..."
                    aria-label="Cari Surat Keputusan">
            </label>

            <label class="filter-control">
                <select name="tahun" aria-label="Filter tahun">
                    <option value="">Semua Tahun</option>

                    @foreach ($tahun as $year)
                        <option
                            value="{{ $year }}"
                            @selected((string) $selectedTahun === (string) $year)>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </label>

            <label class="filter-control">
                <select name="status" aria-label="Filter status">
                    <option value="">Semua Status</option>

                    <option value="final"
                        @selected($selectedStatus === 'final')>
                        Berlaku
                    </option>

                    <option value="arsip"
                        @selected($selectedStatus === 'arsip')>
                        Arsip
                    </option>

                    <option value="draft"
                        @selected($selectedStatus === 'draft')>
                        Draft
                    </option>
                </select>
            </label>

            <button class="button primary" type="submit">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2">
                    <path d="M4 5h16M7 12h10m-7 7h4"/>
                </svg>
                Terapkan
            </button>

            <a class="reset-link" href="{{ route('sk.index') }}">
                Reset
            </a>

        </form>

        {{-- TABEL SK --}}
        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NOMOR SK</th>
                        <th>JUDUL KEPUTUSAN</th>
                        <th>TENTANG / PERIHAL</th>
                        <th>TANGGAL PENETAPAN</th>
                        <th>STATUS</th>
                        <th>AKSI MANAJEMEN</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($suratKeputusan as $item)

                        @php
                            $uploaderName = $item->uploader?->nama_lengkap
                                ?? 'Tidak diketahui';

                            $statusLabel = [
                                'final' => 'Berlaku',
                                'arsip' => 'Arsip',
                                'draft' => 'Draft',
                            ][$item->status] ?? ucfirst($item->status);

                            $statusClass = $item->status === 'arsip'
                                ? 'archived'
                                : ($item->status === 'draft' ? 'draft' : '');
                        @endphp

                        <tr>
                            <td class="number-cell">
                                {{ $suratKeputusan->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <span class="doc-number">
                                    {{ $item->nomor_sk ?: '—' }}
                                </span>
                            </td>

                            <td>
                                <span class="doc-title" title="{{ $item->judul }}">
                                    {{ $item->judul }}
                                </span>

                                <span class="file-meta">
                                    Dikelola oleh {{ $uploaderName }}
                                </span>
                            </td>

                            <td>
                                <span class="category-badge">
                                    {{ $item->tentang ?: '—' }}
                                </span>
                            </td>

                            <td>
                                {{ $item->tanggal_penetapan?->locale('id')->translatedFormat('d M Y') ?? '—' }}
                            </td>

                            <td>
                                <span class="status {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            {{-- AKSI MANAJEMEN --}}
                            <td>
                                <span class="actions">

                                    {{-- DETAIL --}}
                                    <a
                                        href="{{ route('sk.show', $item->id) }}"
                                        class="action-button"
                                        aria-label="Lihat Surat Keputusan"
                                        title="Lihat Detail SK">

                                        <svg viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8">
                                            <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/>
                                            <circle cx="12" cy="12" r="3"/>
                                        </svg>
                                    </a>

                                    {{-- UNDUH --}}
                                    <a
                                        href="{{ route('sk.download', $item->id) }}"
                                        class="action-button"
                                        aria-label="Unduh Surat Keputusan"
                                        title="Unduh PDF SK">

                                        <svg viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="1.8">
                                            <path d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4"/>
                                        </svg>
                                    </a>

                                    {{-- ARSIPKAN --}}
                                    @if ($item->status !== 'arsip')
                                        <form
                                            action="{{ route('sk.simpanArsip', $item->id) }}"
                                            method="POST"
                                            style="display:inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin mengarsipkan Surat Keputusan ini?')">

                                            @csrf

                                            <button
                                                class="action-button"
                                                type="submit"
                                                aria-label="Arsipkan Surat Keputusan"
                                                title="Arsipkan SK">

                                                <svg viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="1.8">
                                                    <path d="M4 7h16M7 4h10M6 7l1 14h10l1-14"/>
                                                    <path d="M10 11v6m4-6v6"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- HAPUS --}}
                                    <form
                                        action="{{ route('sk.destroy', $item->id) }}"
                                        method="POST"
                                        style="display:inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus Surat Keputusan {{ addslashes($item->nomor_sk ?: $item->judul) }}? Data yang dihapus tidak dapat dikembalikan.')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="action-button"
                                            type="submit"
                                            aria-label="Hapus Surat Keputusan"
                                            title="Hapus SK"
                                            style="color:#dc3545">

                                            <svg viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8">
                                                <path d="M3 6h18"/>
                                                <path d="M8 6V4h8v2"/>
                                                <path d="m19 6-1 14H6L5 6"/>
                                                <path d="M10 11v5m4-5v5"/>
                                            </svg>
                                        </button>
                                    </form>

                                </span>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td class="empty-cell" colspan="7">
                                {{ $search !== '' || $selectedTahun !== '' || $selectedStatus !== ''
                                    ? 'Tidak ada Surat Keputusan yang sesuai dengan filter.'
                                    : 'Belum ada Surat Keputusan untuk ditampilkan.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- FOOTER DAN PAGINATION --}}
        <div class="table-footer">

            <span class="pagination-summary">
                @if ($suratKeputusan->total() > 0)
                    Menampilkan
                    <strong>
                        {{ $suratKeputusan->firstItem() }}–{{ $suratKeputusan->lastItem() }}
                    </strong>
                    dari
                    <strong>
                        {{ number_format($suratKeputusan->total(), 0, ',', '.') }}
                    </strong>
                    total data
                @else
                    Menampilkan <strong>0</strong> dari <strong>0</strong> total data
                @endif
            </span>

            <div class="pagination">
                {{ $suratKeputusan->onEachSide(1)->links() }}
            </div>

        </div>

    </section>

    {{-- INFORMASI REGISTER --}}
    <aside class="integrity" aria-label="Informasi register Surat Keputusan">

        <div class="integrity-copy">

            <span class="integrity-icon">
                <svg viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.8">
                    <path d="M5 3h10l4 4v14H5z"/>
                    <path d="M14 3v5h5M8 12h8m-8 4h8"/>
                </svg>
            </span>

            <span>
                <strong>Register Surat Keputusan SPI</strong>
                <span>
                    Status Berlaku dan Arsip mengikuti data pada register SK.
                    Gunakan pencarian dan filter untuk menelusuri nomor,
                    perihal, tahun penetapan, atau status keputusan.
                </span>
            </span>

        </div>

    </aside>

</div>

@endsection
