<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda Pengguna - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root { --mdmc-blue: #0047ba; --mdmc-light-blue: #1aa4f6; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; overflow-x: hidden; }
        
        #sidebar {
            width: 280px; min-height: 100vh; background: var(--mdmc-light-blue);
            transition: all 0.3s; position: fixed; z-index: 1000;
            display: flex; flex-direction: column; 
        }
        #sidebar.active { margin-left: -280px; }

        .sidebar-header { 
            height: 70px; display: flex; align-items: center; justify-content: center;
            background: #fff; border-bottom: 1px solid #eee; position: relative;
        }
        
        .nav { flex-grow: 1; display: flex; flex-direction: column; justify-content: flex-start; padding: 20px 0 40px 0; gap: 15px; }
        .nav-item { padding: 0 15px; }
        .nav-link { color: white; padding: 15px 20px; font-weight: 500; transition: 0.3s; border-radius: 8px; display: flex; align-items: center; text-decoration: none; }
        .nav-link:hover { color: var(--mdmc-blue) !important; background: white; }
        
        #sidebarOverlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 999; display: none; }
        #sidebarOverlay.show { display: block; }

        #content { width: calc(100% - 280px); margin-left: 280px; transition: all 0.3s; min-height: 100vh; display: flex; flex-direction: column; }
        #content.active { width: 100%; margin-left: 0; }
        
        .navbar { height: 70px; }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; border: 1px solid #ddd; padding: 2px; }

       
        .card-stat { border: none; border-radius: 15px; color: white; transition: transform 0.3s; }
        .card-stat:hover { transform: translateY(-5px); }
        .bg-gradient-blue { background: linear-gradient(45deg, #0d6efd, #0b5ed7); }
        .bg-gradient-orange { background: linear-gradient(45deg, #fd7e14, #e8590c); }

        @media (max-width: 992px) {
            #sidebar { margin-left: -280px; }
            #sidebar.show { margin-left: 0; }
            #content { width: 100%; margin-left: 0; }
        }
        
        .profile-toggle-btn::after { display: none !important; }
        .profile-toggle-btn .profile-arrow { transition: transform 0.3s ease; }
        .profile-toggle-btn.show .profile-arrow { transform: rotate(180deg); }
    </style>
</head>
<body>

<div id="sidebarOverlay" class="d-lg-none"></div>

<div class="d-flex">
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo-mdmc.png') }}" height="40" alt="Logo">
            <button class="btn btn-sm text-dark d-lg-none position-absolute start-0 ms-3" id="closeSidebar">
                <i class="fas fa-times fa-lg"></i>
            </button>
        </div>
        
        <ul class="nav">
            <li class="nav-item"><a href="/pengguna/dashboard" class="nav-link"><i class="fas fa-th-large fa-fw me-3"></i> Beranda</a></li>
            <li class="nav-item"><a href="/pengguna/laporan" class="nav-link"><i class="fas fa-file-alt fa-fw me-3"></i> Laporan Bencana</a></li>
            <li class="nav-item"><a href="/pengguna/rekapitulasi" class="nav-link"><i class="fas fa-chart-bar fa-fw me-3"></i> Rekap Laporan</a></li>
        </ul>
    </nav>

    <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
            <button type="button" id="sidebarCollapse" class="btn btn-primary d-lg-none">
                <i class="fas fa-bars"></i>
            </button>
            <div class="ms-auto dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none text-dark profile-toggle-btn" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/logo-mdmc.png') }}" class="profile-img me-2 shadow-sm" style="object-fit: cover; width: 40px; height: 40px; border-radius: 50%;">
                    <i class="fas fa-chevron-down profile-arrow text-muted"></i>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 rounded-3" style="min-width: 250px; overflow: hidden;">
                    <li class="px-4 py-3 bg-light border-bottom">
                        <span class="d-block text-muted mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Login sebagai:</span>
                        <span class="d-block fw-bold text-dark" style="font-size: 14px; line-height: 1.2;">{{ Auth::user()->name }}</span>
                        <span class="d-block text-primary fw-medium" style="font-size: 12px;">(Pengguna)</span>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 px-4 d-flex align-items-center mt-2" href="/pengguna/profile">
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

        <div class="container-fluid p-4 flex-grow-1">
            
            <div class="mb-4">
                <h4 class="fw-bold text-dark mb-1">Selamat Datang, Relawan {{ Auth::user()->name }}!</h4>
                <p class="text-muted mb-0">Pantau dan laporkan situasi kebencanaan di daerah anda.</p>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card card-stat bg-gradient-blue p-4 shadow h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1 opacity-75">TOTAL LAPORAN DAERAH ANDA</h6>
                                <h1 class="display-4 fw-bold">{{ $jumlahLaporan ?? 0 }}</h1>
                            </div>
                            <i class="fas fa-clipboard-check fa-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-white border-0 shadow-sm p-4 h-100 d-flex flex-row justify-content-between align-items-center" style="border-radius: 16px;">
                        <div class="pe-3">
                            <h4 class="fw-bold text-dark mb-2">Ada Kejadian Bencana?</h4>
                            <p class="text-muted mb-0" style="font-size: 0.95rem; line-height: 1.5;">
                                Segera buat laporan baru untuk ditindaklanjuti oleh MDMC Wilayah.
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <a href="{{ route('pengguna.laporan.create') }}" class="btn btn-danger border-0 shadow-sm px-4 py-2 d-flex flex-column align-items-center justify-content-center text-decoration-none" style="background-color: #dc3545; border-radius: 25px; line-height: 1.2;">
                                <span class="fw-bold text-white"><i class="fas fa-plus me-1"></i> Buat</span>
                                <span class="fw-bold text-white">Laporan</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                            <h6 class="fw-bold text-dark m-0"><i class="fas fa-chart-bar me-2 text-primary"></i>Statistik Laporan Bencana</h6>
                        </div>
                        <div class="card-body px-4 pb-4">
                            <canvas id="bencanaChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                            <h6 class="fw-bold text-dark m-0"><i class="fas fa-chart-pie me-2 text-primary"></i>Status Laporan</h6>
                        </div>
                        <div class="card-body px-4 pb-4 d-flex justify-content-center align-items-center">
                            <canvas id="statusChart" height="200"></canvas>
                        </div>
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
    
    sidebarCollapse.addEventListener('click', () => {
        sidebar.classList.add('show'); sidebarOverlay.classList.add('show');
    });

    const hideSidebar = () => {
        sidebar.classList.remove('show'); sidebarOverlay.classList.remove('show');
    };

    closeSidebar.addEventListener('click', hideSidebar);
    sidebarOverlay.addEventListener('click', hideSidebar);

    document.addEventListener("DOMContentLoaded", function() {
    
        const ctxBencana = document.getElementById('bencanaChart').getContext('2d');
        new Chart(ctxBencana, {
            type: 'bar',
            data: {
                labels: ['Banjir', 'Karhutla'],
                datasets: [{
                    label: 'Jumlah Bencana',
                    data: [{{ $laporanBanjir ?? 0 }}, {{ $laporanKarhutla ?? 0 }}],
                    backgroundColor: ['#0d6efd', '#dc3545'], // Biru untuk Banjir, Merah untuk Karhutla
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: ['Aktif', 'Selesai'],
                datasets: [{
                    data: [{{ $laporanAktif ?? 0 }}, {{ $laporanSelesai ?? 0 }}],
                    backgroundColor: ['#0d6efd', '#198754'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    });
</script>
</body>
</html>