@extends('layouts.app')

@section('title', 'Kategori Dokumen - SIDOKSPI')
@section('active-menu', 'kategori')
@section('breadcrumb', 'Master Data / Kategori Dokumen')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kategori.css') }}">
@include('partials.sidokspi-feedback')
@endpush

@section('content')

<div class="kategori-page">

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

    <div class="laporan-panel">

        <div class="panel-heading">
            <h3>Daftar Kategori Dokumen</h3>
            <p>
                Klik kolom untuk mengubah data. Enter atau klik di luar untuk menyimpan, Esc untuk membatalkan.
            </p>
        </div>

        <div class="kategori-toolbar">

            <form action="{{ route('kategori.index') }}"
                  method="GET"
                  class="kategori-search" id="kategoriSearchForm">

                <input type="text"
                       name="search" id="kategoriSearchInput" aria-label="Cari kategori" autocomplete="off"
                       placeholder="Cari kode atau nama kategori..."
                       value="{{ $search ?? '' }}">

            </form>

            <span id="kategoriSearchStatus" role="status" aria-live="polite"></span>
            <div class="kategori-total" id="kategoriTotal">
                Total {{ $kategori->total() }} kategori
            </div>

        </div>

        <div id="kategoriSaveStatus" role="status" aria-live="polite"></div>
        <div id="kategoriResults" aria-busy="false">
        <div class="table-wrapper">

            <table class="laporan-table">

                <thead>
                    <tr>
                        <th>No.</th>
                        <th aria-sort="{{ ($sort ?? 'created_at') === 'kode_kategori' ? (($direction ?? 'desc') === 'asc' ? 'ascending' : 'descending') : 'none' }}">
                            <a class="kategori-sort" href="{{ route('kategori.index', array_merge(request()->except('page'), ['sort' => 'kode_kategori', 'direction' => ($sort ?? 'created_at') === 'kode_kategori' && ($direction ?? 'desc') === 'asc' ? 'desc' : 'asc'])) }}">
                                <span>Kode Kategori</span>
                                <span class="kategori-sort-icon" aria-hidden="true">{{ ($sort ?? 'created_at') === 'kode_kategori' ? (($direction ?? 'desc') === 'asc' ? '↑' : '↓') : '↕' }}</span>
                            </a>
                        </th>
                        <th aria-sort="{{ ($sort ?? 'created_at') === 'nama_kategori' ? (($direction ?? 'desc') === 'asc' ? 'ascending' : 'descending') : 'none' }}">
                            <a class="kategori-sort" href="{{ route('kategori.index', array_merge(request()->except('page'), ['sort' => 'nama_kategori', 'direction' => ($sort ?? 'created_at') === 'nama_kategori' && ($direction ?? 'desc') === 'asc' ? 'desc' : 'asc'])) }}">
                                <span>Nama Kategori</span>
                                <span class="kategori-sort-icon" aria-hidden="true">{{ ($sort ?? 'created_at') === 'nama_kategori' ? (($direction ?? 'desc') === 'asc' ? '↑' : '↓') : '↕' }}</span>
                            </a>
                        </th>
                        <th aria-sort="{{ ($sort ?? 'created_at') === 'keterangan' ? (($direction ?? 'desc') === 'asc' ? 'ascending' : 'descending') : 'none' }}">
                            <a class="kategori-sort" href="{{ route('kategori.index', array_merge(request()->except('page'), ['sort' => 'keterangan', 'direction' => ($sort ?? 'created_at') === 'keterangan' && ($direction ?? 'desc') === 'asc' ? 'desc' : 'asc'])) }}">
                                <span>Keterangan &amp; Lingkup Naskah</span>
                                <span class="kategori-sort-icon" aria-hidden="true">{{ ($sort ?? 'created_at') === 'keterangan' ? (($direction ?? 'desc') === 'asc' ? '↑' : '↓') : '↕' }}</span>
                            </a>
                        </th>
                        <th aria-sort="{{ ($sort ?? 'created_at') === 'dokumen_count' ? (($direction ?? 'desc') === 'asc' ? 'ascending' : 'descending') : 'none' }}">
                            <a class="kategori-sort" href="{{ route('kategori.index', array_merge(request()->except('page'), ['sort' => 'dokumen_count', 'direction' => ($sort ?? 'created_at') === 'dokumen_count' && ($direction ?? 'desc') === 'asc' ? 'desc' : 'asc'])) }}">
                                <span>Jumlah Berkas</span>
                                <span class="kategori-sort-icon" aria-hidden="true">{{ ($sort ?? 'created_at') === 'dokumen_count' ? (($direction ?? 'desc') === 'asc' ? '↑' : '↓') : '↕' }}</span>
                            </a>
                        </th>
                        <th aria-sort="{{ ($sort ?? 'created_at') === 'status' ? (($direction ?? 'desc') === 'asc' ? 'ascending' : 'descending') : 'none' }}">
                            <a class="kategori-sort" href="{{ route('kategori.index', array_merge(request()->except('page'), ['sort' => 'status', 'direction' => ($sort ?? 'created_at') === 'status' && ($direction ?? 'desc') === 'asc' ? 'desc' : 'asc'])) }}">
                                <span>Status</span>
                                <span class="kategori-sort-icon" aria-hidden="true">{{ ($sort ?? 'created_at') === 'status' ? (($direction ?? 'desc') === 'asc' ? '↑' : '↓') : '↕' }}</span>
                            </a>
                        </th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($kategori as $index => $item)

                        <tr data-update-url="{{ route('kategori.update', $item->id) }}">
                            <td>{{ $kategori->firstItem() + $index }}</td>
                            <td><input class="kategori-inline kategori-inline-code" name="kode_kategori" value="{{ $item->kode_kategori }}" maxlength="10" required aria-label="Kode kategori"></td>
                            <td><input class="kategori-inline" name="nama_kategori" value="{{ $item->nama_kategori }}" maxlength="100" required aria-label="Nama kategori"></td>
                            <td><input class="kategori-inline" name="keterangan" value="{{ $item->keterangan }}" maxlength="255" placeholder="-" aria-label="Keterangan"></td>
                            <td>{{ $item->dokumen_count }} berkas</td>
                            <td>
                                <select class="kategori-inline kategori-inline-status" data-status="{{ $item->status }}" name="status" aria-label="Status kategori">
                                    <option value="aktif" @selected($item->status === 'aktif')>Aktif</option>
                                    <option value="nonaktif" @selected($item->status === 'nonaktif')>Nonaktif</option>
                                </select>
                            </td>
                            <td>
                                <form action="{{ route('kategori.destroy', $item->id) }}" method="POST" data-confirm-delete>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="kategori-action action-delete" title="Hapus kategori" aria-label="Hapus kategori {{ $item->nama_kategori }}">
                                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M8 6V4h8v2M19 6l-1 14H6L5 6M10 10v6M14 10v6"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty

                        <tr>
                            <td colspan="7" class="kategori-empty">
                                {{ filled($search) ? "Kategori tidak ditemukan." : "Belum ada kategori dokumen." }}
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        <div class="table-footer">

            <div class="table-info">
                Menampilkan {{ $kategori->firstItem() ?? 0 }}
                sampai {{ $kategori->lastItem() ?? 0 }}
                dari {{ $kategori->total() }} kategori
            </div>

            <div>
                {{ $kategori->withQueryString()->links() }}
            </div>

        </div>

        </div>
    </div>

</div>

<div class="kategori-modal" id="modalKategori" role="dialog" aria-modal="true" aria-labelledby="kategori-modal-title">

    <div class="kategori-modal-content">

        <div class="kategori-modal-header">

            <h3 id="kategori-modal-title">Tambah Kategori Baru</h3>

            <button type="button"
                    class="kategori-modal-close" aria-label="Tutup modal"
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

                    <label for="modal_kode_kategori">Kode Kategori</label>

                    <input type="text"
                           id="modal_kode_kategori" name="kode_kategori"
                           maxlength="10"
                           placeholder="Contoh: KAT001"
                           value="{{ old('kode_kategori') }}"
                           required>

                @error('kode_kategori')
                        <div class="kategori-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="kategori-form-group">

                    <label for="modal_nama_kategori">Nama Kategori</label>

                    <input type="text"
                           id="modal_nama_kategori" name="nama_kategori"
                           maxlength="100"
                           placeholder="Masukkan nama kategori"
                           value="{{ old('nama_kategori') }}"
                           required>

                @error('nama_kategori')
                        <div class="kategori-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="kategori-form-group">

                    <label for="modal_keterangan">Keterangan & Lingkup Naskah</label>

                    <textarea id="modal_keterangan" name="keterangan"
                              maxlength="255"
                              placeholder="Masukkan keterangan kategori">{{ old('keterangan') }}</textarea>

                @error('keterangan')
                        <div class="kategori-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="kategori-form-group">

                    <label for="modal_status">Status Kategori</label>

                    <select id="modal_status" name="status" required>

                        <option value="aktif"
                            {{ old('status', 'aktif') === 'aktif' ? 'selected' : '' }}>
                            Aktif Digunakan
                        </option>

                        <option value="nonaktif"
                            {{ old('status') === 'nonaktif' ? 'selected' : '' }}>
                            Nonaktif
                        </option>

                    </select>

                @error('status')
                        <div class="kategori-error">{{ $message }}</div>
                    @enderror
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
        document.getElementById('modalKategori').querySelector('input[name=kode_kategori]').focus();
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
<script src="{{ asset('js/kategori.js') }}?v=4" defer></script>
@endpush
