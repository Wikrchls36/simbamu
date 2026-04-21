<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMBAMU - MDMC Kalbar')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <style>
    :root { --mdmc-blue: #0047ba; --mdmc-light-blue: #1aa4f6; }
    body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; overflow-x: hidden; }
    
    /* SIDEBAR */
    #sidebar {
        width: 280px; min-height: 100vh; background: var(--mdmc-light-blue);
        transition: all 0.3s; position: fixed; z-index: 1000;
        display: flex; flex-direction: column;
    }
    .sidebar-header { 
        height: 70px; display: flex; align-items: center; justify-content: center;
        background: #fff; border-bottom: 1px solid #eee; position: relative;
    }

    /* PERBAIKAN 2: Navigasi Sidebar (Tanpa Bayangan/Box Putih) */
    .nav {
        flex-grow: 1; display: flex; flex-direction: column;
        justify-content: space-evenly; padding: 20px 0 40px 0; 
    }
    .nav-item { padding: 0 15px; }
    .nav-link { 
        color: white; padding: 15px 20px; font-weight: 500; transition: 0.3s; 
        border-radius: 8px; display: flex; align-items: center; text-decoration: none;
    }
    /* Hanya hover yang berubah warna background, menu aktif tidak perlu box putih */
    .nav-link:hover { color: var(--mdmc-blue) !important; background: white; }
    
    /* Menu Aktif cukup kita buat teksnya lebih tebal atau tetap putih tanpa box */
    .nav-link.active { background: none; font-weight: 700; color: #fff; }

    /* MAIN CONTENT */
    #content { width: calc(100% - 280px); margin-left: 280px; transition: all 0.3s; min-height: 100vh; display: flex; flex-direction: column; }
    
    /* PERBAIKAN 1: Jarak Navbar & Profil */
    .navbar { height: 70px; padding-left: 1.5rem; padding-right: 1.5rem; }
    .profile-img { 
        width: 40px; height: 40px; border-radius: 50%; border: 1px solid #ddd; 
        padding: 2px; object-fit: cover;
    }

    /* MOBILE RESPONSIVE & OTHER STYLES */
    #sidebarOverlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.5); z-index: 999; display: none; }
    #sidebarOverlay.show { display: block; }
    .profile-toggle-btn::after { display: none !important; }
    .profile-toggle-btn .profile-arrow { transition: transform 0.3s ease; }
    .profile-toggle-btn.show .profile-arrow { transform: rotate(180deg); }

    @media (max-width: 992px) {
        #sidebar { margin-left: -280px; }
        #sidebar.show { margin-left: 0; }
        #content { width: 100%; margin-left: 0; }
    }
    @stack('styles')
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
            <li class="nav-item"><a href="/dashboard" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}"><i class="fas fa-th-large me-3"></i> Beranda</a></li>
            
            @if(Auth::user()->role === 'admin')
            <li class="nav-item"><a href="/users" class="nav-link {{ Request::is('users*') ? 'active' : '' }}"><i class="fas fa-users-cog me-3"></i> Manajemen Pengguna</a></li>
            @endif

            <li class="nav-item"><a href="/peta" class="nav-link {{ Request::is('peta') ? 'active' : '' }}"><i class="fas fa-map-marked-alt me-3"></i> Peta Potensi Bencana</a></li>
            <li class="nav-item"><a href="/peringatan" class="nav-link {{ Request::is('peringatan') ? 'active' : '' }}"><i class="fas fa-exclamation-triangle me-3"></i> Peringatan Bencana</a></li>
            <li class="nav-item"><a href="/laporan" class="nav-link {{ Request::is('laporan') ? 'active' : '' }}"><i class="fas fa-file-alt me-3"></i> Laporan Bencana</a></li>
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
                        
                        <span class="d-block text-primary fw-medium" style="font-size: 12px; text-transform: capitalize;">
                            ({{ Auth::user()->role === 'admin' ? 'Admin Wilayah' : 'MDMC Daerah' }})
                        </span>

                    </li>
                    <li><a class="dropdown-item py-2 px-4 d-flex align-items-center mt-2" href="/profile"><i class="fas fa-user-edit text-muted me-3" style="width: 20px;"></i> <span>Edit Profil</span></a></li>
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

        <main class="container-fluid p-4 flex-grow-1">
            @yield('content')
        </main>

        <footer class="text-center py-4 mt-auto">
            <small class="text-muted">
                <i class="far fa-copyright"></i> MDMC KALIMANTAN BARAT 2026 - SOLID BERGERAK MONITOR | DIKELOLA OLEH BIDANG DATA DAN INFORMASI
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
</script>

@stack('scripts')
</body>
</html>