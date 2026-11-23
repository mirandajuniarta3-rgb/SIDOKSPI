
@extends('layouts.app')

@section('title', 'Manajemen Pengguna - SIDOKSPI')
@section('active-menu', 'pengguna')
@section('breadcrumb', 'Master Data / Manajemen Pengguna')

@push('styles')
<link rel="stylesheet" href="{{ asset('asset/css/laporan.css') }}">

<style>
    .pengguna-page {
        padding-bottom: 30px;
    }

    .pengguna-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        padding: 0 20px;
        margin-bottom: 22px;
    }

    .pengguna-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 7px;
        padding: 10px 16px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
    }

    .pengguna-btn-primary {
        background: #0753c5;
        color: white;
    }

    .pengguna-btn-primary:hover {
        background: #0644a1;
        color: white;
    }

    .pengguna-search {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .pengguna-search input {
        width: 260px;
        max-width: 100%;
        padding: 10px 13px;
        border: 1px solid #dce3ef;
        border-radius: 8px;
        outline: none;
    }

    .pengguna-search input:focus {
        border-color: #0753c5;
        box-shadow: 0 0 0 3px rgba(7, 83, 197, .1);
    }

    .pengguna-role,
    .pengguna-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .pengguna-role {
        color: #0753c5;
        background: #edf2ff;
    }

    .status-aktif {
        color: #16834a;
        background: #e7f8ee;
    }

    .status-nonaktif {
        color: #c03945;
        background: #fff0f0;
    }

    .pengguna-actions {
        display: flex;
        gap: 7px;
        align-items: center;
    }

    .pengguna-action {
        padding: 7px 11px;
        border: none;
        border-radius: 6px;
        text-decoration: none;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
    }

    .action-edit {
        background: #edf2ff;
        color: #0753c5;
    }

    .action-delete {
        background: #fff0f0;
        color: #d43d4b;
    }

    .pengguna-alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin: 0 0 16px;
        font-size: 13px;
    }

    .pengguna-alert-success {
        background: #e7f8ee;
        color: #16834a;
    }

    .pengguna-alert-error {
        background: #fff0f0;
        color: #c03945;
    }

    .pengguna-empty {
        text-align: center;
        padding: 30px;
        color: #8792a3;
    }

    @media(max-width: 650px) {
        .pengguna-search,
        .pengguna-search input {
            width: 100%;
        }

        .pengguna-toolbar {
            align-items: stretch;
        }
    }
</style>
@endpush

@section('content')

<div class="laporan-page pengguna-page">

    {{-- HEADER --}}
    <div class="laporan-header">
        <div class="laporan-heading">
            <div class="laporan-breadcrumb">
                MASTER DATA / MANAJEMEN PENGGUNA
            </div>

            <h2>Manajemen Pengguna</h2>

            <p>
                Kelola data akun pengguna yang memiliki akses ke sistem SIDOKSPI.
            </p>
        </div>

        <a href="{{ route('pengguna.create') }}"
           class="pengguna-btn pengguna-btn-primary">
            + Tambah Pengguna
        </a>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="pengguna-alert pengguna-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="pengguna-alert pengguna-alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="pengguna-alert pengguna-alert-error">
            <strong>Periksa kembali data yang dimasukkan:</strong>
            <ul style="margin:7px 0 0 18px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- PANEL TABEL --}}
    <div class="laporan-panel">

        <div class="panel-heading">
            <h3>Daftar Pengguna</h3>
            <p>
                Daftar akun yang terdaftar dalam sistem SIDOKSPI.
            </p>
        </div>

        {{-- PENCARIAN --}}
        <div class="pengguna-toolbar">

            <form action="{{ route('pengguna.index') }}"
                  method="GET"
                  class="pengguna-search">

                <input type="text"
                       name="search"
                       placeholder="Cari nama, NIP, atau email..."
                       value="{{ request('search') }}">

                <button type="submit"
                        class="pengguna-btn pengguna-btn-primary">
                    Cari
                </button>

                @if(request()->filled('search'))
                    <a href="{{ route('pengguna.index') }}"
                       class="pengguna-btn"
                       style="background:#edf0f5;color:#44546a;">
                        Reset
                    </a>
                @endif

            </form>

            <div style="font-size:12px;color:#718096;">
                Total {{ $pengguna->total() }} pengguna
            </div>

        </div>

        {{-- TABEL --}}
        <div class="table-wrapper">

            <table class="laporan-table">

                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama Lengkap</th>
                        <th>NIP</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Bidang</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($pengguna as $index => $item)

                        <tr>
                            <td>
                                {{ $pengguna->firstItem() + $index }}
                            </td>

                            <td class="table-primary-text">
                                {{ $item->nama_lengkap }}
                            </td>

                            <td>
                                {{ $item->nip ?: '-' }}
                            </td>

                            <td>
                                {{ $item->email }}
                            </td>

                            <td>
                                <span class="pengguna-role">
                                    {{ strtoupper(str_replace('_', ' ', $item->role)) }}
                                </span>
                            </td>

                            <td>
                                {{ $item->bidang ?: '-' }}
                            </td>

                            <td>
                                @if($item->status === 'aktif')
                                    <span class="pengguna-status status-aktif">
                                        Aktif
                                    </span>
                                @else
                                    <span class="pengguna-status status-nonaktif">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="pengguna-actions">

                                    <a href="{{ route('pengguna.edit', $item->id) }}"
                                       class="pengguna-action action-edit">
                                        Edit
                                    </a>

                                    <form action="{{ route('pengguna.destroy', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="pengguna-action action-delete">
                                            Hapus
                                        </button>

                                    </form>

                                </div>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="8" class="pengguna-empty">
                                Belum ada data pengguna.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- FOOTER TABEL --}}
        <div class="table-footer">

            <div class="table-info">
                Menampilkan {{ $pengguna->firstItem() ?? 0 }}
                sampai {{ $pengguna->lastItem() ?? 0 }}
                dari {{ $pengguna->total() }} pengguna
            </div>

            <div>
                {{ $pengguna->links() }}
            </div>

        </div>

    </div>

</div>

@endsection
