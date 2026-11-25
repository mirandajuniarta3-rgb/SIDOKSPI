@extends('layouts.app')
@section('active-menu', 'pengguna')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/pengguna.css') }}">
@include('partials.sidokspi-feedback')
@endpush
@section('title', 'Edit Pengguna - SIDOKSPI')
@section('breadcrumb', 'Master Data / Manajemen Pengguna / Edit Pengguna')
@section('content')
<div class="pengguna-page pengguna-form-page">
    <header class="pengguna-header"><div><div class="pengguna-eyebrow">MASTER DATA / MANAJEMEN PENGGUNA / EDIT</div><h2>Edit Pengguna</h2><p>Perbarui informasi akun pengguna SIDOKSPI.</p></div></header>
    <section class="pengguna-panel">
        <div class="pengguna-panel-heading"><h3>Formulir Edit Pengguna</h3><p>Lengkapi informasi akun pengguna dengan benar.</p></div>
        <form class="pengguna-form" action="{{ route('pengguna.update', $pengguna->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="pengguna-form-grid">
                <div class="pengguna-field"><label for="nama_lengkap">Nama Lengkap *</label>
                    <input id="nama_lengkap" name="nama_lengkap" type="text" maxlength="100" value="{{ old('nama_lengkap', $pengguna->nama_lengkap) }}" required>
                    @error('nama_lengkap')<div class="pengguna-error">{{ $message }}</div>@enderror
                </div>
                <div class="pengguna-field"><label for="nip">NIP</label>
                    <input id="nip" name="nip" type="text" maxlength="30" value="{{ old('nip', $pengguna->nip) }}">
                    @error('nip')<div class="pengguna-error">{{ $message }}</div>@enderror
                </div>
                <div class="pengguna-field full"><label for="email">Email *</label>
                    <input id="email" name="email" type="email" maxlength="100" value="{{ old('email', $pengguna->email) }}" required>
                    @error('email')<div class="pengguna-error">{{ $message }}</div>@enderror
                </div>
                <div class="pengguna-field"><label for="password">Kata Sandi Baru</label>
                    <input id="password" name="password" type="password" minlength="8" autocomplete="new-password">
                    <small>Kosongkan untuk mempertahankan kata sandi lama.</small>
                    @error('password')<div class="pengguna-error">{{ $message }}</div>@enderror
                </div>
                <div class="pengguna-field"><label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" autocomplete="new-password">
                    @error('password_confirmation')<div class="pengguna-error">{{ $message }}</div>@enderror
                </div>
                <div class="pengguna-field"><label for="role">Role Pengguna *</label>
                    <select id="role" name="role" required>
                        <option value="admin" @selected(old('role', $pengguna->role) === 'admin')>Admin</option>
                        <option value="staf_spi" @selected(old('role', $pengguna->role) === 'staf_spi')>Staf SPI</option>
                        <option value="auditee" @selected(old('role', $pengguna->role) === 'auditee')>Auditee</option>
                    </select>
                    @error('role')<div class="pengguna-error">{{ $message }}</div>@enderror
                </div>
                <div class="pengguna-field"><label for="bidang">Bidang</label>
                    <input id="bidang" name="bidang" type="text" maxlength="100" value="{{ old('bidang', $pengguna->bidang) }}">
                    @error('bidang')<div class="pengguna-error">{{ $message }}</div>@enderror
                </div>
                <div class="pengguna-field"><label for="status">Status Pengguna *</label>
                    <select id="status" name="status" required>
                        <option value="aktif" @selected(old('status', $pengguna->status) === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status', $pengguna->status) === 'nonaktif')>Nonaktif</option>
                    </select>
                    @error('status')<div class="pengguna-error">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="pengguna-form-footer"><a href="{{ route('pengguna.index') }}" class="pengguna-btn pengguna-btn-cancel">Batal</a><button type="submit" class="pengguna-btn pengguna-btn-primary">Simpan Perubahan</button></div>
        </form>
    </section>
</div>
@endsection
