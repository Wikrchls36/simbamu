<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Penyebaran Bencana - SIMBAMU</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; margin: 0; padding: 0; overflow: hidden; }
        
        .map-header { background-color: white; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: relative; z-index: 1000; }
        .map-container { position: relative; width: 100%; height: calc(100vh - 75px); }
        #map { width: 100%; height: 100%; z-index: 1; }

        .btn-back-floating {
            position: absolute; top: 20px; left: 20px; z-index: 1000; width: 50px; height: 50px;
            background-color: white; border-radius: 50%; display: flex; align-items: center;
            justify-content: center; box-shadow: 0 4px 8px rgba(0,0,0,0.2); color: #333;
            text-decoration: none; font-size: 1.4rem; transition: all 0.3s ease;
        }
        .btn-back-floating:hover { transform: scale(1.05); box-shadow: 0 6px 12px rgba(0,0,0,0.3); color: #000; }

        /* Custom Marker Styles */
        .custom-marker {
            display: flex; justify-content: center; align-items: center;
            width: 40px; height: 40px; border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3); color: white; font-size: 1.2rem;
            border: 3px solid white; transition: transform 0.2s;
        }
        .custom-marker { background: #0d6efd; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.3); font-size: 18px; transition: transform 0.2s;}
        .custom-marker:hover { transform: scale(1.1); }
        .custom-marker.karhutla { background: #dc3545; }

        .leaflet-control-zoom { border: none !important; box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important; }

        /* CSS KHUSUS POPUP LEAFLET (SINKRON DENGAN IMAGE 2) */
        .leaflet-popup-content-wrapper { border-radius: 12px; padding: 0; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .leaflet-popup-content { margin: 0; width: 340px !important; }
        .popup-custom { font-family: 'Poppins', sans-serif; font-size: 12px; color: #333; }
        .popup-header { background: #f8f9fa; padding: 12px 15px; border-bottom: 2px solid #eee; display: flex; align-items: center; gap: 10px; }
        .popup-body { padding: 15px; background: #fff; }
        .section-title-popup { background: #e9ecef; font-weight: bold; padding: 6px 10px; margin: 12px 0 8px 0; border-radius: 6px; color: #444; font-size: 12px;}
        .stat-box { border: 1px solid #dee2e6; border-radius: 8px; padding: 8px 5px; text-align: center; background: #fff; }
        .stat-box strong { font-size: 16px; color: #0d6efd; display: block; line-height: 1.2; }
    </style>
</head>
<body>

    <div class="map-header d-flex align-items-center px-4 py-3">
        <div class="header-left" style="width: 220px;">
            <img src="{{ asset('images/logo-mdmc.png') }}" alt="Logo MDMC" style="height: 45px; object-fit: contain;" onerror="this.style.display='none'">
        </div>
        <div class="flex-grow-1 text-center">
            <h5 class="fw-bold m-0 text-dark" style="letter-spacing: 1px;">PETA PENYEBARAN BENCANA</h5>
        </div>
        <div style="width: 220px;"></div>
    </div>

    <div class="map-container">
        <a href="{{ route('laporan.index') }}" class="btn-back-floating" title="Kembali ke Log Laporan">
            <i class="fas fa-arrow-left"></i>
        </a>
        <div id="map"></div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // Inisialisasi Peta
        var map = L.map('map', { zoomControl: false }).setView([-0.27878, 111.4752], 7);
        L.control.zoom({ position: 'bottomleft' }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
           attribution: '© MDMC Kalbar | SIMBAMU'
        }).addTo(map);

        // Render data laporan menggunakan Blade Engine
        @foreach($laporans as $item)
            @php 
                $latest = $item->updates->last(); 
                // Gunakan latitude/longitude dari laporan, jika kosong gunakan koordinat default user
                $lat = $item->latitude ?? ($item->user->latitude ?? -0.27878);
                $lng = $item->longitude ?? ($item->user->longitude ?? 111.4752);
            @endphp
            
            @if($latest)
                var latLng = [{{ $lat }}, {{ $lng }}];
                var isBanjir = '{{ $item->jenis_bencana }}' === 'Banjir';
                
                var iconHtml = isBanjir ? '<i class="fas fa-water"></i>' : '<i class="fas fa-fire"></i>';
                var markerClass = isBanjir ? 'marker-banjir' : 'marker-karhutla';

                var customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="custom-marker ${markerClass}">${iconHtml}</div>`,
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -15] // Posisi popup agar tidak menutupi marker
                });

                var marker = L.marker(latLng, {icon: customIcon}).addTo(map);

                // KONTEN POPUP (Persis dengan Desain Image 2 + Detail Lengkap)
                    // Desain Pop-Up Miniatur SitRep (LENGKAP & MODERN)
                    var popupContent = `
                        <div class="popup-custom">
                            <div class="popup-header">
                                <img src="{{ asset('images/logo-mdmc.png') }}" height="26" onerror="this.style.display='none'">
                                <strong style="font-size: 15px;">SITUATION REPORT</strong>
                            </div>
                            <div class="popup-body">
                                
                                <!-- Info Pelapor & Tanggal -->
                                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                    <div>
                                        <strong class="text-muted text-uppercase" style="font-size: 10px;">Pelapor</strong><br>
                                        <span class="fw-bold text-dark" style="font-size: 12px;">{{ $item->user->name ?? 'MDMC Daerah' }}</span>
                                    </div>
                                    <div class="text-end">
                                        <strong class="text-muted text-uppercase" style="font-size: 10px;">Tanggal SitRep</strong><br>
                                        <span class="fw-bold text-dark" style="font-size: 12px;">{{ \Carbon\Carbon::parse($latest->tanggal_sitrep)->translatedFormat('d M Y') }}</span>
                                    </div>
                                </div>

                                <div class="mb-2 mt-2">
                                    <span style="font-size: 11px;" class="text-muted text-uppercase fw-bold">Jenis Bencana:</span> 
                                    <span class="text-primary fw-bold" style="font-size: 13px;">{{ $item->jenis_bencana }}</span>
                                </div>

                                <!-- BAGIAN A: DAMPAK BENCANA (Tampil Semua) -->
                                <div class="section-title-popup mt-0"><i class="fas fa-users me-1 text-secondary"></i> A. Dampak Korban & Material</div>
                                
                                <!-- 5 Kotak Dampak Korban -->
                                <div class="row g-2 mb-2">
                                    <div class="col-4"><div class="stat-box"><strong>{{ $latest->dampak_meninggal ?? 0 }}</strong><small class="text-muted" style="font-size: 10px;">Meninggal</small></div></div>
                                    <div class="col-4"><div class="stat-box"><strong>{{ $latest->dampak_luka ?? 0 }}</strong><small class="text-muted" style="font-size: 10px;">Luka-luka</small></div></div>
                                    <div class="col-4"><div class="stat-box"><strong>{{ $latest->dampak_hilang ?? 0 }}</strong><small class="text-muted" style="font-size: 10px;">Hilang</small></div></div>
                                    <div class="col-6"><div class="stat-box"><strong>{{ $latest->dampak_pengungsi ?? 0 }}</strong><small class="text-muted" style="font-size: 10px;">Pengungsi</small></div></div>
                                    <div class="col-6"><div class="stat-box"><strong>{{ $latest->dampak_terdampak ?? 0 }}</strong><small class="text-muted" style="font-size: 10px;">Terdampak</small></div></div>
                                </div>
                                
                                <!-- Dampak Material -->
                                <div style="font-size: 11px; margin-bottom: 12px; background: #f8f9fa; padding: 8px; border-radius: 6px; border: 1px solid #eee;">
                                    <strong class="text-dark">Dampak Material:</strong><br>
                                    <span style="color: #555;">{{ Str::limit($latest->dampak_material ?? 'Tidak ada laporan kerusakan material.', 70) }}</span>
                                </div>

                                <!-- BAGIAN B: KRONOLOGI -->
                                <div class="section-title-popup"><i class="fas fa-clock me-1 text-secondary"></i> B. Kronologi Singkat</div>
                                <div style="font-size: 11px; line-height: 1.5; color: #555; text-align: justify; max-height: 60px; overflow-y: auto; margin-bottom: 15px;">
                                    {{ Str::limit($latest->kronologi ?? 'Menunggu laporan kronologi dari daerah...', 120) }}
                                </div>

                                <!-- TOMBOL AKSI (Desain Baru) -->
                                    <div class="d-flex gap-2 border-top pt-3 mt-2">
                                        <!-- Tombol Detail Laporan (Warna teks dipaksa putih) -->
                                        <a href="{{ route('pengguna.laporan.pdf', $latest->id) }}" class="btn btn-primary btn-sm fw-bold w-100 d-flex align-items-center justify-content-center shadow-sm text-white" style="border-radius: 8px; padding: 8px; color: #ffffff !important;">
                                            <i class="fas fa-file-pdf me-2" style="color: #ffffff !important;"></i> Detail Laporan
                                        </a>
                                        
                                        <!-- Tombol Kembali/Tutup -->
                                        <button onclick="map.closePopup()" class="btn btn-light btn-sm border fw-bold w-100 d-flex align-items-center justify-content-center shadow-sm" style="border-radius: 8px; padding: 8px; color: #555 !important;">
                                            <i class="fas fa-times me-2"></i> Tutup
                                        </button>
                                    </div>
                            </div>
                        </div>
                    `;

                marker.bindPopup(popupContent);
            @endif
        @endforeach
    </script>
</body>
</html>