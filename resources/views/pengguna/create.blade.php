@extends('layouts.app')

@section('title', 'Tambah Pengguna - SIDOKSPI')
@section('active-menu', 'pengguna')
@section('breadcrumb', 'Master Data / Manajemen Pengguna / Tambah Pengguna')

@push('styles')
<link rel="stylesheet" href="{{ asset('asset/css/laporan.css') }}">

<style>
    .pengguna-form-page {
        padding-bottom: 30px;
    }

    .pengguna-form-panel {
        background: #fff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 3px 15px rgba(20, 45, 80, .05);
    }

    .pengguna-form-title {
        margin-bottom: 22px;
        padding-bottom: 15px;
        border-bottom: 1px solid #edf0f5;
    }

    .pengguna-form-title h3 {
        margin: 0 0 6px;
        font-size: 18px;
        font-weight: 750;
        color: #18345b;
    }

    .pengguna-form-title p {
        margin: 0;
        font-size: 13px;
        color: #718096;
    }

    .pengguna-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .pengguna-form-group {
        margin-bottom: 5px;
    }

    .pengguna-form-group.full {
        grid-column: 1 / -1;
    }

    .pengguna-form-group label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 700;
        color: #34445d;
    }

    .pengguna-form-group input,
    .pengguna-form-group select {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #dce3ef;
        border-radius: 8px;
        font-size: 13px;
        outline: none;
        background: #fff;
        color: #34445d;
    }

    .pengguna-form-group input:focus,
    .pengguna-form-group select:focus {
        border-color: #0753c5;
        box-shadow: 0 0 0 3px rgba(7, 83, 197, .1);
    }

    .pengguna-form-group small {
        display: block;
        margin-top: 6px;
        color: #8792a3;
        font-size: 11px;
    }

    .pengguna-error {
        color: #c03945;
        font-size: 12px;
        margin-top: 5px;
    }

    .pengguna-form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 25px;
        padding-top: 20px;
        border-top: 1px solid #edf0f5;
    }

    .pengguna-btn {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        padding: 10px 17px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
    }

    .pengguna-btn-primary {
        background: #0753c5;
        color: white;
    }

    .pengguna-btn-primary:hover {
        background: #0644a1;
        color: white;
    }

    .pengguna-btn-cancel {
        background: #edf0f5;
        color: #44546a;
    }

    .pengguna-btn-cancel:hover {
        background: #dfe5ee;
        color: #34445d;
    }

    @media(max-width: 650px) {
        .pengguna-form-grid {
            grid-template-columns: 1fr;
        }

        .pengguna-form-group.full {
            grid-column: auto;
        }
    }
</style>
@endpush

@section('content')

<div class="laporan-page pengguna-form-page">

    <div class="laporan-header">
        <div class="laporan-heading">
            <div class="laporan-breadcrumb">
                MASTER DATA / MANAJEMEN PENGGUNA / TAMBAH
            </div>

            <h2>Tambah Pengguna</h2>

            <p>
                Tambahkan akun pengguna baru ke dalam sistem SIDOKSPI.
            </p>
        </div>
    </div>

    <div class="pengguna-form-panel">

        <div class="pengguna-form-title">
            <h3>Formulir Data Pengguna</h3>
            <p>Lengkapi informasi akun pengguna dengan benar.</p>
        </div>

        <form action="{{ route('pengguna.store') }}" method="POST">
            @csrf

            <div class="pengguna-form-grid">

                {{-- NAMA --}}
                <div class="pengguna-form-group">
                    <label for="nama_lengkap">Nama Lengkap *</label>

                    <input
                        type="text"
                        id="nama_lengkap"
                        name="nama_lengkap"
                        maxlength="100"
                        value="{{ old('nama_lengkap') }}"
                        placeholder="Masukkan nama lengkap"
                        required
                    >

                    @error('nama_lengkap')
                        <div class="pengguna-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- NIP --}}
                <div class="pengguna-form-group">
                    <label for="nip">NIP</label>

                    <input
                        type="text"
                        id="nip"
                        name="nip"
                        maxlength="30"
                        value="{{ old('nip') }}"
                        placeholder="Masukkan NIP"
                    >

                    @error('nip')
                        <div class="pengguna-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="pengguna-form-group full">
                    <label for="email">Email *</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        maxlength="100"
                        value="{{ old('email') }}"
                        placeholder="contoh@email.com"
                        required
                    >

                    @error('email')
                        <div class="pengguna-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="pengguna-form-group">
                    <label for="password">Kata Sandi *</label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        minlength="8"
                        placeholder="Minimal 8 karakter"
                        required
                    >

                    @error('password')
                        <div class="pengguna-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- KONFIRMASI PASSWORD --}}
                <div class="pengguna-form-group">
                    <label for="password_confirmation">
                        Konfirmasi Kata Sandi *
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        minlength="8"
                        placeholder="Ulangi kata sandi"
                        required
                    >
                </div>

                {{-- ROLE --}}
                <div class="pengguna-form-group">
                    <label for="role">Role Pengguna *</label>

                    <select id="role" name="role" required>
                        <option value="">Pilih Role</option>

                        <option value="admin"
                            {{ old('role') === 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="staf_spi"
                            {{ old('role', 'staf_spi') === 'staf_spi' ? 'selected' : '' }}>
                            Staf SPI
                        </option>

                        <option value="auditee"
                            {{ old('role') === 'auditee' ? 'selected' : '' }}>
                            Auditee
                        </option>
                    </select>

                    <small>
                        Pilih hak akses sesuai jenis pengguna.
                    </small>

                    @error('role')
                        <div class="pengguna-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- BIDANG --}}
                <div class="pengguna-form-group">
                    <label for="bidang">Bidang</label>

                    <input
                        type="text"
                        id="bidang"
                        name="bidang"
                        maxlength="100"
                        value="{{ old('bidang') }}"
                        placeholder="Masukkan bidang"
                    >

                    @error('bidang')
                        <div class="pengguna-error">{{ $message }}</div>
                    @enderror
                </div>

                {{-- STATUS --}}
                <div class="pengguna-form-group">
                    <label for="status">Status Pengguna *</label>

                    <select id="status" name="status" required>
                        <option value="aktif"
                            {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="nonaktif"
                            {{ old('status') === 'nonaktif' ? 'selected' : '' }}>
                            Nonaktif
                        </option>
                    </select>

                    @error('status')
                        <div class="pengguna-error">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="pengguna-form-footer">

                <a
                    href="{{ route('pengguna.index') }}"
                    class="pengguna-btn pengguna-btn-cancel"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="pengguna-btn pengguna-btn-primary"
                >
                    Simpan Pengguna
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
