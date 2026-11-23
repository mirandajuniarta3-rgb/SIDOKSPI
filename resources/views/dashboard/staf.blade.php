
@extends('layouts.app')

@section('title', 'Dashboard Staf SPI - SIDOKSPI')
@section('active-menu', 'dashboard')
@section('breadcrumb', 'Dashboard Staf SPI')

@php
    $user = auth()->user();

    $role = strtolower(trim($user->role ?? ''));

    $nama = $user->nama_lengkap
        ?? $user->name
        ?? 'Pengguna SIDOKSPI';

    $roleBadge = match ($role) {
        'admin' => 'Administrator SPI',
        'staf_spi' => 'Staf SPI',
        'auditee' => 'Auditee',
        default => 'Pengguna',
    };

    $jabatan = match ($role) {
        'admin' => 'Administrator',
        'staf_spi' => 'Staf SPI',
        'auditee' => 'Auditee',
        default => 'Pengguna',
    };

    $canSwitchAccount = in_array($role, ['admin', 'staf_spi'], true);

    $initials = collect(explode(' ', trim($nama)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');

    $breadcrumb = $breadcrumb ?? 'Dashboard Staf SPI';
    $searchAction = $searchAction ?? route('dashboard');
@endphp

@push('styles')
<style>
    .account-switcher {
        position: relative;
        display: inline-flex;
        align-items: center;
        flex: 0 0 auto;
        margin: 0;
    }

    .account-switcher summary {
        list-style: none;
        cursor: pointer;
    }

    .account-switcher summary::-webkit-details-marker {
        display: none;
    }

    .account-switch-trigger {
        display: inline-flex !important;
        align-items: center;
        justify-content: center;
        gap: 7px;
        width: auto !important;
        min-width: 0;
        min-height: 38px;
        padding: 4px 8px !important;
        margin: 0;
        border: 1px solid transparent;
        border-radius: 9px;
        background: transparent;
        color: #263650;
        line-height: 1.2;
        box-shadow: none;
        transition: background .2s ease, border-color .2s ease;
    }

    .account-switch-trigger:hover {
        background: #f3f6fb;
        border-color: #e8edf5;
    }

    .account-switch-trigger .account {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
    }

    .account-switch-trigger .account-name {
        display: block;
        max-width: 125px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-size: 11px;
        font-weight: 700;
    }

    .account-switch-trigger .account-role {
        display: block;
        margin-top: 2px;
        color: #8290a8;
        font-size: 10px;
        line-height: 1.2;
    }

    .account-switch-trigger .avatar,
    .account .avatar {
        width: 30px;
        height: 30px;
        min-width: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 11px;
    }

    .account-switch-chevron {
        display: block;
        width: 13px;
        height: 13px;
        flex-shrink: 0;
        color: #718096;
        transition: transform .2s ease;
    }

    .account-switcher[open] .account-switch-chevron {
        transform: rotate(180deg);
    }

    .account-switch-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        z-index: 9999;
        width: 290px;
        max-width: calc(100vw - 24px);
        padding: 17px;
        background: #fff;
        border: 1px solid #e5e9f2;
        border-radius: 12px;
        box-shadow: 0 10px 28px rgba(30, 45, 75, .14);
    }

    .account-switch-title {
        margin: 0 0 5px;
        color: #263650;
        font-size: 14px;
        font-weight: 700;
    }

    .account-switch-description {
        margin: 0 0 14px;
        color: #718096;
        font-size: 11px;
        line-height: 1.5;
    }

    .account-switch-field {
        margin-bottom: 12px;
    }

    .account-switch-field label {
        display: block;
        margin-bottom: 5px;
        color: #34445d;
        font-size: 11px;
        font-weight: 650;
    }

    .account-switch-field input {
        display: block;
        width: 100%;
        min-height: 37px;
        box-sizing: border-box;
        padding: 8px 10px;
        border: 1px solid #dce2ec;
        border-radius: 7px;
        outline: none;
        color: #263650;
        font-size: 12px;
    }

    .account-switch-field input:focus {
        border-color: #5369d8;
        box-shadow: 0 0 0 3px rgba(83, 105, 216, .10);
    }

    .account-switch-submit {
        display: block;
        width: 100%;
        min-height: 37px;
        padding: 8px 12px;
        border: none;
        border-radius: 7px;
        background: #5369d8;
        color: #fff;
        font-size: 12px;
        font-weight: 650;
        cursor: pointer;
        transition: background .2s ease;
    }

    .account-switch-submit:hover {
        background: #4055bd;
    }

    .account-switch-error {
        margin-bottom: 12px;
        padding: 9px;
        border-radius: 7px;
        background: #fff0f0;
        color: #c0392b;
        font-size: 11px;
        line-height: 1.5;
    }

    @media (max-width: 600px) {
        .account-switch-trigger {
            gap: 5px;
            padding: 4px 5px !important;
        }

        .account-switch-trigger .account-name {
            max-width: 85px;
            font-size: 10px;
        }

        .account-switch-trigger .account-role {
            font-size: 9px;
        }

        .account-switch-trigger .avatar {
            width: 27px;
            height: 27px;
            min-width: 27px;
        }

        .account-switch-dropdown {
            right: -35px;
        }
    }
</style>
@endpush

@section('content')

<div class="staff-dashboard">

    <div class="staff-welcome">
        <div>
            <h2>Selamat Datang, {{ $nama }}</h2>
            <p>Berikut informasi aktivitas dan pekerjaan Staf SPI.</p>
        </div>
    </div>

    <div class="staff-stat-grid">

        <div class="staff-stat-card">
            <span>Total Dokumen Final</span>
            <strong>{{ $totalDokumenFinal ?? 0 }}</strong>
        </div>

        <div class="staff-stat-card">
            <span>Dokumen Draft</span>
            <strong>{{ $totalDokumenDraft ?? 0 }}</strong>
        </div>

        <div class="staff-stat-card">
            <span>Total Surat Keputusan</span>
            <strong>{{ $totalSK ?? 0 }}</strong>
        </div>

        <div class="staff-stat-card">
            <span>Total Arsip</span>
            <strong>{{ $totalArsip ?? 0 }}</strong>
        </div>

    </div>

    <div class="staff-section">
        <h3>Daftar Dokumen Menunggu</h3>

        @if(isset($daftarMenunggu) && $daftarMenunggu->count())
            <div class="table-responsive">
                <table class="staff-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Dokumen</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($daftarMenunggu as $index => $dokumen)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $dokumen->nama_dokumen ?? $dokumen->judul ?? '-' }}</td>
                                <td>{{ $dokumen->kategori?->nama_kategori ?? '-' }}</td>
                                <td>
                                    {{ $dokumen->created_at?->format('d/m/Y') ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="staff-empty">Tidak ada dokumen yang menunggu saat ini.</p>
        @endif
    </div>

    <div class="staff-section">
        <h3>Aktivitas Saya</h3>

        @if(isset($aktivitasSaya) && $aktivitasSaya->count())
            <div class="table-responsive">
                <table class="staff-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Aktivitas</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($aktivitasSaya as $index => $aktivitas)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    {{ $aktivitas->aktivitas ?? $aktivitas->deskripsi ?? '-' }}
                                </td>
                                <td>
                                    {{ $aktivitas->created_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="staff-empty">Belum ada aktivitas yang tercatat.</p>
        @endif
    </div>

</div>

@endsection

@push('styles')
<style>
    .staff-dashboard {
        display: flex;
        flex-direction: column;
        gap: 22px;
        color: #263650;
    }

    .staff-welcome {
        padding: 24px;
        border-radius: 14px;
        background: linear-gradient(120deg, #0753c5, #3478dc);
        color: #fff;
    }

    .staff-welcome h2 {
        margin: 0 0 8px;
        font-size: 22px;
    }

    .staff-welcome p {
        margin: 0;
        font-size: 13px;
        opacity: .9;
    }

    .staff-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .staff-stat-card {
        padding: 20px;
        border: 1px solid #e8edf5;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 3px 12px rgba(30, 45, 75, .04);
    }

    .staff-stat-card span {
        display: block;
        margin-bottom: 12px;
        color: #718096;
        font-size: 12px;
    }

    .staff-stat-card strong {
        color: #0753c5;
        font-size: 27px;
    }

    .staff-section {
        padding: 20px;
        border: 1px solid #e8edf5;
        border-radius: 12px;
        background: #fff;
    }

    .staff-section h3 {
        margin: 0 0 16px;
        font-size: 16px;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .staff-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .staff-table th,
    .staff-table td {
        padding: 12px;
        border-bottom: 1px solid #edf0f5;
        text-align: left;
    }

    .staff-table th {
        background: #f7f9fc;
        color: #52627a;
    }

    .staff-empty {
        margin: 0;
        padding: 15px;
        color: #8290a8;
        font-size: 13px;
    }

    @media (max-width: 900px) {
        .staff-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 500px) {
        .staff-stat-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush
