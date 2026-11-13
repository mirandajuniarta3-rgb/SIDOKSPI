@extends('layouts.app')

@section('title', 'Unggah Dokumen - SIDOKSPI')
@section('active-menu', 'document-create')
@section('breadcrumb', 'Dokumen / Unggah Dokumen')
@section('search-action', route('dokumen.index'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('asset/css/dokumen-create.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('asset/js/document-upload.js') }}" defer></script>
@endpush

@section('content')
    <section class="page-heading upload-heading" aria-labelledby="page-title">
        <div>
            <div class="upload-context">
                <span class="eyebrow">Portal SPI <span aria-hidden="true">›</span> Beranda Administrasi</span>
                <span class="form-code">FORM-SPI-2025/REG-DOC</span>
            </div>
            <h1 id="page-title">Unggah Dokumen Pengawasan <span class="verified-mark" aria-label="Formulir resmi">✓</span>
            </h1>
            <p class="page-description">Tambahkan berkas audit baru, laporan kepatuhan, nota pemeriksaan, atau kertas kerja
                ke repositori privat SPI PDAM Tirta Pinang.</p>
        </div>
        <div class="secure-channel">
            <span class="secure-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="4" y="10" width="16" height="11" rx="2" />
                    <path d="M8 10V7a4 4 0 1 1 8 0v3m-4 4v3" />
                </svg></span>
            <span><strong>KANAL AUDIT INTERNAL</strong><small>Penyimpanan privat</small></span>
        </div>
    </section>

    @if ($errors->any())
        <div class="form-errors" role="alert">
            <strong>Dokumen belum dapat disimpan.</strong>
            <span>Periksa kembali kolom yang ditandai dan coba lagi.</span>
        </div>
    @endif

    <form id="document-upload-form" class="upload-form" method="POST" action="{{ route('dokumen.store') }}"
        enctype="multipart/form-data">
        @csrf
        <div class="upload-grid">
            <section class="upload-card metadata-card" aria-labelledby="metadata-title">
                <div class="card-heading">
                    <div class="card-title">
                        <span class="heading-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M6 3h9l4 4v14H6z" />
                                <path d="M14 3v5h5M9 13h7m-7 4h7" />
                            </svg></span>
                        <h2 id="metadata-title">Metadata &amp; Identitas Dokumen</h2>
                    </div>
                    <span class="required-note">Kolom bertanda <b>*</b> wajib diisi</span>
                </div>

                <div class="field">
                    <label for="nomor_dokumen">Nomor Dokumen <span class="required">*</span></label>
                    <input id="nomor_dokumen" name="nomor_dokumen" type="text" value="{{ old('nomor_dokumen') }}"
                        maxlength="100" placeholder="DOK/SPI/2026/001" required
                        aria-describedby="nomor-help @error('nomor_dokumen') nomor-error @enderror">
                    <small id="nomor-help" class="field-help">Contoh: DOK/SPI/2024/11-043 atau LHA-08/DIR/PDAM/2024</small>
                    @error('nomor_dokumen')
                        <small id="nomor-error" class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="field">
                    <label for="judul">Judul Dokumen <span class="required">*</span></label>
                    <input id="judul" name="judul" type="text" value="{{ old('judul') }}" maxlength="255"
                        placeholder="Contoh: Laporan Hasil Audit Kualitas Air" required>
                    @error('judul')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="field-grid">
                    <div class="field">
                        <label for="kategori_id">Kategori Dokumen <span class="required">*</span></label>
                        <select id="kategori_id" name="kategori_id" required>
                            <option value="">Pilih kategori</option>
                            @foreach ($kategori as $item)
                                <option value="{{ $item->id }}" @selected((string) old('kategori_id') === (string) $item->id)>{{ $item->kode_kategori }}
                                    ({{ $item->nama_kategori }})</option>
                            @endforeach
                        </select>
                        @if ($kategori->isEmpty())
                            <small class="field-error">Kategori belum tersedia. Tambahkan kategori sebelum mengunggah
                                dokumen.</small>
                        @endif
                        @error('kategori_id')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="field">
                        <label for="tahun">Tahun Anggaran <span class="required">*</span></label>
                        <select id="tahun" name="tahun" required>
                            @foreach ($tahunAnggaran as $year)
                                <option value="{{ $year }}" @selected((string) old('tahun', now()->year) === (string) $year)>
                                    {{ $year }}{{ $year === now()->year ? ' (Tahun Berjalan)' : '' }}</option>
                            @endforeach
                        </select>
                        @error('tahun')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="field date-field">
                    <label for="tanggal_dokumen">Tanggal Dokumen / Pengesahan <span class="required">*</span></label>
                    <input id="tanggal_dokumen" name="tanggal_dokumen" type="date"
                        value="{{ old('tanggal_dokumen', now()->toDateString()) }}" required>
                    @error('tanggal_dokumen')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="field">
                    <div class="label-row">
                        <label for="deskripsi">Deskripsi &amp; Ringkasan Berkas</label>
                        <span class="optional-note">Opsional tetapi disarankan</span>
                    </div>
                    <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="10000"
                        placeholder="Tuliskan ringkasan isi dokumen, ruang lingkup pemeriksaan, dan catatan penting lainnya...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

            </section>

            <div class="upload-sidebar">
                <section class="upload-card attachment-card" aria-labelledby="attachment-title">
                    <div class="card-heading">
                        <div class="card-title">
                            <span class="heading-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="1.8">
                                    <path d="m8 12 6.5-6.5a4 4 0 0 1 5.7 5.7l-8.5 8.5a6 6 0 0 1-8.5-8.5l8.5-8.5" />
                                </svg></span>
                            <h2 id="attachment-title">Berkas Lampiran Dokumen</h2>
                        </div>
                        <span class="ready-label"><span></span>Siap</span>
                    </div>

                    <label id="file-dropzone" class="file-dropzone" for="document-file">
                        <input id="document-file" class="file-input" type="file" name="file"
                            accept=".pdf,.docx,.xlsx,application/pdf,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                            required>
                        <span class="cloud-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M7 18a5 5 0 0 1-.5-10A6 6 0 0 1 18 9a4.5 4.5 0 0 1-.5 9H7Z" />
                                <path d="M12 16V10m-3 3 3-3 3 3" />
                            </svg></span>
                        <strong>Tarik &amp; lepas berkas di sini</strong>
                        <span>atau <u>klik untuk memilih berkas</u></span>
                        <small>Format PDF, DOCX, XLSX · Maksimal 20 MB</small>
                    </label>
                    @error('file')
                        <small class="field-error file-error">{{ $message }}</small>
                    @enderror

                    <div id="selected-file" class="selected-file" hidden>
                        <span class="file-type-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="M6 3h8l5 5v13H6z" />
                                <path d="M14 3v5h5M9 14h7m-7 3h5" />
                            </svg></span>
                        <span class="selected-file-info"><strong id="selected-file-name"></strong><small
                                id="selected-file-size"></small></span>
                        <button id="remove-selected-file" class="remove-file" type="button"
                            aria-label="Hapus pilihan berkas">×</button>
                    </div>
                </section>

                <section class="protection-card" aria-labelledby="protection-title">
                    <div class="protection-heading">
                        <span class="heading-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8">
                                <path d="m12 3 8 3v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3Z" />
                                <path d="m9 12 2 2 4-4" />
                            </svg></span>
                        <strong id="protection-title">Perlindungan Dokumen</strong>
                    </div>
                    <p>Berkas disimpan pada penyimpanan privat dan metadata unggahan dicatat untuk keperluan audit internal.
                    </p>
                    <span class="protection-tag"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8">
                            <rect x="4" y="10" width="16" height="11" rx="2" />
                            <path d="M8 10V7a4 4 0 1 1 8 0v3" />
                        </svg>Akses tidak publik</span>
                </section>

                <section class="process-card" aria-labelledby="process-title">
                    <h2 id="process-title">Alur Pemrosesan Dokumen</h2>
                    <ol class="process-steps">
                        <li><span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M12 16V4m-5 5 5-5 5 5M4 16v4h16v-4" />
                                </svg></span><small>1. Unggah</small></li>
                        <li><span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="m12 3 8 3v5c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-3Z" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg></span><small>2. Catat Metadata</small></li>
                        <li><span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="4" y="4" width="16" height="16" rx="2" />
                                    <path d="M8 8h8m-8 4h8m-8 4h5" />
                                </svg></span><small>3. Simpan Draft</small></li>
                    </ol>
                </section>
            </div>
        </div>

        <footer class="upload-footer">
            <p><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path
                        d="M4 17.5A3.5 3.5 0 0 1 7.5 14H8a5 5 0 1 1 9.8 1.5A3.5 3.5 0 0 1 17 22H7.5A3.5 3.5 0 0 1 4 17.5Z" />
                    <path d="m9 17 2 2 4-4" />
                </svg>Draft dokumen akan disimpan setelah Anda memilih <strong>Simpan Dokumen</strong>.</p>
            <div class="footer-actions">
                <a class="button cancel-button" href="{{ route('dokumen.index') }}">Batal</a>
                <button class="button primary save-button" type="submit" @disabled($kategori->isEmpty())>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="9" />
                        <path d="m8 12 2.5 2.5L16 9" />
                    </svg>Simpan Dokumen
                </button>
            </div>
        </footer>
    </form>
@endsection
