<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Dampak - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --mdmc-blue: #0047ba; --mdmc-light-blue: #1aa4f6; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; overflow-x: hidden; }
        #sidebar { width: 280px; min-height: 100vh; background: var(--mdmc-light-blue); transition: all 0.3s; position: fixed; z-index: 1000; display: flex; flex-direction: column; }
        #sidebar.active { margin-left: -280px; }
        .sidebar-header { height: 70px; display: flex; align-items: center; justify-content: center; background: #fff; border-bottom: 1px solid #eee; position: relative; }
        .nav { flex-grow: 1; display: flex; flex-direction: column; padding: 20px 0 40px 0; gap: 10px; }
        .nav-item { padding: 0 15px; }
        .nav-link { color: rgba(255, 255, 255, 0.8); padding: 14px 20px; font-weight: 500; transition: 0.3s; border-radius: 8px; display: flex; align-items: center; text-decoration: none;}
        .nav-link:hover { color: #ffffff !important; background: rgba(255, 255, 255, 0.1); }
        .nav-link.active { color: #ffffff !important; background: transparent; font-weight: 700; border: none; }
        #sidebarOverlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 999; display: none; }
        #sidebarOverlay.show { display: block; }
        #content { width: calc(100% - 280px); margin-left: 280px; transition: all 0.3s; min-height: 100vh; display: flex; flex-direction: column; }
        .navbar { height: 70px; }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; border: 1px solid #ddd; padding: 2px; }
        .profile-toggle-btn::after { display: none !important; }
        .profile-toggle-btn .profile-arrow { transition: transform 0.3s ease; }
        .profile-toggle-btn.show .profile-arrow { transform: rotate(180deg); }
        .rekap-card { border: none; border-radius: 16px; transition: all 0.3s ease; border: 1px solid rgba(0,0,0,0.03); background: #fff; position: relative; overflow: hidden; }
        .rekap-card:hover { transform: translateY(-5px); box-shadow: 0 12px 24px rgba(0,0,0,0.06) !important; }
        .rekap-icon-box { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .table-custom th { background-color: #f8f9fa; color: #444; font-weight: 600; padding: 16px 15px; border-bottom: 2px solid #dee2e6; text-align: center; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;}
        .table-custom td { padding: 15px; border-bottom: 1px solid #f1f1f1; vertical-align: middle; text-align: center;}
        .filter-select { background-color: #fff; border: 1px solid #e0e0e0; font-weight: 500; border-radius: 8px; padding: 8px 35px 8px 15px; font-size: 14px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); color: #444;}
        .filter-select:focus { border-color: var(--mdmc-blue); box-shadow: 0 0 0 0.2rem rgba(0, 71, 186, 0.15); }

        @media (max-width: 992px) {
            #sidebar { margin-left: -280px; }
            #sidebar.show { margin-left: 0; }
            #content { width: 100%; margin-left: 0; }
        }
    </style>
</head>
<body>

<div id="sidebarOverlay" class="d-lg-none"></div>

<div class="d-flex">
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo-mdmc.png') }}" height="40" alt="Logo" onerror="this.style.display='none'">
            <button class="btn btn-sm text-dark d-lg-none position-absolute start-0 ms-3" id="closeSidebar"><i class="fas fa-times fa-lg"></i></button>
        </div>
        
        <ul class="nav mt-3">
            <li class="nav-item"><a href="/dashboard" class="nav-link"><i class="fas fa-th-large fa-fw me-3"></i> Beranda</a></li>
            <li class="nav-item"><a href="/users" class="nav-link"><i class="fas fa-users-cog fa-fw me-3"></i> Manajemen Pengguna</a></li>
            <li class="nav-item"><a href="/laporan" class="nav-link"><i class="fas fa-file-alt fa-fw me-3"></i> Laporan Bencana</a></li>
            <li class="nav-item"><a href="/rekapitulasi" class="nav-link"><i class="fas fa-chart-area fa-fw me-3"></i> Rekap Laporan</a></li>
        </ul>
    </nav>

    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
            <button type="button" id="sidebarCollapse" class="btn btn-primary d-lg-none me-3"><i class="fas fa-bars"></i></button>
            
            <div class="ms-auto dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none text-dark profile-toggle-btn" data-bs-toggle="dropdown">
                    <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/logo-mdmc.png') }}" class="profile-img me-2 shadow-sm" style="object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=Admin&background=0047ba&color=fff'">
                    <i class="fas fa-chevron-down profile-arrow text-muted"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 rounded-3" style="min-width: 250px; overflow: hidden;">
                    <li class="px-4 py-3 bg-light border-bottom">
                        <span class="d-block text-muted mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Login sebagai:</span>
                        <span class="d-block fw-bold text-dark" style="font-size: 14px; line-height: 1.2;">{{ Auth::user()->name }}</span>
                        <span class="d-block text-primary fw-medium" style="font-size: 12px;">(Admin)</span>
                    </li>

                    <li>
                        <a class="dropdown-item py-2 px-4 d-flex align-items-center mt-2" href="/profile">
                            <i class="fas fa-user-edit text-muted me-3" style="width: 20px;"></i> 
                            <span>Edit Profile</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <form action="{{ url('/logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 px-4 d-flex align-items-center text-danger mb-1">
                                <i class="fas fa-power-off me-3" style="width: 20px;"></i> 
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid p-4 p-md-5 flex-grow-1">
            
            <div class="row align-items-center mb-4 pb-2 border-bottom judul-halaman">
                <div class="col-lg-5 mb-3 mb-lg-0">
                    <h3 class="fw-bold text-dark m-0">Rekapitulasi Laporan Bencana</h3>
                    <p class="text-muted m-0 mt-1" style="font-size: 14px;">
                        Data bersumber dari <strong>{{ $totalKejadian }} laporan kejadian</strong> bencana
                    </p>
                </div>
                <div class="col-lg-7">
                    <form method="GET" action="{{ route('admin.rekapitulasi.index') }}" class="d-flex gap-2 justify-content-lg-end flex-wrap filter-section">
                        <select name="status" class="form-select filter-select" style="width: auto; min-width: 140px;" onchange="this.form.submit()">
                            <option value="Semua" {{ $filterStatus == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                            <option value="Aktif" {{ $filterStatus == 'Aktif' ? 'selected' : '' }}>Masih Aktif</option>
                            <option value="Selesai" {{ $filterStatus == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>

                        <select name="jenis_bencana" class="form-select filter-select" style="width: auto; min-width: 150px;" onchange="this.form.submit()">
                            <option value="Semua" {{ $filterJenis == 'Semua' ? 'selected' : '' }}>Semua Bencana</option>
                            <option value="Banjir" {{ $filterJenis == 'Banjir' ? 'selected' : '' }}>Khusus Banjir</option>
                            <option value="Karhutla" {{ $filterJenis == 'Karhutla' ? 'selected' : '' }}>Khusus Karhutla</option>
                        </select>
                        
                        <select name="tahun" class="form-select filter-select" style="width: auto;" onchange="this.form.submit()">
                            @for ($i = date('Y'); $i >= 2024; $i--)
                                <option value="{{ $i }}" {{ $filterTahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>

                        <select name="bulan" class="form-select filter-select" style="width: auto; min-width: 130px;" onchange="this.form.submit()">
                            <option value="all" {{ $filterBulan == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                            @php
                                $namaBulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                            @endphp
                            @foreach($namaBulanList as $index => $namaBulan)
                                <option value="{{ $index + 1 }}" {{ $filterBulan == ($index + 1) ? 'selected' : '' }}>{{ $namaBulan }}</option>
                            @endforeach
                        </select>
                        <a href="{{ route('admin.rekapitulasi.cetak', request()->query()) }}" target="_blank" class="btn btn-dark fw-bold rounded-3 px-3 shadow-sm d-flex align-items-center" style="font-size: 14px;" title="Cetak Laporan Formal">
                            <i class="fas fa-print"></i>
                        </a>
                    </form>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4 col-sm-6">
                    <div class="rekap-card p-4 h-100 shadow-sm border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-bold text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Korban Meninggal</h6>
                                <h2 class="fw-bold text-danger mb-0">{{ number_format($totalMeninggal, 0, ',', '.') }} <span style="font-size: 14px; font-weight: 500;" class="text-muted">Jiwa</span></h2>
                            </div>
                            <div class="rekap-icon-box bg-danger bg-opacity-10 text-danger"><i class="fas fa-skull"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="rekap-card p-4 h-100 shadow-sm border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-bold text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Korban Luka-luka</h6>
                                <h2 class="fw-bold text-warning mb-0">{{ number_format($totalLuka, 0, ',', '.') }} <span style="font-size: 14px; font-weight: 500;" class="text-muted">Jiwa</span></h2>
                            </div>
                            <div class="rekap-icon-box bg-warning bg-opacity-10 text-warning"><i class="fas fa-user-injured"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="rekap-card p-4 h-100 shadow-sm border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-muted fw-bold text-uppercase mb-1" style="font-size: 11px; letter-spacing: 0.5px;">Korban Hilang</h6>
                                <h2 class="fw-bold text-secondary mb-0">{{ number_format($totalHilang, 0, ',', '.') }} <span style="font-size: 14px; font-weight: 500;" class="text-muted">Jiwa</span></h2>
                            </div>
                            <div class="rekap-icon-box bg-secondary bg-opacity-10 text-secondary"><i class="fas fa-search"></i></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="rekap-card p-4 h-100 shadow-sm border-0" style="border-left: 5px solid #0dcaf0 !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-info fw-bold text-uppercase mb-1" style="font-size: 13px;">Total Pengungsi</h6>
                                <h1 class="fw-bold text-dark mb-0">{{ number_format($totalPengungsi, 0, ',', '.') }}</h1>
                            </div>
                            <i class="fas fa-campground fa-3x text-info opacity-25"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="rekap-card p-4 h-100 shadow-sm border-0" style="border-left: 5px solid #0047ba !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="text-primary fw-bold text-uppercase mb-1" style="font-size: 13px;">Total Warga Terdampak</h6>
                                <h1 class="fw-bold text-dark mb-0">{{ number_format($totalTerdampak, 0, ',', '.') }}</h1>
                            </div>
                            <i class="fas fa-users fa-3x text-primary opacity-25"></i>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="rekap-card p-4 shadow-sm bg-success text-white border-0" style="background: linear-gradient(135deg, #198754, #20c997);">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-uppercase mb-1 text-white-50" style="font-size: 13px; letter-spacing: 1px;">TTotal Relawan Yang Dikerahkan </h6>
                                <h1 class="fw-bold mb-0 text-white">{{ number_format($totalRelawan, 0, ',', '.') }} <span style="font-size: 18px; font-weight: 500; opacity: 0.9;">Personil</span></h1>
                            </div>
                            <i class="fas fa-hard-hat fa-4x opacity-50 text-white"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-3 d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i class="fas fa-map-marked-alt"></i>
                    </div>
                    <h5 class="fw-bold text-dark m-0">Rincian Laporan Per Daerah</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-custom table-hover m-0">
                            <thead>
                                <tr>
                                    <th class="text-start ps-4">Nama Daerah</th>
                                    <th class="text-primary">Jml Kejadian</th>
                                    <th>Meninggal</th>
                                    <th>Luka-luka</th>
                                    <th>Hilang</th>
                                    <th>Pengungsi</th>
                                    <th>Terdampak</th>
                                    <th class="pe-4">Tim Relawan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rincianDaerah as $daerah => $data)
                                <tr>
                                    <td class="text-start ps-4 fw-bold text-dark">{{ $daerah }}</td>
                                    <td class="fw-bold text-primary bg-primary bg-opacity-10">{{ number_format($data['jumlah_kejadian'], 0, ',', '.') }} Laporan</td>
                                    <td>{{ number_format($data['meninggal'], 0, ',', '.') }}</td>
                                    <td>{{ number_format($data['luka'], 0, ',', '.') }}</td>
                                    <td>{{ number_format($data['hilang'], 0, ',', '.') }}</td>
                                    <td class="fw-bold text-info">{{ number_format($data['pengungsi'], 0, ',', '.') }}</td>
                                    <td class="fw-bold text-primary">{{ number_format($data['terdampak'], 0, ',', '.') }}</td>
                                    <td class="pe-4 fw-bold text-success">{{ number_format($data['relawan'], 0, ',', '.') }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <i class="fas fa-folder-open fs-2 mb-3 opacity-50"></i><br>
                                        Belum ada data dampak untuk filter saat ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
        
        <footer class="text-center py-4 mt-auto filter-section">
            <small class="text-muted">
                <i class="far fa-copyright"></i> MDMC KALIMANTAN BARAT 2026 - SOLID BERGERAK MONITOR | DIKELOLA OLEH BIDANG TANGGAP DARURAT
            </small>
        </footer>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const sidebar = document.getElementById('sidebar');
    const sidebarCollapse = document.getElementById('sidebarCollapse');
    const closeSidebar = document.getElementById('closeSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    sidebarCollapse.addEventListener('click', () => { sidebar.classList.add('show'); sidebarOverlay.classList.add('show'); });
    const hideSidebar = () => { sidebar.classList.remove('show'); sidebarOverlay.classList.remove('show'); };
    closeSidebar.addEventListener('click', hideSidebar); sidebarOverlay.addEventListener('click', hideSidebar);
</script>
</body>
</html>