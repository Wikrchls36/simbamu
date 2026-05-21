<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran Bencana - MDMC Daerah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; margin: 0; padding: 0; overflow: hidden; }

        .header-map { height: 70px; background-color: #ffffff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: relative; z-index: 1001; }
        .header-map .title-center { position: absolute; left: 50%; transform: translateX(-50%); font-weight: 700; font-size: 1.2rem; letter-spacing: 0.5px; color: #333; }
        #mapUser { height: calc(100vh - 70px); width: 100%; z-index: 1; }

        .btn-back-floating { position: absolute; top: 90px; left: 20px; width: 45px; height: 45px; background-color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #333; text-decoration: none; box-shadow: 0 4px 10px rgba(0,0,0,0.15); z-index: 1000; transition: all 0.3s ease; font-size: 1.1rem; }
        .btn-back-floating:hover { background-color: #f8f9fa; color: #000; transform: scale(1.05); }

        
        .custom-marker { background: #0d6efd; color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.3); font-size: 18px; transition: transform 0.2s;}
        .custom-marker:hover { transform: scale(1.1); }
        .custom-marker.karhutla { background: #dc3545; }

        
        .leaflet-popup-content-wrapper { border-radius: 12px; padding: 0; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.15); }
        .leaflet-popup-content { margin: 0; width: 340px !important; }
        .popup-custom { font-family: 'Poppins', sans-serif; font-size: 12px; color: #333; }
        .popup-header { background: #f8f9fa; padding: 12px 15px; border-bottom: 2px solid #eee; display: flex; align-items: center; gap: 10px; }
        .popup-body { padding: 15px; background: #fff; }
        .section-title-popup { background: #e9ecef; font-weight: bold; padding: 6px 10px; margin: 12px 0 8px 0; border-radius: 6px; color: #444; font-size: 12px;}
        .stat-box { border: 1px solid #dee2e6; border-radius: 8px; padding: 8px 5px; text-align: center; background: #fff; }
        .stat-box strong { font-size: 16px; color: #0d6efd; display: block; line-height: 1.2; }
        .leaflet-control-zoom { border: none !important; box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important; }
    </style>
</head>
<body>

    <div class="header-map">
        <img src="{{ asset('images/logo-mdmc.png') }}" height="40" onerror="this.style.display='none'">
        <div class="title-center text-uppercase">PETA PENYEBARAN BENCANA</div>
        <div style="width: 40px;"></div>
    </div>

    <a href="{{ route('pengguna.laporan.index') }}" class="btn-back-floating" title="Kembali"><i class="fas fa-arrow-left"></i></a>

    <div id="mapUser"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('mapUser', { zoomControl: false }).setView([-0.0227, 109.3425], 7);
        L.control.zoom({ position: 'bottomleft' }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© MDMC Kalbar | SIMBAMU'
        }).addTo(map);

        @foreach($semuaLaporan as $item)
            @php 
                $latest = $item->updates->last(); 
                
                $lat = $item->latitude ?? ($item->user->latitude ?? -0.0227);
                $lng = $item->longitude ?? ($item->user->longitude ?? 109.3425);
            @endphp
            
            @if($latest)
                
                
                var isBanjir = '{{ $item->jenis_bencana }}' === 'Banjir';
                var iconHtml = isBanjir ? '<i class="fas fa-water"></i>' : '<i class="fas fa-fire"></i>';
                var bgClass = isBanjir ? '' : 'karhutla';

                var customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="custom-marker ${bgClass}">${iconHtml}</div>`,
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                    popupAnchor: [0, -15] 
                });

                var marker = L.marker([{{ $lat }}, {{ $lng }}], {icon: customIcon}).addTo(map);
                
            
                
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
                                    <a href="/pengguna/laporan/{{ $item->id }}/detail" class="btn btn-primary btn-sm fw-bold w-100 d-flex align-items-center justify-content-center shadow-sm text-white" style="border-radius: 8px; padding: 8px; color: #ffffff !important;">
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