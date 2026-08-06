<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bencana - SIMBAMU</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            flex-grow: 1; display: flex; flex-direction: column;
            justify-content: flex-start; padding: 20px 0 40px 0; gap: 15px;
        }

        .nav-item { padding: 0 15px; }

        .nav-link { 
            color: white; padding: 15px 20px; font-weight: 500; transition: 0.3s; 
            border-radius: 8px; display: flex; align-items: center; text-decoration: none;
        }
        
        .nav-link:hover { color: var(--mdmc-blue) !important; background: white; }
        
        #sidebarOverlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); z-index: 999; display: none;
        }
        #sidebarOverlay.show { display: block; }

        #content { width: calc(100% - 280px); margin-left: 280px; transition: all 0.3s; min-height: 100vh; display: flex; flex-direction: column;}
        #content.active { width: 100%; margin-left: 0; }
        .navbar { height: 70px; }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; border: 1px solid #ddd; padding: 2px; }

        @media (max-width: 992px) {
            #sidebar { margin-left: -280px; }
            #sidebar.show { margin-left: 0; }
            #content { width: 100%; margin-left: 0; }
        }
        .profile-toggle-btn::after { display: none !important; }
        .profile-toggle-btn .profile-arrow { transition: transform 0.3s ease; }
        .profile-toggle-btn.show .profile-arrow { transform: rotate(180deg); }

        .info-card {
            border-radius: 16px; padding: 24px 30px; color: white;
            position: relative; overflow: hidden; border: none; min-height: 140px;
        }
        .info-card .bg-icon {
            position: absolute; right: -10px; bottom: -20px;
            font-size: 8rem; opacity: 0.15; z-index: 1;
        }
        .info-card-content { position: relative; z-index: 2; }
        
        .btn-yellow { background-color: #ffc107; color: #000; font-weight: 600; border-radius: 20px; padding: 6px 20px; border: none; font-size: 0.9rem; }
        .btn-yellow:hover { background-color: #e0a800; }
        
        .btn-light-rounded { background-color: white; color: #000000; font-weight: 600; border-radius: 20px; padding: 6px 20px; border: none; font-size: 0.9rem; }
        .btn-light-rounded:hover { background-color: #f8f9fa; }

        #searchInput:focus { box-shadow: none !important; border-color: #dee2e6 !important; }
        .table-custom th { border-top: none; border-bottom: 2px solid #eee; font-weight: 600; color: #333; padding-bottom: 15px;}
        .table-custom td { padding: 15px 10px; vertical-align: middle; border-bottom: 1px solid #eee; }
    </style>
</head>
<body>

<div id="sidebarOverlay" class="d-lg-none"></div>

<div class="d-flex">
    
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logo-mdmc.png') }}" height="40" alt="Logo" onerror="this.style.display='none'">
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
                            <i class="fas fa-user-edit text-muted me-3" style="width: 20px;"></i> <span>Edit Profile</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <form action="{{ url('/logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 px-4 d-flex align-items-center text-danger mb-1">
                                <i class="fas fa-power-off me-3" style="width: 20px;"></i> <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>

        <div class="container-fluid p-4 flex-grow-1">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm border-0 mb-4" role="alert">
                    <i class="fas fa-check-circle me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center shadow-sm border-0 mb-4" role="alert">
                    <i class="fas fa-exclamation-circle me-2 fs-5"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row g-4 mb-4">
                <div class="col-md-6 col-lg-5">
                    <div class="card info-card bg-danger shadow-sm">
                        <i class="fas fa-plus-circle bg-icon"></i>
                        <div class="info-card-content">
                            <h4 class="fw-bold mb-3">Buat Laporan Baru</h4>
                            <a href="{{ route('pengguna.laporan.create') }}" class="btn btn-light-rounded shadow-sm">
                                <i class="fas fa-edit me-1"></i> Isi Form SitRep
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-5">
                    <div class="card info-card bg-primary shadow-sm" style="background-color: #0d6efd !important;">
                        <i class="fas fa-map-marker-alt bg-icon"></i>
                        <div class="info-card-content">
                            <h4 class="fw-bold mb-3">Titik Laporan Bencana</h4>
                            <a href="{{ route('pengguna.laporan.peta') }}" class="btn btn-yellow shadow-sm">
                                Lihat Titik Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border border-light shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white pt-4 pb-3 px-4 border-0 d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <h5 class="fw-bold m-0 text-dark mb-3 mb-md-0">Log Laporan Bencana</h5>
                    
                    <div class="input-group" style="max-width: 250px;">
                        <input type="text" id="searchInput" class="form-control form-control-sm rounded-start-pill border-end-0" placeholder="Tuliskan pencarian...">
                        <span class="input-group-text bg-white rounded-end-pill border-start-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
                
                <div class="card-body px-4 pb-4 pt-0">
                    <div class="table-responsive">
                          <table class="table table-custom mb-0 align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Update</th>
                                    <th>Tujuan</th>
                                    <th>Status</th>
                                    <th>Jenis Bencana</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="logTableBody">
                                @forelse($logLaporan as $lap)
                                <tr>
                                    <td class="fw-bold text-muted">{{ $loop->iteration }}</td>
                                    
                                    <td>{{ \Carbon\Carbon::parse($lap->created_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y') }}</td>
                                    
                                    <td>
                                        {{ \Carbon\Carbon::parse($lap->updated_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y,') }} <br>
                                        <strong class="text-dark">{{ \Carbon\Carbon::parse($lap->updated_at)->timezone('Asia/Jakarta')->translatedFormat('H.i') }} WIB</strong>
                                    </td>
                                    
                                    <td class="text-muted">MDMC Wilayah Kalimantan Barat</td>
                                    
                                    <td>
                                        @if($lap->status == 'Aktif')
                                            <span class="badge bg-primary px-3 py-2 shadow-sm rounded-pill">Aktif</span>
                                        @elseif($lap->status == 'Selesai')
                                            <span class="badge bg-success px-3 py-2 shadow-sm rounded-pill text-white">Selesai</span>
                                        @endif
                                    </td>
                                    
                                    <td><span class="fw-bold">{{ $lap->jenis_bencana }}</span></td>
                                    
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">
                                            @php $latestSitrep = $lap->updates->last(); @endphp
                                            @if($latestSitrep)
                                              <a href="{{ route('pengguna.laporan.show', $lap->id) }}" class="btn btn-primary btn-sm shadow-sm rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Lihat Detail Laporan">
                                                <i class="fas fa-eye"></i>
                                              </a>
                                            @endif

                                            @if($lap->status == 'Aktif')
                                                <!-- PERBAIKAN: Tombol Batal/Merah sudah dihilangkan, sisa tombol Update -->
                                                <a href="{{ route('pengguna.laporan.update_create', $lap->id) }}" class="btn btn-success btn-sm shadow-sm rounded-3 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;" title="Update Laporan Baru">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada data laporan bencana yang dikirim.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div> 
        
         <footer class="text-center py-4 mt-auto">
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
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 4000);
        });
    });

    const searchInput = document.getElementById('searchInput');
    const logTableBody = document.getElementById('logTableBody');

    if (searchInput && logTableBody) {
        searchInput.addEventListener('keyup', function() {
            let filterValue = this.value.toLowerCase();
            let rows = logTableBody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                if (rows[i].getElementsByTagName('td').length === 1) continue; 
                
                let rowText = rows[i].textContent.toLowerCase();
                if (rowText.includes(filterValue)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    }
</script>
</body>
</html>