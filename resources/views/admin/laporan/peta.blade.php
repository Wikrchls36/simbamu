<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peta Sebaran Bencana - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; margin: 0; padding: 0; overflow: hidden; }

        .header-map { height: 70px; background-color: #ffffff; display: flex; align-items: center; justify-content: space-between; padding: 0 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: relative; z-index: 1001; }
        .header-map .title-center { position: absolute; left: 50%; transform: translateX(-50%); font-weight: 700; font-size: 1.2rem; letter-spacing: 0.5px; color: #333; }
        #mapUser { height: calc(100vh - 70px); width: 100%; z-index: 1; }

       
        .btn-back-floating { position: absolute; top: 90px; left: 20px; width: 45px; height: 45px; background-color: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #333; text-decoration: none; box-shadow: 0 4px 15px rgba(0,0,0,0.2); z-index: 1000; transition: all 0.3s ease; font-size: 1.1rem; border: 2px solid transparent;}
        .btn-back-floating:hover { background-color: #f8f9fa; color: #0047ba; transform: scale(1.05); border-color: #0047ba;}

        
        .custom-marker { background: #0d6efd; color: white; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; border: 3px solid white; box-shadow: 0 4px 10px rgba(0,0,0,0.3); font-size: 20px; transition: transform 0.2s;}
        .custom-marker:hover { transform: scale(1.15); }
        .custom-marker.karhutla { background: #dc3545; }

      
        .custom-marker-kecil { width: 28px; height: 28px; font-size: 12px; border-width: 2px; }

        
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

    <a href="{{ route('admin.laporan.index') }}" class="btn-back-floating" title="Kembali ke Log"><i class="fas fa-arrow-left"></i></a>
    
    <div id="mapUser"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <script>
        // PETA
        var defaultCenter = [-0.0227, 109.3425];
        var map = L.map('mapUser', { zoomControl: false }).setView(defaultCenter, 7);
        L.control.zoom({ position: 'bottomleft' }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© MDMC Kalbar | SIMBAMU'
        }).addTo(map);

        
        // Layer Utama 
        var layerUtama = L.featureGroup().addTo(map); 
        // Layer Kecil 
        var layerKecil = L.featureGroup(); 

        
        var zoomThreshold = 11; 

        // UI Zoom
        map.on('zoomend', function() {
            if (map.getZoom() >= zoomThreshold) {
                if(map.hasLayer(layerUtama)) map.removeLayer(layerUtama);
                if(!map.hasLayer(layerKecil)) map.addLayer(layerKecil);
            } else {
                if(map.hasLayer(layerKecil)) map.removeLayer(layerKecil);
                if(!map.hasLayer(layerUtama)) map.addLayer(layerUtama);
            }
        });

        
        function fokusSebaran(lat, long) {
            map.closePopup();
            
            map.flyTo([lat, long], 13, { animate: true, duration: 1.5 });
        }

        @foreach($semuaLaporan as $item)
            @php 
                
                $latest = $item->updates->sortBy('id')->last(); 
                $latPusat = $item->latitude ?? ($item->user->latitude ?? -0.0227);
                $lngPusat = $item->longitude ?? ($item->user->longitude ?? 109.3425);
            @endphp
            
            @if($latest)
                
                //  MARKUP UTAMA 
                var isBanjir_{{ $item->id }} = '{{ $item->jenis_bencana }}' === 'Banjir';
                var iconHtml_{{ $item->id }} = isBanjir_{{ $item->id }} ? '<i class="fas fa-water"></i>' : '<i class="fas fa-fire"></i>';
                var bgClass_{{ $item->id }} = isBanjir_{{ $item->id }} ? '' : 'karhutla';

                var customIconUtama_{{ $item->id }} = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="custom-marker ${bgClass_{{ $item->id }}} shadow">${iconHtml_{{ $item->id }}}</div>`,
                    iconSize: [45, 45], iconAnchor: [22.5, 22.5], popupAnchor: [0, -20] 
                });

                var markerUtama_{{ $item->id }} = L.marker([{{ $latPusat }}, {{ $lngPusat }}], {icon: customIconUtama_{{ $item->id }}});
                
                var popupContentUtama_{{ $item->id }} = `
                    <div class="popup-custom">
                        <div class="popup-header">
                            <img src="{{ asset('images/logo-mdmc.png') }}" height="26" onerror="this.style.display='none'">
                            <strong style="font-size: 15px;">SITUATION REPORT</strong>
                        </div>
                        <div class="popup-body">
                            
                            <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                                <div>
                                    <strong class="text-muted text-uppercase" style="font-size: 10px;">Pelapor</strong><br>
                                    <span class="fw-bold text-dark" style="font-size: 12px;">{{ addslashes($item->user->name ?? 'MDMC Daerah') }}</span>
                                </div>
                                <div class="text-end">
                                    <strong class="text-muted text-uppercase" style="font-size: 10px;">Update Terakhir</strong><br>
                                    <span class="fw-bold text-dark" style="font-size: 12px;">{{ \Carbon\Carbon::parse($latest->tanggal_sitrep)->translatedFormat('d M Y') }}</span>
                                </div>
                            </div>

                            <div class="mb-2 mt-2">
                                <span style="font-size: 11px;" class="text-muted text-uppercase fw-bold">Bencana:</span> 
                                <span class="text-primary fw-bold" style="font-size: 13px;">{{ $item->jenis_bencana }}</span>
                            </div>

                            <button onclick="fokusSebaran({{ $latPusat }}, {{ $lngPusat }})" class="btn btn-warning btn-sm fw-bold w-100 shadow-sm mb-3 d-flex align-items-center justify-content-center text-dark" style="background-color: #ffc107; border: none; border-radius: 8px; padding: 8px;">
                                <i class="fas fa-search-location me-2" style="font-size: 14px;"></i> Lihat Sebaran Titik Bencana
                            </button>

                            <div class="section-title-popup mt-0"><i class="fas fa-users me-1 text-secondary"></i> Dampak Korban</div>
                            <div class="row g-2 mb-2">
                                <div class="col-4"><div class="stat-box"><strong>{{ $latest->dampak_meninggal ?? 0 }}</strong><small class="text-muted" style="font-size: 10px;">Meninggal</small></div></div>
                                <div class="col-4"><div class="stat-box"><strong>{{ $latest->dampak_luka ?? 0 }}</strong><small class="text-muted" style="font-size: 10px;">Luka</small></div></div>
                                <div class="col-4"><div class="stat-box"><strong>{{ $latest->dampak_hilang ?? 0 }}</strong><small class="text-muted" style="font-size: 10px;">Hilang</small></div></div>
                                <div class="col-6"><div class="stat-box"><strong>{{ $latest->dampak_pengungsi ?? 0 }}</strong><small class="text-muted" style="font-size: 10px;">Pengungsi</small></div></div>
                                <div class="col-6"><div class="stat-box"><strong>{{ $latest->dampak_terdampak ?? 0 }}</strong><small class="text-muted" style="font-size: 10px;">Terdampak</small></div></div>
                            </div>
                            
                            <div class="d-flex gap-2 border-top pt-3 mt-3">
                                <a href="{{ route('admin.laporan.show', $item->id) }}" class="btn btn-primary btn-sm fw-bold w-100 d-flex align-items-center justify-content-center shadow-sm text-white" style="border-radius: 8px; padding: 8px;">
                                    <i class="fas fa-file-pdf me-2"></i> Laporan Lengkap
                                </a>
                                <button onclick="map.closePopup()" class="btn btn-light btn-sm border fw-bold w-100 d-flex align-items-center justify-content-center shadow-sm" style="border-radius: 8px; padding: 8px; color: #555;">
                                    <i class="fas fa-times me-2"></i> Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                markerUtama_{{ $item->id }}.bindPopup(popupContentUtama_{{ $item->id }});
                layerUtama.addLayer(markerUtama_{{ $item->id }}); // Masukkan ke Layer Utama

                // MARKUP KECIL
                @php
                    
                    $wk_lat = is_string($latest->wk_latitude ?? null) ? json_decode($latest->wk_latitude, true) : ($latest->wk_latitude ?? []);
                    $wk_lng = is_string($latest->wk_longitude ?? null) ? json_decode($latest->wk_longitude, true) : ($latest->wk_longitude ?? []);
                    $wk_lok = is_string($latest->wk_lokasi ?? null) ? json_decode($latest->wk_lokasi, true) : ($latest->wk_lokasi ?? []);
                @endphp

                @if(!empty($wk_lat) && is_array($wk_lat))
                    @foreach($wk_lat as $idx => $latDetail)
                        @if(!empty($latDetail) && !empty($wk_lng[$idx]))
                            var iconHtmlKecil_{{ $item->id }}_{{ $idx }} = isBanjir_{{ $item->id }} ? '<i class="fas fa-tint"></i>' : '<i class="fas fa-fire-alt"></i>';
                            
                          
                            var customIconKecil_{{ $item->id }}_{{ $idx }} = L.divIcon({
                                className: 'custom-div-icon',
                                html: `<div class="custom-marker ${bgClass_{{ $item->id }}} custom-marker-kecil shadow-sm">${iconHtmlKecil_{{ $item->id }}_{{ $idx }}}</div>`,
                                iconSize: [28, 28], iconAnchor: [14, 14], popupAnchor: [0, -12]
                            });

                            var markerKecil_{{ $item->id }}_{{ $idx }} = L.marker([{{ $latDetail }}, {{ $wk_lng[$idx] }}], {icon: customIconKecil_{{ $item->id }}_{{ $idx }}});
                            
                            var namaLokasi = '{{ addslashes($wk_lok[$idx] ?? "Titik Kejadian Spesifik") }}';
                            var warnaBadge = '{{ $item->jenis_bencana }}' === 'Banjir' ? 'bg-primary' : 'bg-danger';
                            
                            
                            var popupKecil = `
                                <div class="p-2 font-monospace" style="min-width: 170px; text-align: center; font-family: 'Poppins', sans-serif !important;">
                                    <h6 class="fw-bold mb-2 pb-2 border-bottom" style="color: #333; font-size:14px;">${namaLokasi}</h6>
                                    <span class="badge ${warnaBadge} w-100 mb-2 py-1 shadow-sm" style="font-size:11px;">{{ $item->jenis_bencana }}</span>
                                    
                                    <div style="font-size: 11px; background: #f4f7fa; padding: 8px; border-radius: 6px; color: #444; border: 1px solid #ddd; text-align: left;">
                                        <div class="mb-1"><i class="fas fa-map-marker-alt text-danger me-1"></i> <strong>Lat:</strong> <span class="float-end">{{ $latDetail }}</span></div>
                                        <div><i class="fas fa-map-marker-alt text-danger me-1" style="visibility: hidden;"></i> <strong>Lng:</strong> <span class="float-end">{{ $wk_lng[$idx] }}</span></div>
                                    </div>
                                </div>
                            `;
                            
                            
                            markerKecil_{{ $item->id }}_{{ $idx }}.bindPopup(popupKecil, { maxWidth: 220, minWidth: 170, className: 'small-popup' });
                            layerKecil.addLayer(markerKecil_{{ $item->id }}_{{ $idx }}); 
                        @endif
                    @endforeach
                @endif
                
            @endif
        @endforeach
    </script>
</body>
</html>