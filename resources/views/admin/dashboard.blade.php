<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard SIMBAMU</title>
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
        
        .nav {
            flex-grow: 1; 
            display: flex; flex-direction: column;
            justify-content: flex-start; 
            padding: 20px 0 40px 0; 
            gap: 15px; 
        }

        .nav-item { padding: 0 15px; }

        .nav-link { 
            color: white; padding: 15px 20px; font-weight: 500; transition: 0.3s; 
            border-radius: 8px; display: flex; align-items: center;
        }

        .nav-link:hover { color: var(--mdmc-blue) !important; background: white; }
        
        #sidebarOverlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); z-index: 999; display: none;
        }
        #sidebarOverlay.show { display: block; }

        #content { 
            width: calc(100% - 280px); 
            margin-left: 280px; 
            transition: all 0.3s; 
            min-height: 100vh;
            display: flex;
            flex-direction: column; 
        }
        #content.active { width: 100%; margin-left: 0; }
        
        .navbar { height: 70px; }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; border: 1px solid #ddd; padding: 2px; }

        .card-stat { border: none; border-radius: 15px; color: white; transition: transform 0.3s; }
        .card-stat:hover { transform: translateY(-5px); }
        .bg-gradient-blue { background: linear-gradient(45deg, #000080, #0000ff); }
        .bg-gradient-cyan { background: linear-gradient(45deg, #0088cc, #00bbff); }

        @media (max-width: 992px) {
            #sidebar { margin-left: -280px; }
            #sidebar.show { margin-left: 0; }
            #content { width: 100%; margin-left: 0; }
        }
        
        .profile-toggle-btn::after { display: none !important; }
        .profile-toggle-btn .profile-arrow { transition: transform 0.3s ease; }
        .profile-toggle-btn.show .profile-arrow { transform: rotate(180deg); }
        .card-daerah {
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05) !important;
            background-color: #ffffff;
        }
        .card-daerah:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 15px rgba(0, 71, 186, 0.1) !important;
            border-color: rgba(0, 71, 186, 0.2) !important;
        }
        .aksen-kiri {
            width: 4px;
            background: linear-gradient(180deg, var(--mdmc-light-blue), var(--mdmc-blue));
            border-radius: 4px 0 0 4px;
        }
        .angka-laporan-wrapper {
            width: 48px;
            height: 48px;
            background-color: #f8f9fa;
            border: 2px solid #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
        }
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
            <li class="nav-item"><a href="/dashboard" class="nav-link"><i class="fas fa-th-large fa-fw me-3"></i> Beranda</a></li>
            <li class="nav-item"><a href="/users" class="nav-link"><i class="fas fa-users-cog fa-fw me-3"></i> Manajemen Pengguna</a></li>
            <li class="nav-item"><a href="/laporan" class="nav-link"><i class="fas fa-file-alt fa-fw me-3"></i> Laporan Bencana</a></li>
            <li class="nav-item"><a href="/rekapitulasi" class="nav-link"><i class="fas fa-chart-area fa-fw me-3"></i> Rekap Laporan</a></li>
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

        <div class="container-fluid p-4 flex-grow-1">
            
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <form method="GET" action="/dashboard" class="d-flex gap-2 align-items-center">
                    
                    <select name="tahun" class="form-select border-primary shadow-sm" style="min-width: 120px; border-radius: 8px; font-weight: 500;" onchange="this.form.submit()">
                        @php $currentYear = date('Y'); @endphp
                        @for ($i = $currentYear; $i >= 2024; $i--)
                            <option value="{{ $i }}" {{ ($filterTahun ?? $currentYear) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>

                    <select name="bulan" class="form-select border-primary shadow-sm" style="min-width: 160px; border-radius: 8px; font-weight: 500;" onchange="this.form.submit()">
                        <option value="all" {{ ($filterBulan ?? 'all') == 'all' ? 'selected' : '' }}>Semua Bulan</option>
                        @php
                            $namaBulanList = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
                        @endphp
                        @foreach($namaBulanList as $index => $namaBulan)
                            <option value="{{ $index + 1 }}" {{ ($filterBulan ?? 'all') == ($index + 1) ? 'selected' : '' }}>{{ $namaBulan }}</option>
                        @endforeach
                    </select>

                </form>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card card-stat bg-gradient-blue p-4 shadow h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-0">PENGGUNA AKTIF</h5>
                                <h1 class="display-4 fw-bold">{{ $jumlahPengguna ?? 0 }}</h1>
                            </div>
                            <i class="fas fa-user-circle fa-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-stat bg-gradient-cyan p-4 shadow h-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="fw-bold mb-0">LAPORAN BENCANA</h5>
                                <h1 class="display-4 fw-bold text-white">{{ $jumlahLaporan ?? 0 }}</h1>
                            </div>
                            <i class="fas fa-clipboard-list fa-4x opacity-50"></i>
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

            <div class="row g-4 mt-1 mb-2">
                <div class="col-12">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h6 class="fw-bold text-dark m-0"><i class="fas fa-chart-line me-2 text-primary"></i>Total Laporan Daerah</h6>
                            <span class="badge bg-light text-muted border py-2 px-3 rounded-pill">
                                Periode: 
                                @if(($filterBulan ?? 'all') == 'all')
                                    Tahun {{ $filterTahun ?? date('Y') }}
                                @else
                                    {{ $namaBulanList[($filterBulan ?? 1) - 1] }} {{ $filterTahun ?? date('Y') }}
                                @endif
                            </span>
                        </div>
                        <div class="card-body p-4 pt-3">
                            <div class="row g-3">
                                @forelse($topDaerah ?? [] as $index => $daerah)
                                <div class="col-md-4">
                                    <div class="card-daerah rounded-4 shadow-sm d-flex align-items-stretch position-relative h-100 pe-3">
                                        
                                        <div class="aksen-kiri position-absolute start-0 top-0 bottom-0"></div>
                                        
                                        
                                        <div class="d-flex align-items-center justify-content-between w-100 py-3 ps-4 ms-1">
                                            
                                           
                                            <div class="d-flex align-items-center">
                                                <div class="fw-bold text-muted me-3" style="font-size: 1.1rem;">
                                                    {{ $index + 1 }}.
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">{{ $daerah->name }}</h6>
                                                    <small class="text-muted d-block mt-1" style="font-size: 0.75rem; font-weight: 500;">
                                                        <i class="fas fa-file-alt text-primary opacity-75 me-1"></i> 
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="angka-laporan-wrapper rounded-circle shadow-sm">
                                                <h4 class="mb-0 fw-bold {{ $daerah->laporans_count > 0 ? 'text-primary' : 'text-secondary opacity-50' }}">
                                                    {{ $daerah->laporans_count }}
                                                </h4>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12 text-center py-4 text-muted">
                                    <i class="fas fa-folder-open mb-2 fs-4 opacity-50"></i><br>
                                    Belum ada data laporan pada periode ini.
                                </div>
                                @endforelse
                            </div>
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
        sidebar.classList.add('show');
        sidebarOverlay.classList.add('show');
    });

    const hideSidebar = () => {
        sidebar.classList.remove('show');
        sidebarOverlay.classList.remove('show');
    };

    closeSidebar.addEventListener('click', hideSidebar);
    sidebarOverlay.addEventListener('click', hideSidebar);

    document.addEventListener("DOMContentLoaded", function() {
        const labelBencana = {!! json_encode($labelBencana ?? ['Banjir', 'Karhutla']) !!};
        const dataBencana = {!! json_encode($dataBencana ?? [0, 0]) !!};
        const labelStatus = {!! json_encode($labelStatus ?? ['Aktif', 'Selesai']) !!};
        const dataStatus = {!! json_encode($dataStatus ?? [0, 0]) !!};

        // Render Bar Chart
        const ctxBencana = document.getElementById('bencanaChart').getContext('2d');
        new Chart(ctxBencana, {
            type: 'bar',
            data: {
                labels: {!! json_encode($labelBencana ?? ['Banjir', 'Karhutla']) !!},
                datasets: [{ 
                    label: 'Jumlah Bencana', 
                    data: {!! json_encode($dataBencana ?? [0, 0]) !!}, 
        
                    backgroundColor: ['#0d6efd', '#dc3545'], 
                    borderRadius: 6 
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
        });

        
        const ctxStatus = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStatus, {
            type: 'doughnut',
            data: {
                labels: labelStatus,
                datasets: [{
                    data: dataStatus,
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