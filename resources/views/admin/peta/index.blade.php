<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Peta Potensi - SIMBAMU</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root { --mdmc-blue: #0047ba; --mdmc-light-blue: #1aa4f6; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; overflow-x: hidden; }
        
        /* SIDEBAR FLEXBOX (Dari Referensi) */
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
            justify-content: space-evenly; padding: 20px 0 40px 0;
        }

        .nav-item { padding: 0 15px; }

        .nav-link { 
            color: white; padding: 15px 20px; font-weight: 500; transition: 0.3s; 
            border-radius: 8px; display: flex; align-items: center;
        }

        .nav-link:hover, .nav-link.active { 
            color: var(--mdmc-blue) !important; background: white; 
        }
        
        /* OVERLAY UNTUK MOBILE */
        #sidebarOverlay {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5); z-index: 999; display: none;
        }
        #sidebarOverlay.show { display: block; }

        /* MAIN CONTENT & NAVBAR */
        #content { width: calc(100% - 280px); margin-left: 280px; transition: all 0.3s; min-height: 100vh; display: flex; flex-direction: column; }
        #content.active { width: 100%; margin-left: 0; }
        .navbar { height: 70px; }
        .profile-img { width: 40px; height: 40px; border-radius: 50%; border: 1px solid #ddd; padding: 2px; }

        .profile-toggle-btn::after { display: none !important; }
        .profile-toggle-btn .profile-arrow { transition: transform 0.3s ease; }
        .profile-toggle-btn.show .profile-arrow { transform: rotate(180deg); }

        /* RESPONSIVE MOBILE */
        @media (max-width: 992px) {
            #sidebar { margin-left: -280px; }
            #sidebar.show { margin-left: 0; }
            #content { width: 100%; margin-left: 0; }
        }

        /* CUSTOM CSS KHUSUS PETA PREVIEW */
        #map-preview { 
            height: 450px; 
            width: 100%; 
            z-index: 1; 
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
            <li class="nav-item"><a href="/dashboard" class="nav-link"><i class="fas fa-th-large me-3"></i> Beranda</a></li>
            <li class="nav-item"><a href="/users" class="nav-link"><i class="fas fa-users-cog me-3"></i> Manajemen Pengguna</a></li>
            <li class="nav-item"><a href="/peta" class="nav-link"><i class="fas fa-map-marked-alt me-3"></i> Peta Potensi Bencana</a></li>
            <li class="nav-item"><a href="/peringatan" class="nav-link"><i class="fas fa-exclamation-triangle me-3"></i> Peringatan Bencana</a></li>
            <li class="nav-item"><a href="/laporan" class="nav-link"><i class="fas fa-file-alt me-3"></i> Laporan Bencana</a></li>
        </ul>
    </nav>

    <div id="content">
        
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4">
            <button type="button" id="sidebarCollapse" class="btn btn-primary d-lg-none">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="ms-auto dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none text-dark profile-toggle-btn" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : asset('images/logo-mdmc.png') }}" class="profile-img me-2 shadow-sm" style="object-fit: cover;">
                    <i class="fas fa-chevron-down profile-arrow text-muted"></i>
                </a>
                
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3 rounded-3" style="min-width: 250px; overflow: hidden;">
                    <li class="px-4 py-3 bg-light border-bottom">
                        <span class="d-block text-muted mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Login sebagai:</span>
                        <span class="d-block fw-bold text-dark" style="font-size: 14px; line-height: 1.2;">{{ Auth::user()->name }}</span>
                        <span class="d-block text-primary fw-medium" style="font-size: 12px;">({{ ucfirst(Auth::user()->role) }})</span>
                    </li>
                    <li>
                        <a class="dropdown-item py-2 px-4 d-flex align-items-center mt-2" href="/profile">
                            <i class="fas fa-user-edit text-muted me-3" style="width: 20px;"></i> <span>Edit Profile</span>
                        </a>
                    </li>
                    <li><hr class="dropdown-divider my-2"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
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
            
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-md-5">
                    
                    <div class="text-center mb-4">
                        <h4 class="fw-bold text-dark text-uppercase">Menu Peta Potensi Bencana</h4>
                        <p class="text-muted small">Peta Interaktif Pantauan Banjir dan Kebakaran Hutan & Lahan Provinsi Kalimantan Barat</p>
                    </div>

                    <div id="map-preview" class="mb-5 rounded-4 shadow-sm border border-light"></div>

                    <div class="d-flex justify-content-center flex-wrap gap-3 mt-4">
                        
                        <a href="{{ route('peta.lihat') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center" style="box-shadow: none !important; transition: none; transform: none;">
                            <i class="fas fa-eye me-2"></i> Lihat Peta 
                        </a>
                        
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('peta.kelola') }}" class="btn btn-success rounded-pill px-4 py-2 fw-bold d-inline-flex align-items-center" style="box-shadow: none !important; transition: none; transform: none;">
                            <i class="fas fa-edit me-2"></i> Kelola Data Daerah
                        </a>
                        @endif
                        
                    </div>

                </div>
            </div>

        </div>

        <footer class="text-center py-4 mt-auto">
            <small class="text-muted">
                <i class="far fa-copyright"></i> MDMC KALIMANTAN BARAT 2026 - SOLID BERGERAK MONITOR | DIKELOLA OLEH BIDANG DATA DAN INFORMASI
            </small>
        </footer>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // 1. LOGIKA RESPONSIVE SIDEBAR MOBILE
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


    // 2. INISIALISASI PETA PREVIEW
    // Menampilkan titik tengah Kalimantan Barat
    var map = L.map('map-preview').setView([-0.2787, 111.4753], 6);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; MDMC Kalbar | SIMBAMU'
    }).addTo(map);

    // Load File GeoJSON sebagai preview area (Garis tipis transparan)
    fetch('/data/kalbar.geojson')
        .then(res => res.json())
        .then(data => {
            L.geoJSON(data, {
                style: { color: '#0047ba', weight: 1.5, fillColor: '#1aa4f6', fillOpacity: 0.1 }
            }).addTo(map);
        });
</script>
</body>
</html>