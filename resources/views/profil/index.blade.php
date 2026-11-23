
@extends('layouts.app')

@section('title', 'Profil Saya - SIDOKSPI')
@section('active-menu', 'profil')
@section('breadcrumb', 'Pengaturan / Profil Saya')

@push('styles')
<style>
    .profil-container {
        max-width: 850px;
        margin: 0 auto;
        color: #263650;
    }

    .profil-header {
        margin-bottom: 20px;
    }

    .profil-header h2 {
        margin: 0 0 6px;
        font-size: 23px;
        color: #17345f;
    }

    .profil-header p {
        margin: 0;
        color: #78839a;
        font-size: 13px;
    }

    .profil-card {
        padding: 24px;
        border: 1px solid #edf0f8;
        border-radius: 12px;
        background: #fff;
    }

    .profil-identitas {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid #edf0f8;
    }

    .profil-avatar {
        display: grid;
        width: 60px;
        height: 60px;
        place-items: center;
        border-radius: 50%;
        background: #0753c5;
        color: white;
        font-size: 22px;
        font-weight: 800;
    }

    .profil-identitas h3 {
        margin: 0 0 5px;
        font-size: 17px;
    }

    .profil-identitas p {
        margin: 0;
        color: #78839a;
        font-size: 12px;
    }

    .profil-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .profil-field {
        margin-bottom: 16px;
    }

    .profil-field.full {
        grid-column: 1 / -1;
    }

    .profil-field label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 700;
    }

    .profil-field input {
        width: 100%;
        min-height: 40px;
        padding: 9px 11px;
        border: 1px solid #dce2ee;
        border-radius: 7px;
        outline: none;
        font-size: 13px;
    }

    .profil-field input:focus {
        border-color: #0753c5;
        box-shadow: 0 0 0 3px #0753c51a;
    }

    .profil-help {
        margin-top: 6px;
        color: #8993a5;
        font-size: 11px;
    }

    .profil-button {
        padding: 10px 18px;
        border: none;
        border-radius: 7px;
        background: #0753c5;
        color: white;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .profil-button:hover {
        background: #06449f;
    }

    .profil-alert {
        margin-bottom: 15px;
        padding: 12px;
        border-radius: 7px;
        font-size: 13px;
    }

    .profil-success {
        background: #effcf6;
        color: #08734e;
    }

    .profil-error {
        background: #fff1f1;
        color: #c62828;
    }

    @media(max-width: 600px) {
        .profil-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<div class="profil-container">

    <div class="profil-header">
        <h2>Profil Saya</h2>
        <p>Kelola informasi akun dan data pribadi Anda.</p>
    </div>

    <div class="profil-card">

        <div class="profil-identitas">
            <div class="profil-avatar">
                {{ mb_strtoupper(mb_substr($user->nama_lengkap ?? 'A', 0, 1)) }}
            </div>

            <div>
                <h3>{{ $user->nama_lengkap }}</h3>
                <p>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
            </div>
        </div>

        @if (session('success'))
            <div class="profil-alert profil-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="profil-alert profil-error">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profil.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="profil-grid">

                <div class="profil-field full">
                    <label>Nama Lengkap</label>
                    <input
                        type="text"
                        name="nama_lengkap"
                        value="{{ old('nama_lengkap', $user->nama_lengkap) }}"
                        required
                    >
                </div>

                <div class="profil-field">
                    <label>NIP</label>
                    <input
                        type="text"
                        name="nip"
                        value="{{ old('nip', $user->nip) }}"
                        required
                    >
                </div>

                <div class="profil-field">
                    <label>Email</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $user->email) }}"
                        required
                    >
                </div>

                <div class="profil-field full">
                    <label>Password Baru</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="Kosongkan jika tidak ingin mengubah password"
                    >
                    <p class="profil-help">
                        Minimal 8 karakter. Kosongkan jika password tidak ingin diubah.
                    </p>
                </div>

                <div class="profil-field full">
                    <label>Konfirmasi Password Baru</label>
                    <input
                        type="password"
                        name="password_confirmation"
                        placeholder="Masukkan kembali password baru"
                    >
                </div>

            </div>

            <button type="submit" class="profil-button">
                Simpan Perubahan
            </button>

        </form>

    </div>
</div>

@endsection
