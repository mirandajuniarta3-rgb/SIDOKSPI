
@extends('layouts.app')

@section('title', 'Tambah SK - SIDOKSPI')
@section('active-menu', 'sk-list')
@section('breadcrumb', 'Surat Keputusan (SK) / Tambah SK')

@section('content')

<style>
    .sk-page {
        color: #24324b;
        font-size: 13px;
    }

    .sk-header, .sk-card, .sk-footer {
        background: #fff;
        border: 1px solid #e8ebf3;
        border-radius: 12px;
    }

    .sk-header {
        padding: 22px;
        margin-bottom: 20px;
    }

    .sk-breadcrumb {
        font-size: 11px;
        color: #8b96aa;
        margin-bottom: 10px;
    }

    .sk-title {
        font-size: 23px;
        font-weight: 750;
        color: #172b4d;
        margin-bottom: 5px;
    }

    .sk-subtitle {
        color: #7c879c;
        font-size: 12px;
        max-width: 650px;
    }

    .sk-card {
        box-shadow: 0 3px 12px rgba(30, 55, 100, .04);
        height: 100%;
    }

    .sk-card-header {
        padding: 17px 18px;
        border-bottom: 1px solid #edf0f6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }

    .sk-card-title {
        font-weight: 750;
        color: #253858;
        font-size: 14px;
        margin: 0;
    }

    .sk-card-description {
        color: #929caf;
        font-size: 10px;
        margin-top: 4px;
    }

    .sk-card-body {
        padding: 20px;
    }

    .sk-label {
        display: block;
        font-size: 12px;
        font-weight: 650;
        color: #344563;
        margin-bottom: 7px;
    }

    .required {
        color: #e05260;
    }

    .sk-input, .sk-select, .sk-textarea {
        width: 100%;
        border: 1px solid #e0e5ef;
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 12px;
        color: #344563;
        background: #fff;
        transition: .2s;
    }

    .sk-input:focus, .sk-select:focus, .sk-textarea:focus {
        outline: none;
        border-color: #2864d7;
        box-shadow: 0 0 0 3px rgba(40, 100, 215, .09);
    }

    .sk-textarea {
        min-height: 105px;
        resize: vertical;
    }

    .sk-form-group {
        margin-bottom: 18px;
    }

    .sk-help, .sk-validation {
        display: block;
        font-size: 10px;
        margin-top: 6px;
    }

    .sk-help {
        color: #929caf;
    }

    .sk-validation {
        color: #d45d6a;
    }

    .sk-info {
        background: #f5f7ff;
        border: 1px solid #e7ebfb;
        padding: 12px;
        border-radius: 9px;
        color: #66749a;
        font-size: 11px;
        line-height: 1.6;
    }

    .sk-badge {
        font-size: 10px;
        padding: 5px 9px;
        border-radius: 20px;
        font-weight: 650;
        background: #e9f8f1;
        color: #16865c;
    }

    .sk-badge-blue {
        background: #edf2ff;
        color: #315fc1;
    }

    .sk-upload {
        border: 2px dashed #cbd5ef;
        border-radius: 12px;
        background: #f7f8ff;
        padding: 25px 15px;
        text-align: center;
        cursor: pointer;
        transition: .2s;
    }

    .sk-upload:hover, .sk-upload.dragover {
        border-color: #2864d7;
        background: #eef3ff;
    }

    .sk-upload-icon {
        width: 53px;
        height: 53px;
        background: #e7edff;
        color: #2864d7;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        margin: 0 auto 12px;
        font-size: 23px;
    }

    .sk-upload-title {
        font-size: 12px;
        font-weight: 700;
        color: #344563;
    }

    .sk-upload-text {
        color: #8b96aa;
        font-size: 10px;
        margin-top: 5px;
    }

    .sk-file-preview {
        display: none;
        border: 1px solid #e4e9f2;
        background: #fff;
        padding: 12px;
        border-radius: 9px;
        margin-top: 12px;
    }

    .sk-file-icon {
        width: 38px;
        height: 38px;
        background: #fff0f0;
        color: #e05260;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-size: 18px;
    }

    .sk-file-name {
        font-size: 11px;
        font-weight: 650;
        color: #344563;
        overflow-wrap: anywhere;
    }

    .sk-file-size {
        font-size: 10px;
        color: #8b96aa;
        margin-top: 3px;
    }

    .sk-footer {
        padding: 15px 18px;
        margin-top: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .sk-btn {
        border: 0;
        padding: 10px 17px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 650;
        transition: .2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        cursor: pointer;
    }

    .sk-btn-primary {
        color: #fff;
        background: #1758cb;
    }

    .sk-btn-primary:hover {
        background: #1046a9;
        color: #fff;
    }

    .sk-btn-secondary {
        background: #f0f2f8;
        color: #56647c;
    }

    .sk-btn-secondary:hover {
        background: #e2e7f2;
        color: #344563;
    }

    .sk-btn:disabled {
        opacity: .65;
        cursor: not-allowed;
    }

    @media(max-width: 767px) {
        .sk-title {
            font-size: 19px;
        }

        .sk-card-body {
            padding: 15px;
        }

        .sk-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .sk-footer .d-flex {
            justify-content: flex-end;
        }
    }
</style>

<div class="container-fluid py-4 sk-page">

    <div class="sk-header">
        <div class="sk-breadcrumb">
            SIDOKSPI &nbsp;›&nbsp;
            Surat Keputusan (SK) &nbsp;›&nbsp;
            <span class="text-primary fw-bold">Tambah SK</span>
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h3 class="sk-title">Registrasi Surat Keputusan (SK) Baru</h3>

                <div class="sk-subtitle">
                    Formulir pencatatan dan pengarsipan naskah penetapan
                    Surat Keputusan Direksi / Dewan Pengawas PDAM Tirta Pinang
                    ke repositori resmi SPI.
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('sk.index') }}" class="sk-btn sk-btn-secondary">
                    <i class="bi bi-arrow-left"></i>
                    Kembali ke Daftar SK
                </a>

                <span class="sk-btn sk-btn-primary">
                    <i class="bi bi-pencil-square"></i>
                    Mode Registrasi SK
                </span>

                <span class="sk-badge d-flex align-items-center">
                    <i class="bi bi-shield-check me-1"></i>
                    Registrasi SK
                </span>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 shadow-sm">
            <strong>
                <i class="bi bi-exclamation-circle me-1"></i>
                Data belum dapat disimpan.
            </strong>

            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('sk.store') }}"
          method="POST"
          enctype="multipart/form-data"
          id="formSK">

        @csrf

        <div class="row g-3">

            <div class="col-lg-7">
                <div class="sk-card">

                    <div class="sk-card-header">
                        <div>
                            <h6 class="sk-card-title">
                                <i class="bi bi-file-earmark-text text-primary me-2"></i>
                                Informasi Pokok & Legalitas SK
                            </h6>

                            <div class="sk-card-description">
                                Bagian 1 dari 2 - Metadata Berkas Penetapan
                            </div>
                        </div>

                        <span class="sk-badge sk-badge-blue">LEGAL-SEC-01</span>
                    </div>

                    <div class="sk-card-body">

                        <div class="sk-form-group">
                            <label class="sk-label">
                                Nomor SK <span class="required">*</span>
                            </label>

                            <input type="text"
                                   name="nomor_sk"
                                   class="sk-input"
                                   placeholder="Contoh: 188.4/012/SK-DIR/2025"
                                   value="{{ old('nomor_sk') }}"
                                   maxlength="100"
                                   required>

                            <small class="sk-validation">
                                <i class="bi bi-info-circle"></i>
                                Nomor SK wajib sesuai format tata naskah dinas hukum PDAM.
                            </small>
                        </div>

                        <div class="sk-form-group">
                            <label class="sk-label">
                                Judul SK <span class="required">*</span>
                            </label>

                            <input type="text"
                                   name="judul"
                                   class="sk-input"
                                   placeholder="Masukkan judul Surat Keputusan"
                                   value="{{ old('judul') }}"
                                   maxlength="255"
                                   minlength="10"
                                   required>

                            <small class="sk-validation">
                                <i class="bi bi-info-circle"></i>
                                Judul SK wajib diisi dengan minimal 10 karakter.
                            </small>
                        </div>

                        <div class="sk-form-group">
                            <div class="d-flex justify-content-between">
                                <label class="sk-label">
                                    Tentang (Substansi Penetapan)
                                    <span class="required">*</span>
                                </label>

                                <small class="text-muted">
                                    <span id="jumlahKarakter">0</span> / 500
                                </small>
                            </div>

                            <textarea name="tentang"
                                      id="tentang"
                                      class="sk-textarea"
                                      minlength="20"
                                      maxlength="500"
                                      placeholder="Jelaskan substansi atau isi pokok Surat Keputusan..."
                                      required>{{ old('tentang') }}</textarea>

                            <small class="sk-help">
                                Tuliskan substansi penetapan secara ringkas dan jelas.
                            </small>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="sk-label">
                                    Tanggal Penetapan <span class="required">*</span>
                                </label>

                                <input type="date"
                                       name="tanggal_penetapan"
                                       id="tanggal_penetapan"
                                       class="sk-input"
                                       value="{{ old('tanggal_penetapan') }}"
                                       required>

                                <small class="sk-validation">
                                    Tanggal penetapan wajib diisi.
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="sk-label">Tahun SK</label>

                                <input type="text"
                                       id="tahun_sk"
                                       class="sk-input"
                                       placeholder="Otomatis dari tanggal"
                                       value="{{ old('tanggal_penetapan') ? date('Y', strtotime(old('tanggal_penetapan'))) : '' }}"
                                       readonly>

                                <small class="sk-help">
                                    Tahun terisi otomatis berdasarkan tanggal penetapan.
                                </small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="sk-form-group mb-0">
                            <label class="sk-label">
                                Status Keberlakuan Hukum <span class="required">*</span>
                            </label>

                            <select name="status" class="sk-select" required>
                                <option value="final"
                                    {{ old('status', 'final') === 'final' ? 'selected' : '' }}>
                                    Berlaku (Ketetapan Aktif & Mengikat Operasional)
                                </option>

                                <option value="draft"
                                    {{ old('status') === 'draft' ? 'selected' : '' }}>
                                    Draft (Belum Berlaku)
                                </option>
                            </select>

                            <small class="sk-validation">
                                Pilih status keberlakuan hukum SK.
                            </small>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="sk-card mb-3">

                    <div class="sk-card-header">
                        <div>
                            <h6 class="sk-card-title">
                                <i class="bi bi-cloud-arrow-up text-primary me-2"></i>
                                Lampiran Naskah Digital SK
                            </h6>

                            <div class="sk-card-description">
                                Bagian 2 dari 2 - Dokumen Elektronik Resmi
                            </div>
                        </div>

                        <span class="badge bg-danger-subtle text-danger">PDF ONLY</span>
                    </div>

                    <div class="sk-card-body">
                        <label class="sk-label">
                            Unggah Berkas Naskah SK Asli
                            <span class="required">*</span>
                        </label>

                        <div class="sk-upload" id="dropArea">
                            <input type="file"
                                   name="file"
                                   id="fileSK"
                                   accept=".pdf,application/pdf"
                                   hidden
                                   required>

                            <div class="sk-upload-icon">
                                <i class="bi bi-cloud-arrow-up"></i>
                            </div>

                            <div class="sk-upload-title">
                                Tarik & Lepas Berkas SK di Sini, atau Jelajahi
                            </div>

                            <div class="sk-upload-text">
                                Dokumen resmi keputusan direkomendasikan
                                dalam bentuk PDF.
                            </div>

                            <div class="mt-3">
                                <span class="badge bg-danger-subtle text-danger">
                                    <i class="bi bi-file-pdf me-1"></i>
                                    Hanya Mendukung Format PDF (Maksimal 10 MB)
                                </span>
                            </div>

                            <button type="button"
                                    class="sk-btn sk-btn-primary mt-3"
                                    id="pilihFile">
                                <i class="bi bi-folder2-open"></i>
                                Pilih File PDF dari Komputer
                            </button>
                        </div>

                        <div class="sk-file-preview" id="filePreview">
                            <div class="d-flex align-items-center gap-3">
                                <div class="sk-file-icon">
                                    <i class="bi bi-file-earmark-pdf"></i>
                                </div>

                                <div class="flex-grow-1">
                                    <div class="sk-file-name" id="fileName"></div>
                                    <div class="sk-file-size" id="fileSize"></div>
                                </div>

                                <button type="button"
                                        class="btn btn-sm btn-light text-danger"
                                        id="hapusFile"
                                        title="Hapus file">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>

                            <div class="mt-2">
                                <span class="sk-badge">
                                    <i class="bi bi-check-circle me-1"></i>
                                    File siap diunggah
                                </span>
                            </div>
                        </div>

                        <div class="sk-info mt-3">
                            <strong>
                                <i class="bi bi-shield-check me-1"></i>
                                Ketentuan Integritas & Keabsahan SK SPI
                            </strong>

                            <p class="mb-0 mt-2">
                                Naskah Surat Keputusan wajib dalam format PDF.
                                Pastikan file yang diunggah merupakan dokumen
                                yang benar dan telah ditandatangani secara resmi.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="sk-info">
                    <strong>
                        <i class="bi bi-info-circle me-1"></i>
                        Informasi Registrasi
                    </strong>

                    <p class="mb-0 mt-2">
                        Data SK yang disimpan akan masuk ke dalam register
                        Surat Keputusan SPI dan dapat ditelusuri melalui
                        halaman Daftar SK.
                    </p>
                </div>
            </div>
        </div>

        <div class="sk-footer">
            <div class="text-muted" style="font-size: 10px;">
                <i class="bi bi-info-circle me-1"></i>
                Tanda bintang menandakan kolom wajib diisi sesuai prosedur pengelolaan SPI.
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('sk.index') }}" class="sk-btn sk-btn-secondary">
                    Batal
                </a>

                <button type="submit" class="sk-btn sk-btn-primary" id="btnSimpan">
                    <i class="bi bi-floppy"></i>
                    Simpan SK
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('fileSK');
    const dropArea = document.getElementById('dropArea');
    const pilihFile = document.getElementById('pilihFile');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');
    const hapusFile = document.getElementById('hapusFile');

    const tanggal = document.getElementById('tanggal_penetapan');
    const tahun = document.getElementById('tahun_sk');

    const tentang = document.getElementById('tentang');
    const jumlahKarakter = document.getElementById('jumlahKarakter');

    function updateTahun() {
        tahun.value = tanggal.value ? tanggal.value.substring(0, 4) : '';
    }

    tanggal.addEventListener('change', updateTahun);
    updateTahun();

    function hitungKarakter() {
        jumlahKarakter.textContent = tentang.value.length;
    }

    tentang.addEventListener('input', hitungKarakter);
    hitungKarakter();

    function formatUkuran(bytes) {
        return (bytes / (1024 * 1024)).toFixed(2) + ' MB';
    }

    function tampilkanFile(file) {
        if (!file) return;

        const namaValid = file.name.toLowerCase().endsWith('.pdf');
        const tipeValid = file.type === 'application/pdf' || file.type === '';

        if (!namaValid || !tipeValid) {
            alert('File harus berformat PDF.');
            fileInput.value = '';
            filePreview.style.display = 'none';
            return;
        }

        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file maksimal 10 MB.');
            fileInput.value = '';
            filePreview.style.display = 'none';
            return;
        }

        fileName.textContent = file.name;
        fileSize.textContent = formatUkuran(file.size);
        filePreview.style.display = 'block';
    }

    pilihFile.addEventListener('click', function (event) {
        event.stopPropagation();
        fileInput.click();
    });

    dropArea.addEventListener('click', function (event) {
        if (event.target.closest('button')) return;
        fileInput.click();
    });

    fileInput.addEventListener('change', function () {
        tampilkanFile(this.files[0]);
    });

    dropArea.addEventListener('dragover', function (event) {
        event.preventDefault();
        dropArea.classList.add('dragover');
    });

    dropArea.addEventListener('dragleave', function () {
        dropArea.classList.remove('dragover');
    });

    dropArea.addEventListener('drop', function (event) {
        event.preventDefault();
        dropArea.classList.remove('dragover');

        const files = event.dataTransfer.files;

        if (files.length > 0) {
            fileInput.files = files;
            tampilkanFile(files[0]);
        }
    });

    hapusFile.addEventListener('click', function () {
        fileInput.value = '';
        filePreview.style.display = 'none';
    });
});
</script>

@endsection
