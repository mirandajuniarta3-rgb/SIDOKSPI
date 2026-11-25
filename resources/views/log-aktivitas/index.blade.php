@extends('layouts.app')

@section('title', 'Log Aktivitas - SIDOKSPI')
@section('active-menu', 'log-aktivitas')
@section('breadcrumb', 'Pengawasan & Keamanan / Log Aktivitas')

@push('styles')
    <link rel="stylesheet"
          href="{{ asset('css/laporan.css') }}">

    <link rel="stylesheet"
          href="{{ asset('css/log-aktivitas.css') }}?v=1">
@endpush

@section('content')

<div class="laporan-page log-page">

    <div class="laporan-header">

        <div class="laporan-heading">

            <div class="laporan-breadcrumb">
                PENGAWASAN & KEAMANAN / LOG AKTIVITAS
            </div>

            <h2>Log Aktivitas & Audit Trail</h2>

            <p>
                Pantau riwayat aktivitas pengguna, perubahan data,
                dan akses dokumen dalam sistem SIDOKSPI.
            </p>

        </div>

        <div class="log-date">

            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.8"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 aria-hidden="true">

                <rect x="3" y="5" width="18" height="16" rx="2"/>
                <path d="M16 3v4M8 3v4M3 10h18"/>

            </svg>

            {{ now()->locale('id')->translatedFormat('l, d F Y') }}

        </div>

    </div>

    <div class="log-stats">

        <div class="log-stat">

            <div class="log-stat-top">

                <span class="log-stat-label">
                    Total Aktivitas Tercatat
                </span>

                <div class="log-stat-icon icon-blue">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         aria-hidden="true">

                        <rect x="5" y="4" width="14" height="17" rx="2"/>
                        <path d="M9 4V2h6v2M9 10h6M9 14h6M9 18h4"/>

                    </svg>

                </div>

            </div>

            <div class="log-stat-value">
                {{ number_format($totalLog, 0, ',', '.') }}
            </div>

            <div class="log-stat-note">
                Seluruh riwayat aktivitas tersimpan
            </div>

        </div>

        <div class="log-stat">

            <div class="log-stat-top">

                <span class="log-stat-label">
                    Aktivitas Hari Ini
                </span>

                <div class="log-stat-icon icon-blue">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         aria-hidden="true">

                        <circle cx="12" cy="12" r="9"/>
                        <path d="M12 7v5l3 2"/>

                    </svg>

                </div>

            </div>

            <div class="log-stat-value">
                {{ number_format($aktivitasHariIni, 0, ',', '.') }}
            </div>

            <div class="log-stat-note">
                Aktivitas pada tanggal hari ini
            </div>

        </div>

        <div class="log-stat">

            <div class="log-stat-top">

                <span class="log-stat-label">
                    Pengguna Aktif
                </span>

                <div class="log-stat-icon icon-green">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         aria-hidden="true">

                        <circle cx="9" cy="8" r="3"/>
                        <path d="M3 20v-2a6 6 0 0 1 12 0v2"/>
                        <path d="M16 5a3 3 0 0 1 0 6M21 20v-2a6 6 0 0 0-4-5"/>

                    </svg>

                </div>

            </div>

            <div class="log-stat-value">
                {{ number_format($penggunaAktif, 0, ',', '.') }}
            </div>

            <div class="log-stat-note">
                Pengguna beraktivitas hari ini
            </div>

        </div>

        <div class="log-stat">

            <div class="log-stat-top">

                <span class="log-stat-label">
                    Aktivitas dengan Referensi
                </span>

                <div class="log-stat-icon icon-purple">

                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="1.8"
                         stroke-linecap="round"
                         stroke-linejoin="round"
                         aria-hidden="true">

                        <path d="M6 3h8l4 4v14H6z"/>
                        <path d="M14 3v5h4M9 12h6M9 16h6"/>

                    </svg>

                </div>

            </div>

            <div class="log-stat-value">
                {{ number_format($aktivitasDenganReferensi, 0, ',', '.') }}
            </div>

            <div class="log-stat-note">
                Aktivitas terkait dokumen atau SK
            </div>

        </div>

    </div>

    <div class="log-panel">

        <div class="log-panel-heading">

            <h3>Filter dan Pencarian Aktivitas</h3>

            <p>
                Temukan riwayat aktivitas berdasarkan pengguna,
                aksi, tanggal, atau kata kunci.
            </p>

        </div>

        <form action="{{ route('log-aktivitas.index') }}"
              method="GET"
              id="logSearchForm"
              class="log-filter">

            <input type="hidden"
                   name="sort"
                   id="logSort"
                   value="{{ $sort }}">

            <input type="hidden"
                   name="direction"
                   id="logDirection"
                   value="{{ $direction }}">

            <div class="log-filter-grid">

                <div class="log-field">

                    <label for="user_id">Pengguna</label>

                    <select name="user_id" id="user_id">

                        <option value="">Semua Pengguna</option>

                        @foreach ($daftarPengguna as $pengguna)

                            <option value="{{ $pengguna->id }}"
                                @selected(request('user_id') == $pengguna->id)>

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
                                @selected(request('aksi') == $aksi)>

                                {{ ucfirst(str_replace('_', ' ', $aksi)) }}

                            </option>

                        @endforeach

                    </select>

                </div>

                <div class="log-field">

                    <label for="tanggal_mulai">
                        Tanggal Mulai
                    </label>

                    <input type="date"
                           name="tanggal_mulai"
                           id="tanggal_mulai"
                           value="{{ request('tanggal_mulai') }}">

                </div>

                <div class="log-field">

                    <label for="tanggal_akhir">
                        Tanggal Akhir
                    </label>

                    <input type="date"
                           name="tanggal_akhir"
                           id="tanggal_akhir"
                           value="{{ request('tanggal_akhir') }}">

                </div>

                <div class="log-field log-search-field">

                    <label for="logSearchInput">
                        Kata Kunci
                    </label>

                    <div class="log-search-box">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             stroke-linecap="round"
                             aria-hidden="true">

                            <circle cx="11" cy="11" r="7"/>
                            <path d="m16 16 4 4"/>

                        </svg>

                        <input type="search"
                               name="search"
                               id="logSearchInput"
                               value="{{ $search }}"
                               maxlength="100"
                               autocomplete="off"
                               placeholder="Cari aktivitas...">

                    </div>

                </div>

                <div class="log-filter-actions">

                    <a href="{{ route('log-aktivitas.index') }}"
                       id="logReset"
                       class="log-btn log-btn-reset">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="1.8"
                             stroke-linecap="round"
                             stroke-linejoin="round"
                             aria-hidden="true">

                            <path d="M3 12a9 9 0 1 1 3 6.7"/>
                            <path d="M3 5v7h7"/>

                        </svg>

                        Reset

                    </a>

                </div>

            </div>

        </form>

    </div>

    <div class="log-panel log-results-panel"
         id="logResultsPanel"
         aria-busy="false">

        <div class="log-loading"
             id="logLoading"
             role="status"
             hidden>

            <div class="log-loading-content">

                <span class="log-spinner"></span>

                <span>Memuat data...</span>

            </div>

        </div>

        <div id="logResults">

            @include('log-aktivitas.partials.results')

        </div>

    </div>

    <div class="log-audit-footer">

        <div class="log-audit-icon">

            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="1.8"
                 stroke-linecap="round"
                 stroke-linejoin="round"
                 aria-hidden="true">

                <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11z"/>
                <path d="m9 12 2 2 4-4"/>

            </svg>

        </div>

        <div>

            <div class="log-audit-title">
                Informasi Log Aktivitas
            </div>

            <p class="log-audit-text">
                Riwayat aktivitas mencakup pengguna, aksi, waktu,
                alamat IP, dan referensi dokumen atau surat keputusan.
                Validasi integritas secara kriptografi belum tersedia
                pada struktur database saat ini.
            </p>

        </div>

    </div>

</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/log-aktivitas.js') }}?v=1"
            defer></script>
@endpush
