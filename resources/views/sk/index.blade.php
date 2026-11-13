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
        <section class="sk-heading" aria-labelledby="sk-page-title">
            <div>
                <div class="sk-eyebrow">Portal SPI <span aria-hidden="true">›</span> Surat Keputusan (SK) <span
                        aria-hidden="true">›</span> Daftar SK</div>
                <h1 id="sk-page-title">Daftar Surat Keputusan (SK) <span class="sk-unit-badge">Tirta Pinang</span></h1>
                <p class="sk-description">Kelola, pantau keabsahan hukum, dan telusuri arsip Surat Keputusan Direksi serta
                    Dewan Pengawas Perumda Air Minum Tirta Pinang.</p>
            </div>
            <div class="sk-heading-actions">
                <button class="sk-button" type="button" disabled title="Fitur ekspor belum tersedia">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5" />
                    </svg>
                    Ekspor Data SK
                    <svg class="sk-button-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="m7 10 5 5 5-5" />
                    </svg>
                </button>
                <button class="sk-button sk-button-primary" type="button" disabled
                    title="Halaman tambah SK belum tersedia">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14m-7-7h14" />
                    </svg>
                    Tambah SK
                </button>
            </div>
        </section>

        <section class="sk-stats-grid" aria-label="Ringkasan Surat Keputusan">
            <article class="sk-stat-card">
                <div>
                    <div class="sk-stat-label">Total Surat Keputusan</div>
                    <div class="sk-stat-value">{{ number_format($totalSk, 0, ',', '.') }}<span class="sk-stat-unit">SK
                            Teregistrasi</span></div>
                    <span class="sk-stat-note">Tercatat dalam basis data hukum SPI</span>
                </div>
                <span class="sk-stat-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M5 3h10l4 4v14H5z" />
                        <path d="M14 3v5h5M8 12h8m-8 4h8" />
                    </svg>
                </span>
            </article>
            <article class="sk-stat-card">
                <div>
                    <div class="sk-stat-label">SK Berlaku (Aktif)</div>
                    <div class="sk-stat-value sk-stat-value-green">{{ number_format($skBerlaku, 0, ',', '.') }}<span
                            class="sk-stat-unit">Ketetapan</span></div>
                    <span class="sk-stat-note">Memiliki status berlaku</span>
                </div>
                <span class="sk-stat-icon sk-stat-icon-green">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path
                            d="m12 3 2 2 3-.2.8 2.9 2.5 1.7-1 2.8 1 2.8-2.5 1.7-.8 2.9-3-.2-2 2-2-2-3 .2-.8-2.9-2.5-1.7 1-2.8-1-2.8 2.5-1.7.8-2.9 3 .2z" />
                        <path d="m8.5 12 2.2 2.2 4.8-4.8" />
                    </svg>
                </span>
                <span
                    class="sk-stat-percent sk-stat-percent-green">{{ number_format($totalSk ? ($skBerlaku / $totalSk) * 100 : 0, 1, ',', '.') }}%</span>
            </article>
            <article class="sk-stat-card">
                <div>
                    <div class="sk-stat-label">SK Berakhir / Arsip</div>
                    <div class="sk-stat-value sk-stat-value-gray">{{ number_format($skArsip, 0, ',', '.') }}<span
                            class="sk-stat-unit">Dokumen</span></div>
                    <span class="sk-stat-note">Berstatus arsip atau tidak berlaku</span>
                </div>
                <span class="sk-stat-icon sk-stat-icon-gray">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="4" y="4" width="16" height="16" rx="2" />
                        <path d="M8 8h8m-8 4h8m-8 4h5" />
                    </svg>
                </span>
                <span
                    class="sk-stat-percent sk-stat-percent-gray">{{ number_format($totalSk ? ($skArsip / $totalSk) * 100 : 0, 1, ',', '.') }}%</span>
            </article>
        </section>

        <section class="sk-panel" aria-label="Daftar Surat Keputusan">
            <form class="sk-filter-bar" method="GET" action="{{ route('sk.index') }}">
                <label class="sk-filter-control sk-search-control">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="10.8" cy="10.8" r="6.8" />
                        <path d="m16 16 5 5" />
                    </svg>
                    <input type="search" name="search" value="{{ $search }}"
                        placeholder="Cari nomor SK, judul keputusan, atau perihal penetapan..."
                        aria-label="Cari Surat Keputusan">
                </label>
                <label class="sk-filter-control">
                    <select name="tahun" aria-label="Filter tahun">
                        <option value="">Semua Tahun</option>
                        @foreach ($tahun as $year)
                            <option value="{{ $year }}" @selected((string) $selectedTahun === (string) $year)>{{ $year }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="sk-filter-control">
                    <select name="status" aria-label="Filter status">
                        <option value="">Semua Status</option>
                        <option value="final" @selected($selectedStatus === 'final')>Berlaku</option>
                        <option value="arsip" @selected($selectedStatus === 'arsip')>Arsip</option>
                        <option value="draft" @selected($selectedStatus === 'draft')>Draft</option>
                    </select>
                </label>
                <button class="sk-button sk-filter-apply" type="submit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="m5 12 4 4L19 6" />
                    </svg>
                    Terapkan
                </button>
                <a class="sk-reset-link" href="{{ route('sk.index') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 12a9 9 0 1 0 2.6-6.4L3 8" />
                        <path d="M3 3v5h5" />
                    </svg>
                    Reset
                </a>
            </form>

            <div class="sk-table-scroll">
                <table class="sk-table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NOMOR SK</th>
                            <th>JUDUL KEPUTUSAN</th>
                            <th>TENTANG / PERIHAL</th>
                            <th>TANGGAL PENETAPAN</th>
                            <th>STATUS</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suratKeputusan as $item)
                            @php
                                $uploaderName = $item->uploader?->nama_lengkap ?? 'Tidak diketahui';
                                $statusLabel =
                                    ['final' => 'Berlaku', 'arsip' => 'Arsip', 'draft' => 'Draft'][$item->status] ??
                                    ucfirst($item->status);
                                $statusClass =
                                    ['final' => 'valid', 'arsip' => 'archived', 'draft' => 'draft'][$item->status] ??
                                    'draft';
                            @endphp
                            <tr>
                                <td class="sk-number-cell">{{ $suratKeputusan->firstItem() + $loop->index }}</td>
                                <td>
                                    <span class="sk-doc-number">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.8">
                                            <path d="M5 3h10l4 4v14H5z" />
                                            <path d="M14 3v5h5M8 12h8m-8 4h5" />
                                        </svg>
                                        {{ $item->nomor_sk }}
                                    </span>
                                </td>
                                <td>
                                    <span class="sk-doc-title" title="{{ $item->judul }}">{{ $item->judul }}</span>
                                    <span class="sk-uploader">Dikelola oleh {{ $uploaderName }}</span>
                                </td>
                                <td><span class="sk-topic-badge">{{ $item->tentang ?: '—' }}</span></td>
                                <td>{{ $item->tanggal_penetapan?->locale('id')->translatedFormat('d M Y') ?? '—' }}</td>
                                <td><span class="sk-status {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                <td>
                                    <span class="sk-actions">
                                        <button class="sk-action-button" type="button" disabled
                                            aria-label="Lihat Surat Keputusan" title="Fitur lihat belum tersedia">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z" />
                                                <circle cx="12" cy="12" r="3" />
                                            </svg>
                                        </button>
                                        <button class="sk-action-button" type="button" disabled
                                            aria-label="Unduh Surat Keputusan" title="Fitur unduh belum tersedia">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4" />
                                            </svg>
                                        </button>
                                        <button class="sk-action-button" type="button" disabled
                                            aria-label="Kelola Surat Keputusan" title="Fitur manajemen belum tersedia">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="1.8">
                                                <path d="M4 7h16M7 4h10M6 7l1 14h10l1-14M10 11v6m4-6v6" />
                                            </svg>
                                        </button>
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="sk-empty-cell" colspan="7">
                                    {{ $search !== '' || $selectedTahun !== '' || $selectedStatus !== '' ? 'Tidak ada Surat Keputusan yang sesuai dengan filter.' : 'Belum ada Surat Keputusan untuk ditampilkan.' }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="sk-table-footer">
                <span>
                    @if ($suratKeputusan->total() > 0)
                        Menampilkan <strong>{{ $suratKeputusan->firstItem() }}–{{ $suratKeputusan->lastItem() }}</strong>
                        dari <strong>{{ number_format($suratKeputusan->total(), 0, ',', '.') }}</strong> data SK
                    @else
                        Menampilkan <strong>0</strong> dari <strong>0</strong> data SK
                    @endif
                </span>
                <div class="sk-pagination">{{ $suratKeputusan->onEachSide(1)->links() }}</div>
            </div>
        </section>

        <aside class="sk-register-note" aria-label="Informasi register Surat Keputusan">
            <span class="sk-note-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M5 3h10l4 4v14H5z" />
                    <path d="M14 3v5h5M8 12h8m-8 4h8" />
                </svg>
            </span>
            <span>
                <strong>Register Surat Keputusan SPI</strong>
                <span>Status Berlaku dan Arsip mengikuti data pada register SK. Gunakan pencarian dan filter untuk
                    menelusuri nomor, perihal, tahun penetapan, atau status keputusan.</span>
            </span>
        </aside>
    </div>
@endsection
