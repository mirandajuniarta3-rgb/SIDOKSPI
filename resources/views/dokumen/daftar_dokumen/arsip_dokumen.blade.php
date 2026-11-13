@extends('layouts.app')

@section('title', 'Arsip Dokumen - SIDOKSPI')
@section('active-menu', 'document-archive')
@section('breadcrumb', 'Dokumen / Arsip Dokumen')
@section('search-action', route('dokumen.arsip'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/arsip-dokumen.css') }}">
@endpush

@section('content')
<div class="archive-page">
    <section class="archive-heading" aria-labelledby="archive-title">
        <div>
            <div class="archive-eyebrow">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16v13H4zM3 4h18v3H3z"/><path d="M9 11h6"/></svg>
                Repositori Arsip Dokumen SPI
            </div>
            <h1 id="archive-title">Arsip Dokumen Pengawasan</h1>
            <p class="archive-description">Koleksi dokumen audit internal, laporan kepatuhan, dan berkas pengawasan yang telah berstatus arsip. Cari, tinjau, unduh, atau aktifkan kembali dokumen sesuai kewenangan.</p>
        </div>
        <div class="archive-heading-actions">
            <button class="archive-button" type="button" disabled title="Fitur bundel ZIP belum tersedia">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4"/></svg>
                Unduh Bundel (.zip)
            </button>
            <button class="archive-button" type="button" disabled title="Fitur ekspor belum tersedia">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4zM8 8h8M8 12h8M8 16h5"/></svg>
                Ekspor Daftar Arsip
            </button>
        </div>
    </section>

    <section class="archive-stats" aria-label="Ringkasan arsip">
        <article class="archive-stat-card">
            <div>
                <div class="archive-stat-label">Total Dokumen Diarsipkan</div>
                <div class="archive-stat-value">{{ number_format($totalArsip, 0, ',', '.') }}</div>
                <span class="archive-stat-note">Berkas dokumen</span>
            </div>
            <span class="archive-stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="4" width="16" height="16" rx="2"/><path d="M8 8h8m-8 4h8m-8 4h5"/></svg>
            </span>
        </article>
        <article class="archive-stat-card">
            <div>
                <div class="archive-stat-label">Retensi Rata-rata</div>
                <div class="archive-stat-value archive-stat-value-teal">
                    {{ $retensiRataRata === null ? '—' : number_format((float) $retensiRataRata, 0, ',', '.') }}
                    @if ($retensiRataRata !== null)<span class="archive-stat-unit">Tahun</span>@endif
                </div>
                <span class="archive-stat-note">{{ $retensiRataRata === null ? 'Data retensi belum tersedia' : 'Berdasarkan masa retensi terdaftar' }}</span>
            </div>
            <span class="archive-stat-icon archive-stat-icon-teal">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
            </span>
        </article>
        <article class="archive-stat-card">
            <div>
                <div class="archive-stat-label">Kapasitas Penyimpanan</div>
                <div class="archive-stat-value archive-stat-value-blue">{{ number_format($kapasitasArsipKb / 1024, 1, ',', '.') }}<span class="archive-stat-unit">MB</span></div>
                <span class="archive-stat-note">Akumulasi ukuran berkas tercatat</span>
            </div>
            <span class="archive-stat-icon archive-stat-icon-cyan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"/></svg>
            </span>
        </article>
    </section>

    <section class="archive-panel" aria-label="Daftar arsip dokumen">
        <div class="archive-audit-strip">
            <span class="archive-audit-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16v13H4zM3 4h18v3H3z"/><path d="M9 11h6"/></svg>
                Register Master Arsip SPI
                <span class="archive-count-badge">{{ number_format($totalArsip, 0, ',', '.') }} Berkas</span>
            </span>
            <span class="archive-audit-note">Dokumen berstatus arsip · Retensi tercatat</span>
        </div>

        <form class="archive-filter-bar" method="GET" action="{{ route('dokumen.arsip') }}">
            <label class="archive-filter-control archive-search-control">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 5 5"/></svg>
                <input type="search" name="search" value="{{ $search }}" placeholder="Cari nomor arsip, judul dokumen, atau pengunggah..." aria-label="Cari arsip">
            </label>
            <label class="archive-filter-control">
                <select name="kategori" aria-label="Filter kategori arsip">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $item)
                        <option value="{{ $item->id }}" @selected((string) $selectedKategori === (string) $item->id)>{{ $item->nama_kategori }}</option>
                    @endforeach
                </select>
            </label>
            <label class="archive-filter-control">
                <select name="tahun" aria-label="Filter tahun dokumen">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahun as $year)
                        <option value="{{ $year }}" @selected((string) $selectedTahun === (string) $year)>{{ $year }}</option>
                    @endforeach
                </select>
            </label>
            <button class="archive-button archive-apply-button" type="submit">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 5h16M7 12h10m-7 7h4"/></svg>
                Terapkan
            </button>
            <a class="archive-reset-link" href="{{ route('dokumen.arsip') }}">Reset</a>
            <span class="archive-scope-badge"><span></span>Arsip saja</span>
        </form>

        <div class="archive-table-scroll">
            <table class="archive-table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NOMOR DOKUMEN</th>
                        <th>JUDUL BERKAS DOKUMEN</th>
                        <th>KATEGORI</th>
                        <th>TANGGAL ARSIP</th>
                        <th>PENGUNGGAH / AUDITOR</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($dokumen as $item)
                        @php
                            $uploaderName = $item->uploader?->nama_lengkap ?? 'Tidak diketahui';
                            $uploaderInitials = collect(explode(' ', trim($uploaderName)))
                                ->filter()
                                ->take(2)
                                ->map(fn ($part) => mb_substr($part, 0, 1))
                                ->implode('');
                            $fileExtension = $item->nama_file ? strtoupper(pathinfo($item->nama_file, PATHINFO_EXTENSION)) : 'FILE';
                        @endphp
                        <tr>
                            <td class="archive-number-cell">{{ $dokumen->firstItem() + $loop->index }}</td>
                            <td><span class="archive-doc-number">{{ $item->nomor_dokumen ?: '—' }}</span></td>
                            <td>
                                <span class="archive-doc-title" title="{{ $item->judul }}">{{ $item->judul }}</span>
                                <span class="archive-file-meta">
                                    <span class="archive-file-type">{{ $fileExtension }}</span>
                                    @if ($item->ukuran_kb) · {{ number_format($item->ukuran_kb / 1024, 1, ',', '.') }} MB @endif
                                    @if ($item->retensi_tahun) · Retensi {{ $item->retensi_tahun }} th @endif
                                </span>
                            </td>
                            <td><span class="archive-category-badge">{{ $item->kategori?->nama_kategori ?? 'Tanpa kategori' }}</span></td>
                            <td>{{ $item->archived_at?->locale('id')->translatedFormat('d M Y') ?? '—' }}</td>
                            <td>
                                <span class="archive-uploader">
                                    <span class="archive-uploader-avatar">{{ mb_strtoupper($uploaderInitials ?: 'U') }}</span>
                                    {{ $uploaderName }}
                                </span>
                            </td>
                            <td><span class="archive-status">Arsip</span></td>
                            <td>
                                <div class="archive-actions">
                                    <button class="archive-action-button" type="button" disabled aria-label="Pratinjau dokumen belum tersedia" title="Pratinjau belum tersedia">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6Z"/><circle cx="12" cy="12" r="3"/></svg>
                                    </button>
                                    @if ($item->file_available)
                                        <a class="archive-action-button" href="{{ route('dokumen.download', $item) }}" aria-label="Unduh {{ $item->nama_file }}" title="Unduh dokumen">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4"/></svg>
                                        </a>
                                    @else
                                        <button class="archive-action-button" type="button" disabled aria-label="Berkas tidak tersedia" title="Berkas tidak tersedia di penyimpanan">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3v12m-5-5 5 5 5-5M4 17v4h16v-4"/></svg>
                                        </button>
                                    @endif
                                    <form method="POST" action="{{ route('dokumen.restore', $item) }}">
                                        @csrf
                                        <button class="archive-restore-button" type="submit" aria-label="Aktifkan kembali {{ $item->judul }}">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 12a9 9 0 1 0 2.64-6.36L3 8"/><path d="M3 3v5h5m4-1v5l3 2"/></svg>
                                            Aktifkan
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="archive-empty-cell" colspan="8">
                                {{ $search !== '' || $selectedKategori !== '' || $selectedTahun !== '' ? 'Tidak ada arsip yang sesuai dengan filter.' : 'Belum ada dokumen yang diarsipkan.' }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="archive-table-footer">
            <span>
                @if ($dokumen->total() > 0)
                    Menampilkan <strong>{{ $dokumen->firstItem() }}–{{ $dokumen->lastItem() }}</strong> dari <strong>{{ number_format($dokumen->total(), 0, ',', '.') }}</strong> arsip
                @else
                    Menampilkan <strong>0</strong> dari <strong>0</strong> arsip
                @endif
            </span>
            <div class="archive-pagination">{{ $dokumen->onEachSide(1)->links() }}</div>
        </div>
    </section>

    <aside class="archive-policy-note" aria-label="Keterangan pengelolaan arsip">
        <span class="archive-policy-icon">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m12 3 8 3v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3Z"/><path d="m9 12 2 2 4-4"/></svg>
        </span>
        <span>
            <strong>Ketentuan Tata Kelola &amp; Pengaktifan Kembali Arsip</strong>
            <span>Dokumen yang diaktifkan kembali akan berubah ke status Dokumen Aktif dan tercatat pada Log Aktivitas. Masa retensi yang ditampilkan mengikuti data yang tercatat pada dokumen.</span>
        </span>
    </aside>
</div>
@endsection