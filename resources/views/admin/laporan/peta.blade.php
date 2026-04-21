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
        
        /* Header Putih Bersih (Seperti Gambar 2) */
        .map-header {
            background-color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1000;
        }

        /* Container Peta */
        .map-container {
            position: relative;
            width: 100%;
            height: calc(100vh - 75px); /* Sesuaikan tinggi dengan header */
        }

        #map {
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* Tombol Kembali Bulat (Floating) */
        .btn-back-floating {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 1000; /* Berada di atas peta */
            width: 50px;
            height: 50px;
            background-color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            color: #333;
            text-decoration: none;
            font-size: 1.4rem;
            transition: all 0.3s ease;
        }

        .btn-back-floating:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 12px rgba(0,0,0,0.3);
            color: #000;
        }

        /* Custom Marker Styles */
        .custom-marker {
            display: flex; justify-content: center; align-items: center;
            width: 40px; height: 40px; border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3); color: white; font-size: 1.2rem;
            border: 3px solid white;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .custom-marker:hover { transform: scale(1.1); }
        .marker-banjir { background-color: #1aa4f6; } 
        .marker-karhutla { background-color: #ff4757; } 

        /* Sitrep Modal Styling */
        .sitrep-table th { background-color: #8c9eff !important; color: white; text-align: center; }
        .sitrep-table td { background-color: #e8eaf6 !important; text-align: center; }
        .section-title { background-color: #e0e0e0; padding: 5px 10px; font-weight: bold; margin-bottom: 10px; }
        
        /* Modifikasi Posisi Zoom Control Leaflet (Agar tidak menabrak tombol kembali) */
        .leaflet-control-zoom {
            border: none !important;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
        }
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

    <div class="modal fade" id="sitrepModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-bottom-0 pb-0" style="border-top: 5px solid #1aa4f6;">
                    <div class="d-flex justify-content-between w-100 align-items-center">
                        <img src="{{ asset('images/logo-mdmc.png') }}" height="40" alt="MDMC" onerror="this.style.display='none'">
                        <h5 class="fw-bold m-0">SITUATION REPORT</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body p-4">
                    
                    <div class="row mb-3">
                        <div class="col-6">
                            <small class="fw-bold d-block">Pelapor</small>
                            <span id="sitrepPelapor">MDMC Sintang</span>
                        </div>
                        <div class="col-6 text-end">
                            <small class="fw-bold d-block">Tanggal Keluar SITREP</small>
                            <span id="sitrepTanggal">-</span>
                        </div>
                    </div>

                    <div class="section-title">Informasi Kunci</div>
                    
                    <div class="mb-3">
                        <small class="fw-bold d-block">Jenis Bencana</small>
                        <span id="sitrepJenis" class="text-danger fw-bold">Banjir</span>
                    </div>

                    <small class="fw-bold d-block mb-2">Waktu Kejadian</small>
                    <table class="table table-bordered sitrep-table mb-4">
                        <thead>
                            <tr>
                                <th>No</th><th>Waktu Kejadian</th><th>Kejadian</th><th>Lokasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td><td id="sitrepWaktu">-</td><td id="sitrepKejadianTabel">-</td><td>Menunggu Data Daerah</td>
                            </tr>
                        </tbody>
                    </table>

                    <small class="fw-bold d-block mb-2">Dampak</small>
                    <table class="table table-bordered sitrep-table mb-4" style="max-width: 500px; margin: 0 auto;">
                        <thead>
                            <tr><th>No</th><th>Dampak</th><th>Jumlah</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>1</td><td>Meninggal</td><td>-</td></tr>
                            <tr><td>2</td><td>Luka-Luka</td><td>-</td></tr>
                            <tr><td>3</td><td>Hilang</td><td>-</td></tr>
                            <tr><td>4</td><td>Pengungsi</td><td>-</td></tr>
                            <tr><td>5</td><td>Terdampak</td><td>-</td></tr>
                        </tbody>
                    </table>

                    <div class="section-title mt-4">Situasi Terkini</div>
                    <div class="border rounded p-3 bg-light">
                        <p class="m-0 text-muted" style="font-style: italic;">
                            Catatan: Detail data dampak, lokasi pasti, dan kronologi akan tersedia setelah fitur pengisian form Sitrep oleh MDMC Daerah selesai dikembangkan.
                        </p>
                    </div>

                </div>
                <div class="modal-footer border-top-0 d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-primary fw-bold px-4">Cek Detail</button>
                    <button type="button" class="btn text-dark fw-bold px-4" style="background-color: #e0e0e0;" data-bs-dismiss="modal">Kembali</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        // 1. Inisialisasi Peta
        var map = L.map('map', {
            zoomControl: false 
        }).setView([-0.27878, 111.4752], 7);

        // 2. Tambahkan Zoom Control di Kiri Bawah (bottomleft)
        L.control.zoom({
            position: 'bottomleft'
        }).addTo(map);

        // Tambahkan Tile Layer (Peta Dasar)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; MDMC Kalbar | SIMBAMU'
        }).addTo(map);

        // Data Laporan dari Database Laravel
        var laporans = @json($laporans);
        var sitrepModal = new bootstrap.Modal(document.getElementById('sitrepModal'));

        // Looping data laporan dan letakkan marker di peta
        laporans.forEach(function(laporan) {
            
            var namaUser = laporan.user ? laporan.user.name : 'User Tidak Diketahui';
            
            // Ambil titik koordinat asli dari database
            var lat = (laporan.user && laporan.user.latitude) ? parseFloat(laporan.user.latitude) : -0.27878;
            var lng = (laporan.user && laporan.user.longitude) ? parseFloat(laporan.user.longitude) : 111.4752;
            var latLng = [lat, lng];

            // Tentukan Ikon
            var iconHtml = '';
            var markerClass = '';
            if(laporan.jenis_bencana === 'Banjir') {
                iconHtml = '<i class="fas fa-water"></i>';
                markerClass = 'marker-banjir';
            } else {
                iconHtml = '<i class="fas fa-fire"></i>';
                markerClass = 'marker-karhutla';
            }

            var customIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="custom-marker ${markerClass}">${iconHtml}</div>`,
                iconSize: [40, 40],
                iconAnchor: [20, 20]
            });

            // Tambahkan Marker ke peta
            var marker = L.marker(latLng, {icon: customIcon}).addTo(map);

            // Event saat marker diklik: Isi modal
            marker.on('click', function() {
                var dateObj = new Date(laporan.created_at);
                var formattedDate = dateObj.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });

                document.getElementById('sitrepPelapor').innerText = namaUser;
                document.getElementById('sitrepTanggal').innerText = formattedDate;
                document.getElementById('sitrepWaktu').innerText = formattedDate;
                
                var elJenis = document.getElementById('sitrepJenis');
                elJenis.innerText = laporan.jenis_bencana;
                elJenis.className = laporan.jenis_bencana === 'Banjir' ? 'text-primary fw-bold' : 'text-danger fw-bold';
                
                document.getElementById('sitrepKejadianTabel').innerText = laporan.jenis_bencana;

                sitrepModal.show();
            });
        });
    </script>
</body>
</html>