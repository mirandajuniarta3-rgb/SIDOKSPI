
@extends('layouts.app')

@section('title', 'Edit Kategori - SIDOKSPI')
@section('active-menu', 'kategori')
@section('breadcrumb', 'Master Data / Edit Kategori')

@push('styles')
<link rel="stylesheet" href="{{ asset('asset/css/laporan.css') }}">
<style>
    .kategori-form {
        max-width: 760px;
    }

    .kategori-input {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #e1e6f0;
        border-radius: 8px;
        outline: none;
        font-size: 13px;
        color: #263650;
        background: #fff;
    }

    .kategori-input:focus {
        border-color: #0753c5;
        box-shadow: 0 0 0 3px rgb(7 83 197 / 10%);
    }

    .kategori-field {
        margin-bottom: 18px;
    }

    .kategori-field label {
        display: block;
        margin-bottom: 7px;
        color: #34435c;
        font-size: 13px;
        font-weight: 700;
    }

    .kategori-error {
        margin-top: 5px;
        color: #d43d4b;
        font-size: 12px;
    }

    .kategori-form-actions {
        display: flex;
        gap: 9px;
        margin-top: 22px;
    }

    .kategori-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 16px;
        border: 0;
        border-radius: 7px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .kategori-btn-simpan {
        background: #0753c5;
        color: #fff;
    }

    .kategori-btn-kembali {
        background: #edf0f7;
        color: #4f5c72;
    }
</style>
@endpush

@section('content')
<div class="laporan-page">

    <div class="laporan-header">
        <div class="laporan-heading">
            <div class="laporan-breadcrumb">MASTER DATA / KATEGORI</div>
            <h2>Edit Kategori Dokumen</h2>
            <p>Perbarui informasi kategori dokumen SPI.</p>
        </div>
    </div>

    <div class="laporan-panel kategori-form">

        <div class="panel-heading">
            <h3>Form Edit Kategori</h3>
            <p>Ubah informasi kategori sesuai kebutuhan.</p>
        </div>

        <div class="panel-body">

            <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="kategori-field">
                    <label for="kode_kategori">Kode Kategori</label>
                    <input
                        type="text"
                        id="kode_kategori"
                        name="kode_kategori"
                        class="kategori-input"
                        value="{{ old('kode_kategori', $kategori->kode_kategori) }}"
                        required
                    >

                    @error('kode_kategori')
                        <div class="kategori-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="kategori-field">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input
                        type="text"
                        id="nama_kategori"
                        name="nama_kategori"
                        class="kategori-input"
                        value="{{ old('nama_kategori', $kategori->nama_kategori) }}"
                        required
                    >

                    @error('nama_kategori')
                        <div class="kategori-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="kategori-field">
                    <label for="keterangan">Keterangan</label>
                    <textarea
                        id="keterangan"
                        name="keterangan"
                        class="kategori-input"
                        rows="4"
                    >{{ old('keterangan', $kategori->keterangan) }}</textarea>

                    @error('keterangan')
                        <div class="kategori-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="kategori-form-actions">
                    <button type="submit" class="kategori-btn kategori-btn-simpan">
                        Simpan Perubahan
                    </button>

                    <a href="{{ route('kategori.index') }}"
                       class="kategori-btn kategori-btn-kembali">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection
