@extends('layouts.app')

@section('title', 'Edit Kategori - SIDOKSPI')
@section('active-menu', 'kategori')
@section('breadcrumb', 'Master Data / Edit Kategori')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kategori.css') }}">
@include('partials.sidokspi-feedback')
@endpush

@section('content')
<div class="kategori-page kategori-form-page">
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
            <p>Lengkapi informasi kategori dokumen berikut.</p>
        </div>
        <div class="panel-body">
            <form action="{{ route('kategori.update', $kategori->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="kategori-field">
                    <label for="kode_kategori">Kode Kategori</label>
                    <input type="text" id="kode_kategori" name="kode_kategori" class="kategori-input" maxlength="10" value="{{ old('kode_kategori', $kategori->kode_kategori) }}" required>
                    @error('kode_kategori')
                        <div class="kategori-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="kategori-field">
                    <label for="nama_kategori">Nama Kategori</label>
                    <input type="text" id="nama_kategori" name="nama_kategori" class="kategori-input" maxlength="100" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                    @error('nama_kategori')
                        <div class="kategori-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="kategori-field">
                    <label for="keterangan">Keterangan</label>
                    <textarea id="keterangan" name="keterangan" class="kategori-input" maxlength="255" rows="4">{{ old('keterangan', $kategori->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="kategori-error">{{ $message }}</div>
                    @enderror
                </div>
                <div class="kategori-field">
                    <label for="status">Status Kategori</label>
                    <select id="status" name="status" class="kategori-input" required>
                        <option value="aktif" @selected(old('status', $kategori->status) === 'aktif')>Aktif</option>
                        <option value="nonaktif" @selected(old('status', $kategori->status) === 'nonaktif')>Nonaktif</option>
                    </select>
                    @error('status')
                        <div class="kategori-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="kategori-form-actions">
                    <a href="{{ route('kategori.index') }}" class="kategori-btn kategori-btn-cancel">Kembali</a>
                    <button type="submit" class="kategori-btn kategori-btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
