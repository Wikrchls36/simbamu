<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simbamu - Peta Potensi Bencana</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        
        
        .map-header {
            height: 70px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1000;
        }


        .mdmc-logo-header {
            height: 40px; 
            width: auto;
            object-fit: contain; 
        }

        
        .btn-back-circle {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #333;
            flex-shrink: 0; 
        }
        
        
        #map { height: calc(100vh - 70px); width: 100%; z-index: 1; }

      
        .btn-back { 
            position: absolute; 
            top: 90px; 
            left: 20px; 
            z-index: 1000; 
            border-radius: 50%; 
            width: 45px; 
            height: 45px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 18px; 
            color: #333;
        }
        .filter-box { 
            position: absolute;
            top: 90px;
            right: 20px; 
            z-index: 1000; 
            background: white; 
            padding: 15px; 
            border-radius: 12px; 
            box-shadow: 0 2px 15px rgba(0,0,0,0.15); 
            min-width: 150px; }
        
      
        .info.legend {
            background: white;
            padding: 12px 15px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.15);
            min-width: 250px;
        }
        .legend-title { font-weight: bold; text-align: center; margin-bottom: 5px; font-size: 15px; color: #000; }
        .legend-labels { display: flex; justify-content: space-between; text-align: center; font-size: 14px; margin-bottom: 2px; color: #333; }
        .legend-labels span { flex: 1; }
        .legend-colors { display: flex; height: 18px; border-radius: 4px; overflow: hidden; }
        .legend-colors span { flex: 1; }
        
       
        .leaflet-popup-content { margin: 15px; min-width: 180px;}
    </style>
</head>
<body>

    <div class="map-header d-flex justify-content-between align-items-center px-4">
        
        <div class="header-left" style="width: 220px;">
            <img src="{{ asset('images/logo-mdmc.png') }}" alt="Logo MDMC" style="height: 45px; object-fit: contain;" onerror="this.style.display='none'">
        </div>

        <div class="header-center text-center flex-grow-1">
            <h4 class="fw-bold m-0 text-dark" style="font-size: 1.25rem;">PETA POTENSI BENCANA KALIMANTAN BARAT</h4>
        </div>
        
        <div class="header-right" style="width: 220px;"></div> 
        
    </div>

    <a href="{{ route('peta.index') }}" class="btn btn-light shadow btn-back" title="Kembali ke Menu Peta">
        <i class="fas fa-arrow-left"></i>
    </a>

    <div class="filter-box">
        <h6 class="fw-bold small mb-2 text-uppercase text-muted">Pilih Layer:</h6>
        <div class="form-check mb-1">
            <input class="form-check-input" type="radio" name="f" id="b" onchange="window.location.replace('?filter=banjir')" {{ $filter == 'banjir' ? 'checked' : '' }}>
            <label class="form-check-label small fw-bold" for="b">Potensi Banjir</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="f" id="k" onchange="window.location.replace('?filter=karhutla')" {{ $filter == 'karhutla' ? 'checked' : '' }}>
            <label class="form-check-label small fw-bold" for="k">Potensi Karhutla</label>
        </div>
    </div>

    <div id="map"></div>

   <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Peta
        var map = L.map('map', { zoomControl: false }).setView([-0.2787, 111.4753], 7);
        L.control.zoom({ position: 'bottomleft' }).addTo(map);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© MDMC Kalbar | SIMBAMU'
        }).addTo(map);

       
        var dbData = @json($dataPeta);
        var filter = '{{ $filter ?? "banjir" }}';

        // DAFTAR DAERAH
        const daftarDaerah = {
            '61-01': 'Kabupaten Sambas',
            '61-02': 'Kabupaten Bengkayang',
            '61-03': 'Kabupaten Landak',
            '61-04': 'Kabupaten Mempawah',
            '61-05': 'Kabupaten Sanggau',
            '61-06': 'Kabupaten Ketapang',
            '61-07': 'Kabupaten Sintang',
            '61-08': 'Kabupaten Kapuas Hulu',
            '61-09': 'Kabupaten Sekadau',
            '61-10': 'Kabupaten Melawi',
            '61-11': 'Kabupaten Kayong Utara',
            '61-12': 'Kabupaten Kubu Raya',
            '61-71': 'Kota Pontianak',
            '61-72': 'Kota Singkawang'
        };

        function getDbData(feature) {
            let namaAsli = daftarDaerah[feature.id];
            if(!namaAsli) return null;
            return dbData.find(row => row.kabupaten_kota === namaAsli);
        }

        
        var geojsonLayer;

        // LEGENDA
        var legend = L.control({ position: 'bottomright' });

        legend.onAdd = function (map) {
            var div = L.DomUtil.create('div', 'info legend');
            
            div.innerHTML = `
                <div class="legend-title">Keterangan</div>
                <div class="legend-labels">
                    <span>Rendah</span>
                    <span>Sedang</span>
                    <span>Tinggi</span>
                </div>
                <div class="legend-colors">
                    <span style="background: #198754;"></span>
                    <span style="background: #ffc107;"></span>
                    <span style="background: #dc3545;"></span>
                </div>
            `;
            return div;
        };

        legend.addTo(map);

        
        fetch('/data/kalbar.geojson?v=' + new Date().getTime())
            .then(res => res.json())
            .then(geojson => {
                
                // Masukkan data ke geojsonLayer
                geojsonLayer = L.geoJSON(geojson, {
                    
               
                    style: function(feature) {
                        let item = getDbData(feature);
                        let isFilled = false;
                        let level = 'Kosong';

                        if (item) {
                            
                            if (filter === 'banjir' && item.luas_genangan > 0) {
                                level = item.potensi_banjir; 
                                isFilled = true;
                            } else if (filter === 'karhutla' && item.jumlah_hotspot > 0) {
                                level = item.potensi_karhutla; 
                                isFilled = true;
                            }
                        }

                       
                        let color = 'transparent';  
                        let fillOp = 0;            
                        let colorLine = '#3388ff';  
                        let weightLine = 1.5;      

                        if (isFilled) {
                            if(level === 'Tinggi') color = '#dc3545'; 
                            else if(level === 'Sedang') color = '#ffc107'; 
                            else if(level === 'Rendah') color = '#198754'; 
                            
                            fillOp = 0.7;        
                            colorLine = 'white'; 
                        }

                        return { 
                            fillColor: color, 
                            weight: weightLine, 
                            color: colorLine, 
                            fillOpacity: fillOp 
                        };
                    },

                
                    onEachFeature: function(feature, layer) {
                        let item = getDbData(feature);
                        let namaDaerah = daftarDaerah[feature.id] || feature.properties.kabkot;

                      
                        let isiPopup = "<div class='text-center mb-2'><b class='fs-6 text-primary text-uppercase'>" + namaDaerah + "</b></div>";
                        
                        if (item) {
                           
                            let sumberInfo = item.sumber_data ? item.sumber_data : 'Belum ada referensi';

                            if(filter === 'banjir') {
                              
                                isiPopup += `
                                <div class='p-2 bg-light rounded border border-primary border-opacity-25' style='min-width: 260px;'>
                                    <div class='d-flex justify-content-between mb-1'><span class='text-muted small'>Tingkat Potensi</span><strong class='text-dark'>${item.potensi_banjir}</strong></div>
                                    <div class='d-flex justify-content-between mb-1'><span class='text-muted small'>Luas Genangan</span><strong class='text-dark'>${item.luas_genangan} Ha</strong></div>
                                    <div class='d-flex justify-content-between mb-1'><span class='text-muted small'>Rentang Tahun</span><strong class='text-dark'>${item.tahun_banjir || '-'}</strong></div>
                                    <div class='d-flex justify-content-between mb-2 pb-2 border-bottom'><span class='text-muted small'>Jiwa Terdampak</span><strong class='text-dark'>${item.jiwa_terdampak_banjir} Jiwa</strong></div>
                                    
                                    <div class='text-center mb-1'><b class='small text-dark'>Material Terdampak (Unit)</b></div>
                                    <div class='row text-center g-1 mb-2'>
                                        <div class='col-3'><div class='bg-white border rounded p-1'><small class='d-block text-muted' style='font-size:10px;'>Warga</small><b class='small'>${item.rumah_warga_banjir}</b></div></div>
                                        <div class='col-3'><div class='bg-white border rounded p-1'><small class='d-block text-muted' style='font-size:10px;'>Ibadah</small><b class='small'>${item.rumah_ibadah_banjir}</b></div></div>
                                        <div class='col-3'><div class='bg-white border rounded p-1'><small class='d-block text-muted' style='font-size:10px;'>Faskes</small><b class='small'>${item.faskes_banjir}</b></div></div>
                                        <div class='col-3'><div class='bg-white border rounded p-1'><small class='d-block text-muted' style='font-size:10px;'>Fasdik</small><b class='small'>${item.fasdik_banjir}</b></div></div>
                                    </div>
                                    
                                    <div class='text-center mt-2 pt-1 border-top'><small class='text-muted' style='font-size:10px;'>Sumber: ${sumberInfo}</small></div>
                                </div>`;
                            } else {
                                
                                isiPopup += `
                                <div class='p-2 bg-light rounded border border-danger border-opacity-25' style='min-width: 250px;'>
                                    <div class='d-flex justify-content-between mb-1'><span class='text-muted small'>Tingkat Potensi</span><strong class='text-dark'>${item.potensi_karhutla}</strong></div>
                                    <div class='d-flex justify-content-between mb-1'><span class='text-muted small'>Jumlah Hotspot</span><strong class='text-dark'>${item.jumlah_hotspot} Titik</strong></div>
                                    <div class='d-flex justify-content-between mb-1'><span class='text-muted small'>Rentang Tahun</span><strong class='text-dark'>${item.tahun_karhutla || '-'}</strong></div>
                                    <div class='d-flex justify-content-between mb-1'><span class='text-muted small'>Jiwa Terdampak</span><strong class='text-dark'>${item.jiwa_terdampak_karhutla} Jiwa</strong></div>
                                    <div class='d-flex justify-content-between mb-2'><span class='text-muted small'>Luas Terbakar</span><strong class='text-dark'>${item.luas_terbakar_karhutla} Ha</strong></div>
                                    
                                    <div class='text-center mt-2 pt-1 border-top'><small class='text-muted' style='font-size:10px;'>Sumber: ${sumberInfo}</small></div>
                                </div>`;
                            }
                        } else {
                            isiPopup += "<div class='alert alert-secondary py-1 px-2 m-0 text-center small'>Data Belum Tersedia</div>";
                        }

                        layer.bindPopup(isiPopup);

                      
                        layer.on({
                            mouseover: function(e) {
                                var l = e.target;
                                l.setStyle({ fillOpacity: 0.8, weight: 2.5, color: '#333' });
                            },
                            mouseout: function(e) {
                                geojsonLayer.resetStyle(e.target);
                            }
                        });
                    }
                }).addTo(map); 

            });
    </script>
</body>
</html>