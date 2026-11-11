
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIDOKSPI</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f6fc;
            font-family: Arial, sans-serif;
            color: #26334a;
        }

        .sidebar {
            width: 245px;
            min-height: 100vh;
            background: white;
            position: fixed;
            left: 0;
            top: 0;
            padding: 25px 15px;
            border-right: 1px solid #eee;
        }

        .logo {
            color: #2456a6;
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .menu-title {
            color: #999;
            font-size: 11px;
            font-weight: bold;
            margin: 20px 10px 10px;
        }

        .sidebar a {
            display: block;
            color: #596579;
            text-decoration: none;
            padding: 12px;
            margin-bottom: 5px;
            border-radius: 8px;
            font-size: 14px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #eaf1ff;
            color: #2456a6;
        }

        .content {
            margin-left: 245px;
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border: none;
            border-radius: 13px;
            padding: 22px;
            height: 100%;
            box-shadow: 0 3px 12px rgba(0,0,0,.04);
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            border-radius: 10px;
            background: #eaf1ff;
            color: #2456a6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin-top: 15px;
        }

        .panel {
            background: white;
            border-radius: 13px;
            padding: 22px;
            margin-top: 22px;
            box-shadow: 0 3px 12px rgba(0,0,0,.04);
        }

        .table th {
            color: #7c8798;
            font-size: 12px;
            white-space: nowrap;
        }

        .table td {
            font-size: 13px;
            vertical-align: middle;
        }

        @media(max-width: 768px) {
            .sidebar {
                position: relative;
                width: 100%;
                min-height: auto;
            }

            .content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
</head>

<body>

@php
    $user = auth()->user();
@endphp

<!-- SIDEBAR -->
<div class="sidebar">

    <div class="logo">
        <i class="bi bi-folder2-open"></i> SIDOKSPI
        <div style="font-size:10px;color:#999;font-weight:normal;">
            PDAM Tirta Pinang
        </div>
    </div>

    <div class="menu-title">MENU NAVIGASI</div>

    <a href="{{ route('dashboard') }}" class="active">
        <i class="bi bi-grid me-2"></i> Dashboard
    </a>

    <a href="{{ route('dokumen.index') }}">
        <i class="bi bi-file-earmark-text me-2"></i> Dokumen
    </a>

    <a href="{{ route('sk.index') }}">
        <i class="bi bi-file-earmark-ruled me-2"></i> Surat Keputusan (SK)
    </a>

    <a href="{{ route('laporan.dokumen') }}">
        <i class="bi bi-bar-chart me-2"></i> Laporan
    </a>

    @if($user->role === 'admin')
        <div class="menu-title">MASTER DATA</div>

        <a href="{{ route('kategori.index') }}">
            <i class="bi bi-tags me-2"></i> Kategori Dokumen
        </a>

        <a href="{{ route('pengguna.index') }}">
            <i class="bi bi-people me-2"></i> Pengguna
        </a>

        <a href="{{ route('log.index') }}">
            <i class="bi bi-clock-history me-2"></i> Log Aktivitas
        </a>
    @endif

    <div class="menu-title">AKUN</div>

    <a href="{{ route('profil') }}">
        <i class="bi bi-person-circle me-2"></i> Profil Saya
    </a>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-danger w-100 mt-2">
            <i class="bi bi-box-arrow-right me-2"></i> Logout
        </button>
    </form>

</div>

<!-- KONTEN UTAMA -->
<div class="content">

    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <small class="text-muted">Portal SPI / Beranda</small>
            <h5 class="mb-0 mt-1">Dashboard Utama SPI</h5>
        </div>

        <div class="text-end">
            <strong>{{ $user->nama_lengkap }}</strong>
            <br>
            <small class="text-muted">
                {{ $user->role === 'admin' ? 'Administrator' : 'Staf SPI' }}
            </small>
        </div>
    </div>

    <div class="mb-4">
        <h4 class="fw-bold">Selamat Datang di SIDOKSPI</h4>
        <p class="text-muted">
            Ringkasan pengelolaan dokumen dan Surat Keputusan
            Satuan Pengawas Internal PDAM Tirta Pinang.
        </p>
    </div>

    <!-- STATISTIK -->
    <div class="row g-3">

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div class="text-muted mt-3">Total Dokumen</div>
                <div class="stat-number">{{ $totalDokumen }}</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-file-earmark-ruled"></i>
                </div>
                <div class="text-muted mt-3">Total Surat Keputusan</div>
                <div class="stat-number">{{ $totalSK }}</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-tags"></i>
                </div>
                <div class="text-muted mt-3">Total Kategori</div>
                <div class="stat-number">{{ $totalKategori }}</div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="text-muted mt-3">Total Pengguna</div>
                <div class="stat-number">{{ $totalPengguna }}</div>
            </div>
        </div>

    </div>

    <!-- DOKUMEN TERBARU -->
    <div class="panel">

        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold">Dokumen Terbaru</h5>
            <a href="{{ route('dokumen.index') }}" class="text-decoration-none">
                Lihat Semua
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NOMOR DOKUMEN</th>
                        <th>JUDUL DOKUMEN</th>
                        <th>TANGGAL</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($dokumenTerbaru as $index => $dokumen)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $dokumen->nomor_dokumen }}</td>
                            <td>{{ $dokumen->judul }}</td>
                            <td>
                                {{ $dokumen->tanggal_dokumen
                                    ? $dokumen->tanggal_dokumen->format('d M Y')
                                    : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Belum ada dokumen.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- SK TERBARU -->
    <div class="panel">

        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold">SK Terbaru</h5>
            <a href="{{ route('sk.index') }}" class="text-decoration-none">
                Lihat Semua
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NOMOR SK</th>
                        <th>JUDUL KEPUTUSAN</th>
                        <th>TANGGAL PENETAPAN</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($skTerbaru as $index => $sk)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $sk->nomor_sk }}</td>
                            <td>{{ $sk->judul }}</td>
                            <td>
                                {{ $sk->tanggal_penetapan
                                    ? $sk->tanggal_penetapan->format('d M Y')
                                    : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">
                                Belum ada Surat Keputusan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

</div>

</body>
</html>
