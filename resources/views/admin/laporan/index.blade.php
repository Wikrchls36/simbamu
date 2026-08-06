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

        .nav-link:hover, .nav-link.active { 
            color: var(--mdmc-blue) !important; background: white; 
        }
        
        #sidebarOverlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); z-index: 999; display: none;
        }
        #sidebarOverlay.show { display: block; }
        
        #content { width: calc(100% - 280px); margin-left: 280px; transition: all 0.3s; min-height: 100vh; display: flex; flex-direction: column; }
        #content.active { width: 100%; margin-left: 0; }
        .navbar { height: 70px; }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; border: 1px solid #ddd; padding: 2px; }

        .profile-toggle-btn::after { display: none !important; }
        .profile-toggle-btn .profile-arrow { transition: transform 0.3s ease; }
        .profile-toggle-btn.show .profile-arrow { transform: rotate(180deg); }

        .table-custom th { color: #333; font-weight: 600; padding: 15px; border-bottom: 2px solid #ddd; }
        .table-custom td { vertical-align: middle; padding: 15px; border-bottom: 1px solid #eee; }
        
        #searchInput:focus {
            box-shadow: none !important;
            border-color: #dee2e6 !important;
        }

        @media (max-width: 992px) {
            #sidebar { margin-left: -280px; }
            #sidebar.show { margin-left: 0; }
            #content { width: 100%; margin-left: 0; }
        }

        .btn-warning { transition: all 0.3s ease-in-out !important; }
        .btn-warning:hover {
            background-color: #e5b800 !important;
            border-color: #d4a700 !important;
            color: #000 !important;
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.2) !important;
        }

        /* --- STYLING KHUSUS UNTUK CETAK PDF/PRINT --- */
        #printHeader { display: none; }
        
        @media print {
            body { background-color: #fff; }
            @page { size: landscape; margin: 15mm; }
            #sidebar, .navbar, .btn, .input-group, #sidebarOverlay, footer, .filter-section { display: none !important; }
            #content { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
            .card { box-shadow: none !important; border: none !important; margin: 0 !important; }
            .card-header { display: none !important; }
            .table-responsive { max-height: none !important; overflow: visible !important; }
            .aksi-kolom { display: none !important; } 
            #printHeader { 
                display: block !important; 
                text-align: center; 
                margin-bottom: 25px; 
                border-bottom: 2px solid #000; 
                padding-bottom: 15px; 
            }
            #printHeader h3 { margin: 0; font-weight: bold; color: #000; }
            #printHeader p { margin: 0; font-size: 14px; }
        }
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
            <li class="nav-item"><a href="/dashboard" class="nav-link"><i class="fas fa-th-large fa-fw me-3"></i> Beranda</a></li>
            <li class="nav-item"><a href="/users" class="nav-link"><i class="fas fa-users-cog fa-fw me-3"></i> Manajemen Pengguna</a></li>
            <li class="nav-item"><a href="/laporan" class="nav-link"><i class="fas fa-file-alt fa-fw me-3"></i> Laporan Bencana</a></li>
            <li class="nav-item"><a href="/rekapitulasi" class="nav-link"><i class="fas fa-chart-area fa-fw me-3"></i> Rekap Laporan</a></li>
        </ul>
    </nav>

    <div id="content">
        
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
            <div class="d-flex align-items-center">
                <button type="button" id="sidebarCollapse" class="btn btn-primary d-lg-none me-3">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
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

        <div class="container-fluid p-4 flex-grow-1">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center shadow-sm border-0" id="success-alert">
                    <i class="fas fa-check-circle me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row mb-4 filter-section">
                <div class="col-md-6 col-lg-4 mb-3 mb-md-0">
                    <div class="card border-0 shadow-sm rounded-4" style="background-color: #0056ff; overflow: hidden;">
                        <div class="card-body p-4 position-relative">
                            <h5 class="fw-bold text-white mb-4 position-relative" style="z-index: 2;">Titik Laporan Bencana</h5>
                            <a href="{{ route('admin.laporan.peta') }}" class="btn btn-warning fw-bold px-4 rounded-pill shadow-sm position-relative" style="z-index: 2;">
                             Lihat Titik Laporan
                            </a>
                            <i class="fas fa-map-marked-alt position-absolute" style="font-size: 6rem; color: rgba(255,255,255,0.2); bottom: -10px; right: 10px; z-index: 1;"></i>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-body p-4 d-flex flex-column justify-content-center">
                            <h6 class="fw-bold text-dark mb-3"><i class="fas fa-filter me-2 text-primary"></i>Filter Log Laporan</h6>
                            <form action="{{ url('/laporan') }}" method="GET" class="row g-2 align-items-center">
                                <div class="col-12 col-md-4">
                                    <select name="status" class="form-select form-select-sm border-secondary shadow-sm" onchange="this.form.submit()">
                                        <option value="Semua" {{ ($filterStatus ?? 'Semua') == 'Semua' ? 'selected' : '' }}>Semua Status</option>
                                        <option value="Aktif" {{ ($filterStatus ?? '') == 'Aktif' ? 'selected' : '' }}>Hanya Status Aktif</option>
                                        <option value="Selesai" {{ ($filterStatus ?? '') == 'Selesai' ? 'selected' : '' }}>Hanya Status Selesai</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <select name="jenis_bencana" class="form-select form-select-sm border-secondary shadow-sm" onchange="this.form.submit()">
                                        <option value="Semua" {{ ($filterJenis ?? 'Semua') == 'Semua' ? 'selected' : '' }}>Semua Jenis Bencana</option>
                                        <option value="Banjir" {{ ($filterJenis ?? '') == 'Banjir' ? 'selected' : '' }}>Hanya Banjir</option>
                                        <option value="Karhutla" {{ ($filterJenis ?? '') == 'Karhutla' ? 'selected' : '' }}>Hanya Karhutla</option>
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                <div class="card-header bg-white border-bottom-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h6 class="fw-bold text-dark m-0">Log Laporan Bencana</h6>
                    
                    <div class="input-group" style="max-width: 250px;">
                        <input type="text" id="searchInput" class="form-control form-control-sm rounded-start-pill border-end-0" placeholder="Cari pelapor/bencana...">
                        <span class="input-group-text bg-white rounded-end-pill border-start-0 text-muted">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    
                    <div id="printHeader">
                        <h3>REKAPITULASI LAPORAN BENCANA</h3>
                        <p>MUHAMMADIYAH DISASTER MANAGEMENT CENTER (MDMC) KALIMANTAN BARAT</p>
                        <p>Tanggal Cetak: {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y, H:i') }} WIB</p>
                        @if(($filterStatus ?? 'Semua') != 'Semua' || ($filterJenis ?? 'Semua') != 'Semua')
                            <p style="margin-top: 5px; font-style: italic;">
                                (Filter: Status {{ $filterStatus ?? 'Semua' }}, Jenis Bencana {{ $filterJenis ?? 'Semua' }})
                            </p>
                        @endif
                    </div>

                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-custom m-0 table-hover">
                            <thead class="bg-white" style="position: sticky; top: 0; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <tr>
                                    <th class="ps-4" style="width: 5%">No</th>
                                    <th>Tanggal</th>
                                    <th>Update</th>
                                    <th>Pelapor</th>
                                    <th>Status</th>
                                    <th>Jenis Bencana</th>
                                    <th class="text-center aksi-kolom" style="width: 15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white" id="logTableBody">
                                
                                @forelse($laporans as $index => $laporan)
                                <tr>
                                    <td class="ps-4">{{ $index + 1 }}</td>
                                    
                                    <td class="text-muted small">{{ $laporan->created_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y') }}</td>
                                    
                                    <td>
                                        <span class="d-block text-dark small">{{ $laporan->updated_at->timezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y') }},</span>
                                        <span class="d-block text-dark fw-bold small">{{ $laporan->updated_at->timezone('Asia/Jakarta')->translatedFormat('H.i') }} WIB</span>
                                    </td>
                                    
                                    <td class="fw-medium text-dark">{{ $laporan->user->name ?? 'User Tidak Diketahui' }}</td>

                                    <td>
                                        @if($laporan->status == 'Aktif')
                                            <span class="badge bg-primary px-3 py-2 rounded-pill">Aktif</span>
                                        @elseif($laporan->status == 'Selesai')
                                            <span class="badge bg-success px-3 py-2 shadow-sm rounded-pill text-white">Selesai</span>
                                        @endif
                                    </td>
                                    
                                    <td class="fw-bold {{ $laporan->jenis_bencana == 'Karhutla' ? 'text-danger' : 'text-info' }}">
                                        {{ $laporan->jenis_bencana }}
                                    </td>

                                    <td class="text-center aksi-kolom d-flex justify-content-center">
                                        <a href="{{ route('admin.laporan.show', $laporan->id) }}" class="btn btn-primary btn-sm rounded-3 shadow-sm me-1" title="Lihat Laporan">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($laporan->status == 'Aktif')
                                            <form action="{{ route('admin.laporan.selesai', $laporan->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-success btn-sm rounded-3 shadow-sm" title="Konfirmasi Selesai" onclick="return confirm('Apakah Anda yakin laporan dari daerah ini telah selesai/kondusif?')">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-5">
                                        <i class="fas fa-folder-open mb-3 fs-3 opacity-50"></i><br>
                                        Belum ada data laporan bencana yang sesuai dengan filter.
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

    document.addEventListener("DOMContentLoaded", function() {
        const alertElement = document.getElementById("success-alert");
        if (alertElement) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
            }, 3000);
        }
    });

    const searchInput = document.getElementById('searchInput');
    const logTableBody = document.getElementById('logTableBody');

    if (searchInput && logTableBody) {
        searchInput.addEventListener('keyup', function() {
            let filterValue = this.value.toLowerCase();
            let rows = logTableBody.getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                if (rows[i].getElementsByTagName('td').length === 1) { continue; }
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