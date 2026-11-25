@extends('layouts.app')
@section('active-menu', 'pengguna')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/pengguna.css') }}">
@include('partials.sidokspi-feedback')
@endpush
@section('title', 'Manajemen Pengguna - SIDOKSPI')
@section('breadcrumb', 'Master Data / Manajemen Pengguna')
@section('content')
<div class="pengguna-page">
    <header class="pengguna-header">
        <div><div class="pengguna-eyebrow">MASTER DATA / MANAJEMEN PENGGUNA</div><h2>Manajemen Pengguna</h2><p>Kelola data akun pengguna yang memiliki akses ke sistem SIDOKSPI.</p></div>
        <a class="pengguna-btn pengguna-btn-primary" href="{{ route('pengguna.create') }}">+ Tambah Pengguna</a>
    </header>

    <section class="pengguna-panel">
        <div class="pengguna-panel-heading"><h3>Daftar Pengguna</h3><p>Daftar akun yang terdaftar dalam sistem SIDOKSPI.</p></div>
        <div class="pengguna-toolbar">
            <form id="pengguna-search" action="{{ route('pengguna.index') }}" method="GET">
                <input type="search" name="search" aria-label="Cari pengguna" placeholder="Cari nama, NIP, atau email..." value="{{ request('search') }}">
                <input type="hidden" name="sort" value="{{ $sort }}">
                <input type="hidden" name="direction" value="{{ $direction }}">
                @foreach(['role', 'status'] as $filter)
                    @if(request()->filled($filter))<input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">@endif
                @endforeach
            </form>
            <span id="pengguna-total">Total {{ $pengguna->total() }} pengguna</span>
        </div>
        <p id="pengguna-message" role="status" aria-live="polite"></p>
        <div id="pengguna-results">
            <div class="pengguna-table-wrap">
                <table class="pengguna-table">
                    <thead><tr><th scope="col">No.</th>
                        @foreach(['nama_lengkap'=>'Nama Lengkap','nip'=>'NIP','email'=>'Email','role'=>'Role','bidang'=>'Bidang','status'=>'Status'] as $key => $label)
                            @php($active = $sort === $key)
                            <th scope="col" aria-sort="{{ $active ? ($direction === 'asc' ? 'ascending' : 'descending') : 'none' }}">
                                <a class="pengguna-sort {{ $active ? 'is-active' : '' }}" href="{{ route('pengguna.index', array_merge(request()->except('page'), ['sort'=>$key,'direction'=>$active && $direction === 'asc' ? 'desc' : 'asc'])) }}">{{ $label }} <span aria-hidden="true">{{ $active ? ($direction === 'asc' ? '↑' : '↓') : '↕' }}</span></a>
                            </th>
                        @endforeach
                        <th scope="col">Aksi</th></tr></thead>
                    <tbody>
                    @forelse($pengguna as $index => $item)
                        <tr><td>{{ $pengguna->firstItem() + $index }}</td><td class="pengguna-name">{{ $item->nama_lengkap }}</td><td>{{ $item->nip ?: '-' }}</td><td>{{ $item->email }}</td><td><span class="pengguna-role">{{ strtoupper(str_replace('_', ' ', $item->role)) }}</span></td><td>{{ $item->bidang ?: '-' }}</td><td><span class="pengguna-status {{ $item->status === 'aktif' ? 'status-aktif' : 'status-nonaktif' }}">{{ $item->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}</span></td>
                        <td><div class="pengguna-actions">
                            <a href="{{ route('pengguna.edit', $item->id) }}" class="pengguna-action action-edit" title="Edit pengguna" aria-label="Edit {{ $item->nama_lengkap }}"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 3 5 5M4 20l4-1L21 6l-5-5L3 14l-1 7z"/></svg></a>
                            <form action="{{ route('pengguna.destroy', $item->id) }}" method="POST" data-confirm-delete>
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="pengguna-action action-delete" title="Hapus pengguna" aria-label="Hapus {{ $item->nama_lengkap }}" @disabled(auth()->id() === $item->id)><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 6h18M9 6V3h6v3M5 6l1 15h12l1-15M10 10v7M14 10v7"/></svg></button>
                            </form>
                        </div></td></tr>
                    @empty
                        <tr><td colspan="8" class="pengguna-empty">Tidak ada pengguna yang ditemukan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="pengguna-footer"><span>Menampilkan {{ $pengguna->firstItem() ?? 0 }} sampai {{ $pengguna->lastItem() ?? 0 }} dari {{ $pengguna->total() }} pengguna</span><div class="pengguna-pagination">{{ $pengguna->links() }}</div></div>
        </div>
    </section>
</div>
<script src="{{ asset('js/pengguna.js') }}" defer></script>
@endsection
