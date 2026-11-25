
@extends('layouts.app')

@section('title', 'Log Aktivitas - SIDOKSPI')

@section('active-menu', 'log-aktivitas')

@section('breadcrumb', 'Pengawasan & Keamanan / Log Aktivitas')

@push('styles')
<style>
    .log-page {
        color: #263650;
    }

    /* HEADING */
    .log-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
    }

    .log-heading h2 {
        margin: 0 0 6px;
        color: #17243b;
        font-size: 23px;
        font-weight: 800;
    }

    .log-heading p {
        margin: 0;
        color: #78839a;
        font-size: 12px;
    }

    .log-heading .log-date {
        color: #78839a;
        font-size: 11px;
        white-space: nowrap;
    }

    /* STATISTIK */
    .log-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 18px;
    }

    .log-stat {
        position: relative;
        min-height: 112px;
        padding: 15px;
        overflow: hidden;
        border: 1px solid #edf0f8;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(35, 53, 90, .035);
    }

    .log-stat-label {
        color: #78839a;
        font-size: 11px;
        font-weight: 600;
    }

    .log-stat-value {
        margin-top: 9px;
        color: #17243b;
        font-size: 27px;
        font-weight: 850;
        line-height: 1.1;
    }

    .log-stat-note {
        margin-top: 7px;
        color: #8993a6;
        font-size: 10px;
    }

    .log-stat-icon {
        position: absolute;
        top: 13px;
        right: 14px;
        display: grid;
        width: 30px;
        height: 30px;
        place-items: center;
        border-radius: 8px;
        background: #edf3ff;
        color: #0753c5;
        font-size: 15px;
        font-weight: 800;
    }

    .log-stat:nth-child(2) .log-stat-icon {
        background: #eaf5ff;
        color: #1683c5;
    }

    .log-stat:nth-child(3) .log-stat-icon {
        background: #e8faf5;
        color: #159879;
    }

    .log-stat:nth-child(4) .log-stat-icon {
        background: #f2efff;
        color: #7555c7;
    }

    /* PANEL */
    .log-panel {
        margin-bottom: 16px;
        border: 1px solid #edf0f8;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(35, 53, 90, .035);
    }

    /* FILTER */
    .log-filter {
        padding: 15px;
    }

    .log-filter-title {
        margin-bottom: 12px;
        color: #66738b;
        font-size: 11px;
        font-weight: 750;
    }

    .log-filter-grid {
        display: grid;
        grid-template-columns: 1.1fr .9fr 1.2fr 1.2fr 1.5fr auto;
        gap: 9px;
        align-items: end;
    }

    .log-field label {
        display: block;
        margin-bottom: 5px;
        color: #68758c;
        font-size: 10px;
        font-weight: 700;
    }

    .log-field input,
    .log-field select {
        width: 100%;
        height: 34px;
        padding: 0 9px;
        border: 1px solid #e7eaf2;
        border-radius: 6px;
        outline: none;
        background: #fff;
        color: #40516b;
        font-size: 11px;
        box-sizing: border-box;
    }

    .log-field input:focus,
    .log-field select:focus {
        border-color: #0753c5;
        box-shadow: 0 0 0 2px rgba(7, 83, 197, .08);
    }

    .log-filter-actions {
        display: flex;
        gap: 6px;
    }

    .log-btn {
        display: inline-flex;
        height: 34px;
        align-items: center;
        justify-content: center;
        padding: 0 12px;
        border: 0;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        cursor: pointer;
    }

    .log-btn-primary {
        background: #0753c5;
        color: #fff;
    }

    .log-btn-light {
        border: 1px solid #e7eaf2;
        background: #fff;
        color: #64718a;
    }

    /* TABEL */
    .log-table-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        padding: 15px;
        border-bottom: 1px solid #edf0f8;
    }

    .log-table-title {
        margin: 0;
        color: #263650;
        font-size: 13px;
        font-weight: 800;
    }

    .log-table-count {
        padding: 4px 8px;
        border-radius: 5px;
        background: #edf3ff;
        color: #0753c5;
        font-size: 10px;
        font-weight: 750;
    }

    .log-table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    .log-table {
        width: 100%;
        min-width: 850px;
        border-collapse: collapse;
    }

    .log-table th {
        padding: 11px 12px;
        border-bottom: 1px solid #edf0f8;
        background: #fafbfe;
        color: #78839a;
        font-size: 10px;
        font-weight: 800;
        text-align: left;
        white-space: nowrap;
    }

    .log-table td {
        padding: 12px;
        border-bottom: 1px solid #f0f2f7;
        color: #40516b;
        font-size: 11px;
        vertical-align: middle;
    }

    .log-table tbody tr:hover {
        background: #fafbff;
    }

    .log-number {
        color: #8993a6;
        font-weight: 700;
    }

    .log-user {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 150px;
    }

    .log-avatar {
        display: grid;
        width: 27px;
        height: 27px;
        flex: 0 0 auto;
        place-items: center;
        border-radius: 50%;
        background: #e9f0ff;
        color: #0753c5;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .log-user-name {
        color: #263650;
        font-size: 11px;
        font-weight: 750;
    }

    .log-user-email {
        margin-top: 3px;
        color: #929bad;
        font-size: 9px;
    }

    .log-date-main {
        color: #40516b;
        font-weight: 700;
        white-space: nowrap;
    }

    .log-date-time {
        margin-top: 3px;
        color: #929bad;
        font-size: 9px;
        white-space: nowrap;
    }

    .log-action {
        display: inline-block;
        padding: 4px 7px;
        border-radius: 5px;
        background: #edf3ff;
        color: #0753c5;
        font-size: 10px;
        font-weight: 750;
        white-space: nowrap;
    }

    .log-reference {
        color: #40516b;
        font-weight: 650;
    }

    .log-ip {
        color: #68758c;
        font-family: monospace;
        font-size: 10px;
        white-space: nowrap;
    }

    .log-empty {
        padding: 35px 15px !important;
        color: #929bad !important;
        text-align: center;
    }

    /* PAGINATION */
    .log-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        padding: 13px 15px;
        border-top: 1px solid #f0f2f7;
    }

    .log-pagination-info {
        color: #8993a6;
        font-size: 10px;
    }

    .log-pagination-links {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 5px;
    }

    .log-page-link {
        display: inline-flex;
        min-width: 30px;
        height: 30px;
        align-items: center;
        justify-content: center;
        padding: 0 9px;
        border: 1px solid #e7eaf2;
        border-radius: 6px;
        background: #fff;
        color: #526078;
        font-size: 11px;
        text-decoration: none;
        box-sizing: border-box;
    }

    .log-page-link:hover {
        border-color: #0753c5;
        background: #f2f6ff;
        color: #0753c5;
    }

    .log-page-link.active {
        border-color: #0753c5;
        background: #0753c5;
        color: #fff;
        font-weight: 700;
    }

    .log-page-link.disabled {
        background: #f8f9fc;
        color: #aab2c0;
        cursor: not-allowed;
    }

    /* FOOTER AUDIT */
    .log-audit-footer {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 15px;
        border: 1px solid #e5eaf8;
        border-radius: 9px;
        background: #f4f6ff;
    }

    .log-audit-icon {
        display: grid;
        width: 34px;
        height: 34px;
        flex: 0 0 auto;
        place-items: center;
        border-radius: 8px;
        background: #e4ebff;
        color: #0753c5;
        font-size: 17px;
    }

    .log-audit-title {
        margin-bottom: 4px;
        color: #263650;
        font-size: 11px;
        font-weight: 800;
    }

    .log-audit-text {
        margin: 0;
        color: #78839a;
        font-size: 10px;
        line-height: 1.6;
    }

    /* RESPONSIVE */
    @media (max-width: 1050px) {
        .log-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .log-filter-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 620px) {
        .log-heading {
            flex-direction: column;
        }

        .log-stats {
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .log-stat {
            padding: 12px;
        }

        .log-stat-value {
            font-size: 22px;
        }

        .log-filter-grid {
            grid-template-columns: 1fr 1fr;
        }

        .log-filter-actions {
            grid-column: 1 / -1;
        }

        .log-pagination {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
<div class="log-page">

    {{-- JUDUL HALAMAN --}}
    <div class="log-heading">
        <div>
            <h2>Log Aktivitas & Audit Trail</h2>
            <p>
                Pantau riwayat aktivitas pengguna, perubahan data,
                dan akses dokumen dalam sistem SIDOKSPI.
            </p>
        </div>

        <div class="log-date">
            {{ now()->translatedFormat('l, d F Y') }}
        </div>
    </div>

    {{-- KARTU STATISTIK --}}
    <div class="log-stats">

        <div class="log-stat">
            <div class="log-stat-label">Total Aktivitas Tercatat</div>
            <div class="log-stat-icon">▤</div>
            <div class="log-stat-value">
                {{ number_format($totalLog, 0, ',', '.') }}
            </div>
            <div class="log-stat-note">
                Seluruh riwayat aktivitas tersimpan
            </div>
        </div>

        <div class="log-stat">
            <div class="log-stat-label">Aktivitas Hari Ini</div>
            <div class="log-stat-icon">◷</div>
            <div class="log-stat-value">
                {{ number_format($aktivitasHariIni, 0, ',', '.') }}
            </div>
            <div class="log-stat-note">
                Aktivitas pada tanggal hari ini
            </div>
        </div>

        <div class="log-stat">
            <div class="log-stat-label">Pengguna Aktif</div>
            <div class="log-stat-icon">♙</div>
            <div class="log-stat-value">
                {{ number_format($penggunaAktif, 0, ',', '.') }}
            </div>
            <div class="log-stat-note">
                Pengguna yang tercatat beraktivitas hari ini
            </div>
        </div>

        <div class="log-stat">
            <div class="log-stat-label">Aktivitas dengan Referensi</div>
            <div class="log-stat-icon">◇</div>
            <div class="log-stat-value">
                {{ number_format($aktivitasDenganReferensi, 0, ',', '.') }}
            </div>
            <div class="log-stat-note">
                Aktivitas terkait dokumen atau SK
            </div>
        </div>

    </div>

    {{-- FILTER --}}
    <div class="log-panel">
        <form action="{{ route('log-aktivitas.index') }}"
              method="GET"
              class="log-filter">

            <div class="log-filter-title">
                FILTER DAN PENCARIAN AKTIVITAS
            </div>

            <div class="log-filter-grid">

                <div class="log-field">
                    <label for="user_id">Pengguna</label>

                    <select name="user_id" id="user_id">
                        <option value="">Semua Pengguna</option>

                        @foreach ($daftarPengguna as $pengguna)
                            <option value="{{ $pengguna->id }}"
                                {{ request('user_id') == $pengguna->id ? 'selected' : '' }}>
                                {{ $pengguna->nama_lengkap }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="log-field">
                    <label for="aksi">Aksi</label>

                    <select name="aksi" id="aksi">
                        <option value="">Semua Aksi</option>

                        @foreach ($daftarAksi as $aksi)
                            <option value="{{ $aksi }}"
                                {{ request('aksi') == $aksi ? 'selected' : '' }}>
                                {{ ucfirst($aksi) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="log-field">
                    <label for="tanggal_mulai">Tanggal Mulai</label>

                    <input type="date"
                           name="tanggal_mulai"
                           id="tanggal_mulai"
                           value="{{ request('tanggal_mulai') }}">
                </div>

                <div class="log-field">
                    <label for="tanggal_akhir">Tanggal Akhir</label>

                    <input type="date"
                           name="tanggal_akhir"
                           id="tanggal_akhir"
                           value="{{ request('tanggal_akhir') }}">
                </div>

                <div class="log-field">
                    <label for="search">Kata Kunci</label>

                    <input type="text"
                           name="search"
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="ID, nama, aksi, IP...">
                </div>

                <div class="log-filter-actions">
                    <button type="submit" class="log-btn log-btn-primary">
                        Terapkan
                    </button>

                    <a href="{{ route('log-aktivitas.index') }}"
                       class="log-btn log-btn-light">
                        Reset
                    </a>
                </div>

            </div>
        </form>
    </div>

    {{-- TABEL AKTIVITAS --}}
    <div class="log-panel">

        <div class="log-table-head">
            <h3 class="log-table-title">
                Daftar Jejak Audit Sistem
            </h3>

            <span class="log-table-count">
                {{ number_format($aktivitas->total(), 0, ',', '.') }} data
            </span>
        </div>

        <div class="log-table-wrap">
            <table class="log-table">

                <thead>
                    <tr>
                        <th>NO</th>
                        <th>WAKTU (TANGGAL)</th>
                        <th>PENGGUNA</th>
                        <th>AKSI</th>
                        <th>OBJEK & REFERENSI</th>
                        <th>ALAMAT IP</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($aktivitas as $log)
                        <tr>

                            <td class="log-number">
                                {{ $aktivitas->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="log-date-main">
                                    {{ $log->created_at
                                        ? $log->created_at->format('d M Y')
                                        : '-' }}
                                </div>

                                <div class="log-date-time">
                                    {{ $log->created_at
                                        ? $log->created_at->format('H:i:s')
                                        : '-' }}
                                </div>
                            </td>

                            <td>
                                <div class="log-user">

                                    <div class="log-avatar">
                                        {{ substr($log->user->nama_lengkap ?? '?', 0, 1) }}
                                    </div>

                                    <div>
                                        <div class="log-user-name">
                                            {{ $log->user->nama_lengkap ?? 'Pengguna tidak ditemukan' }}
                                        </div>

                                        <div class="log-user-email">
                                            {{ $log->user->email ?? '-' }}
                                        </div>
                                    </div>

                                </div>
                            </td>

                            <td>
                                <span class="log-action">
                                    {{ ucfirst(str_replace('_', ' ', $log->aksi)) }}
                                </span>
                            </td>

                            <td>
                                @if ($log->dokumen_id)
                                    <div class="log-reference">
                                        Dokumen #{{ $log->dokumen_id }}
                                    </div>
                                @elseif ($log->sk_id)
                                    <div class="log-reference">
                                        Surat Keputusan #{{ $log->sk_id }}
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                <span class="log-ip">
                                    {{ $log->ip_address ?? '-' }}
                                </span>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="log-empty">
                                Belum ada aktivitas yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        {{-- PAGINATION TANPA IKON SVG --}}
        <div class="log-pagination">

            <div class="log-pagination-info">
                @if ($aktivitas->total() > 0)
                    Menampilkan {{ $aktivitas->firstItem() }}
                    sampai {{ $aktivitas->lastItem() }}
                    dari {{ $aktivitas->total() }} aktivitas
                @else
                    Tidak ada data untuk ditampilkan
                @endif
            </div>

            @if ($aktivitas->lastPage() > 1)
                <div class="log-pagination-links">

                    {{-- SEBELUMNYA --}}
                    @if ($aktivitas->onFirstPage())
                        <span class="log-page-link disabled">
                            Sebelumnya
                        </span>
                    @else
                        <a href="{{ $aktivitas->previousPageUrl() }}"
                           class="log-page-link">
                            Sebelumnya
                        </a>
                    @endif

                    {{-- NOMOR HALAMAN --}}
                    @for ($page = 1; $page <= $aktivitas->lastPage(); $page++)
                        @if ($page == $aktivitas->currentPage())
                            <span class="log-page-link active"
                                  aria-current="page">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $aktivitas->url($page) }}"
                               class="log-page-link">
                                {{ $page }}
                            </a>
                        @endif
                    @endfor

                    {{-- SELANJUTNYA --}}
                    @if ($aktivitas->hasMorePages())
                        <a href="{{ $aktivitas->nextPageUrl() }}"
                           class="log-page-link">
                            Selanjutnya
                        </a>
                    @else
                        <span class="log-page-link disabled">
                            Selanjutnya
                        </span>
                    @endif

                </div>
            @endif

        </div>

    </div>

    {{-- INFORMASI AUDIT --}}
    <div class="log-audit-footer">

        <div class="log-audit-icon">✓</div>

        <div>
            <div class="log-audit-title">
                Integritas Log Aktivitas
            </div>

            <p class="log-audit-text">
                Halaman ini menampilkan riwayat aktivitas yang tersimpan
                pada database SIDOKSPI, termasuk pengguna, aksi, waktu,
                alamat IP, dan referensi dokumen atau surat keputusan.
                Informasi hash dan validasi kriptografi belum tersedia
                pada struktur database saat ini.
            </p>
        </div>

    </div>

</div>
@endsection
