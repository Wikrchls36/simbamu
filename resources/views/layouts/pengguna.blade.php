<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMBAMU - MDMC Daerah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .sidebar { width: 260px; height: 100vh; background: #0066ff; color: white; position: fixed; }
        .nav-link { color: white; padding: 15px 25px; display: flex; align-items: center; }
        .nav-link:hover { background: rgba(255,255,255,0.1); }
        .nav-link i { margin-right: 15px; width: 20px; }
        .main-content { margin-left: 260px; min-height: 100vh; display: flex; flex-direction: column; }
        .header { background: white; padding: 15px 30px; border-bottom: 1px solid #eee; display: flex; justify-content: flex-end; }
    </style>
</head>
<body>

    <div class="sidebar shadow">
        <div class="p-4 text-center">
            <h4 class="fw-bold">SIMBAMU</h4>
            <small>MDMC Daerah</small>
        </div>
        <nav class="nav flex-column mt-3">
            <a class="nav-link" href="#"><i class="fas fa-th-large"></i> Beranda</a>
            <a class="nav-link" href="#"><i class="fas fa-map"></i> Peta Potensi</a>
            <a class="nav-link" href="#"><i class="fas fa-exclamation-triangle"></i> Peringatan</a>
            <a class="nav-link" href="#"><i class="fas fa-file-alt"></i> Laporan</a>
        </nav>
    </div>

    <div class="main-content">
        <header class="header">
            <div class="fw-bold">MDMC KOTA PONTIANAK <i class="fas fa-chevron-down ms-2"></i></div>
        </header>

        <div class="p-4 flex-grow-1">
            @yield('content') {{-- Slot untuk isi konten dashboard --}}
        </div>

        <footer class="p-3 text-center text-muted border-top bg-white" style="font-size: 11px;">
            &copy; MDMC KALIMANTAN BARAT 2026 - SOLID BERGERAK MONITOR
        </footer>
    </div>

</body>
</html>