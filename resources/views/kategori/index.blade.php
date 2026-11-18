@extends('layouts.app')

@section('title', 'Kategori Dokumen - SIDOKSPI')
@section('active-menu', 'kategori')
@section('breadcrumb', 'Master Data / Kategori Dokumen')

@push('styles')
<link rel="stylesheet" href="{{ asset('asset/css/laporan.css') }}">

<style>
    .kategori-page {
        padding-bottom: 30px;
    }

    .kategori-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .kategori-btn {
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

    .kategori-btn-primary {
        background: #0753c5;
        color: white;
    }

    .kategori-btn-primary:hover {
        background: #0644a1;
        color: white;
    }

    .kategori-search {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }

    .kategori-search input {
        width: 260px;
        max-width: 100%;
        padding: 10px 13px;
        border: 1px solid #dce3ef;
        border-radius: 8px;
        outline: none;
    }

    .kategori-search input:focus,
    .kategori-form input:focus,
    .kategori-form textarea:focus,
    .kategori-form select:focus {
        border-color: #0753c5;
        box-shadow: 0 0 0 3px rgba(7, 83, 197, .1);
    }

    .kategori-code {
        display: inline-block;
        padding: 5px 9px;
        border-radius: 6px;
        background: #edf2ff;
        color: #0753c5;
        font-size: 11px;
        font-weight: 750;
    }

    .kategori-status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }

    .status-aktif {
        color: #16834a;
        background: #e7f8ee;
    }

    .status-nonaktif {
        color: #c03945;
        background: #fff0f0;
    }

    .kategori-actions {
        display: flex;
        gap: 7px;
        align-items: center;
    }

    .kategori-action {
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

    .kategori-alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        font-size: 13px;
    }

    .kategori-alert-success {
        background: #e7f8ee;
        color: #16834a;
    }

    .kategori-alert-error {
        background: #fff0f0;
        color: #c03945;
    }

    /* MODAL */
    .kategori-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(15, 30, 55, .5);
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .kategori-modal.show {
        display: flex;
    }

    .kategori-modal-content {
        width: 100%;
        max-width: 550px;
        max-height: 90vh;
        overflow-y: auto;
        background: white;
        border-radius: 13px;
        box-shadow: 0 15px 45px rgba(0,0,0,.15);
    }

    .kategori-modal-header {
        padding: 20px 24px;
        border-bottom: 1px solid #edf0f5;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .kategori-modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 750;
        color: #18345b;
    }

    .kategori-modal-close {
        border: none;
        background: transparent;
        font-size: 25px;
        cursor: pointer;
        color: #718096;
    }

    .kategori-modal-body {
        padding: 22px 24px;
    }

    .kategori-form-group {
        margin-bottom: 17px;
    }

    .kategori-form-group label {
        display: block;
        margin-bottom: 7px;
        font-size: 13px;
        font-weight: 700;
        color: #34445d;
    }

    .kategori-form input,
    .kategori-form textarea,
    .kategori-form select {
        width: 100%;
        padding: 11px 13px;
        border: 1px solid #dce3ef;
        border-radius: 8px;
        font-size: 13px;
        outline: none;
    }

    .kategori-form textarea {
        min-height: 90px;
        resize: vertical;
    }

    .kategori-form-footer {
        display: flex;
        justify-content: flex-end;
        gap: 9px;
        padding-top: 10px;
    }

    .kategori-btn-cancel {
        background: #edf0f5;
        color: #44546a;
    }

    .kategori-empty {
        text-align: center;
        padding: 30px;
        color: #8792a3;
    }

    @media(max-width: 650px) {
        .kategori-search,
        .kategori-search input {
            width: 100%;
        }

        .kategori-toolbar {
            align-items: stretch;
        }
    }
</style>
@endpush

@section('content')

<div class="laporan-page kategori-page">

    {{-- HEADER --}}
    <div class="laporan-header">
        <div class="laporan-heading">
            <div class="laporan-breadcrumb">
                MASTER DATA / KATEGORI DOKUMEN
            </div>

            <h2>Master Data Kategori Dokumen</h2>

            <p>
                Kelola kategori untuk pengelompokan dokumen SPI.
            </p>
        </div>

        <button type="button"
                class="kategori-btn kategori-btn-primary"
                onclick="bukaModalKategori()">
            + Tambah Kategori
        </button>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="kategori-alert kategori-alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="kategori-alert kategori-alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="kategori-alert kategori-alert-error">
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
            <h3>Daftar Kategori Dokumen</h3>
            <p>
                Daftar kategori dokumen yang tersedia dalam sistem.
            </p>
        </div>

        {{-- PENCARIAN --}}
        <div class="kategori-toolbar" style="padding: 0 20px;">

            <form action="{{ route('kategori.index') }}"
                  method="GET"
                  class="kategori-search">

                <input type="text"
                       name="search"
                       placeholder="Cari kode atau nama kategori..."
                       value="{{ $search ?? '' }}">

                <button type="submit"
                        class="kategori-btn kategori-btn-primary">
                    Cari
                </button>

                @if(!empty($search))
                    <a href="{{ route('kategori.index') }}"
                       class="kategori-btn kategori-btn-cancel">
                        Reset
                    </a>
                @endif

            </form>

            <div style="font-size:12px;color:#718096;">
                Total {{ $kategori->total() }} kategori
            </div>

        </div>

        {{-- TABEL --}}
        <div class="table-wrapper">

            <table class="laporan-table">

                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Kode Kategori</th>
                        <th>Nama Kategori</th>
                        <th>Keterangan & Lingkup Naskah</th>
                        <th>Jumlah Berkas</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($kategori as $index => $item)

                        <tr>

                            <td>
                                {{ $kategori->firstItem() + $index }}
                            </td>

                            <td>
                                <span class="kategori-code">
                                    {{ $item->kode_kategori }}
                                </span>
                            </td>

                            <td class="table-primary-text">
                                {{ $item->nama_kategori }}
                            </td>

                            <td>
                                {{ $item->keterangan ?: '-' }}
                            </td>

                            <td>
                                {{ $item->dokumen_count }} berkas
                            </td>

                            <td>
                                @if($item->status === 'aktif')
                                    <span class="kategori-status status-aktif">
                                        Aktif
                                    </span>
                                @else
                                    <span class="kategori-status status-nonaktif">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td>
                                <div class="kategori-actions">

                                    <a href="{{ route('kategori.edit', $item->id) }}"
                                       class="kategori-action action-edit">
                                        Edit
                                    </a>

                                    <form action="{{ route('kategori.destroy', $item->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="kategori-action action-delete">
                                            Hapus
                                        </button>

                                    </form>

                                </div>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="kategori-empty">
                                Belum ada kategori dokumen.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- FOOTER TABEL --}}
        <div class="table-footer">

            <div class="table-info">
                Menampilkan {{ $kategori->firstItem() ?? 0 }}
                sampai {{ $kategori->lastItem() ?? 0 }}
                dari {{ $kategori->total() }} kategori
            </div>

            <div>
                {{ $kategori->links() }}
            </div>

        </div>

    </div>

</div>

{{-- MODAL TAMBAH KATEGORI --}}
<div class="kategori-modal" id="modalKategori">

    <div class="kategori-modal-content">

        <div class="kategori-modal-header">

            <h3>Tambah Kategori Baru</h3>

            <button type="button"
                    class="kategori-modal-close"
                    onclick="tutupModalKategori()">
                &times;
            </button>

        </div>

        <div class="kategori-modal-body">

            <form action="{{ route('kategori.store') }}"
                  method="POST"
                  class="kategori-form">

                @csrf

                <div class="kategori-form-group">

                    <label>Kode Kategori</label>

                    <input type="text"
                           name="kode_kategori"
                           maxlength="10"
                           placeholder="Contoh: KAT001"
                           value="{{ old('kode_kategori') }}"
                           required>

                </div>

                <div class="kategori-form-group">

                    <label>Nama Kategori</label>

                    <input type="text"
                           name="nama_kategori"
                           maxlength="100"
                           placeholder="Masukkan nama kategori"
                           value="{{ old('nama_kategori') }}"
                           required>

                </div>

                <div class="kategori-form-group">

                    <label>Keterangan & Lingkup Naskah</label>

                    <textarea name="keterangan"
                              maxlength="255"
                              placeholder="Masukkan keterangan kategori">{{ old('keterangan') }}</textarea>

                </div>

                <div class="kategori-form-group">

                    <label>Status Keterbukaan Taksonomi</label>

                    <select name="status" required>

                        <option value="aktif"
                            {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>
                            Aktif Digunakan
                        </option>

                        <option value="nonaktif"
                            {{ old('status') === 'nonaktif' ? 'selected' : '' }}>
                            Nonaktif
                        </option>

                    </select>

                </div>

                <div class="kategori-form-footer">

                    <button type="button"
                            class="kategori-btn kategori-btn-cancel"
                            onclick="tutupModalKategori()">
                        Batal
                    </button>

                    <button type="submit"
                            class="kategori-btn kategori-btn-primary">
                        Simpan Kategori
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
    function bukaModalKategori() {
        document.getElementById('modalKategori').classList.add('show');
    }

    function tutupModalKategori() {
        document.getElementById('modalKategori').classList.remove('show');
    }

    document.addEventListener('click', function(event) {
        const modal = document.getElementById('modalKategori');

        if (event.target === modal) {
            tutupModalKategori();
        }
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            tutupModalKategori();
        }
    });

    @if($errors->any())
        bukaModalKategori();
    @endif
</script>
@endpush
