<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan SITREP - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body { background-color: #f4f7fa; font-family: 'Poppins', sans-serif; }
        
        .section-header { 
            background: linear-gradient(90deg, #0047ba, #1aa4f6); 
            color: white; 
            padding: 12px 20px; 
            font-weight: 600; 
            border-radius: 10px; 
            margin-top: 45px; 
            margin-bottom: 25px; 
            text-transform: uppercase; 
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(0, 71, 186, 0.15);
        }
        
        .card { border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.03); }
        .form-label { font-weight: 600; font-size: 0.85rem; color: #444; margin-top: 10px; }
        .table th { font-size: 0.8rem; background-color: #f8f9fa; text-align: center; border-top: none; vertical-align: middle; color: #555; text-transform: uppercase; }
        .table td { vertical-align: middle; }
        .btn-add-row { background-color: #e9ecef; color: #0047ba; border: none; font-weight: bold; border-radius: 8px; transition: 0.3s; }
        .btn-add-row:hover { background-color: #0047ba; color: white; }
        .form-control, .form-select { border-radius: 8px; padding: 10px 15px; font-size: 0.9rem; border-color: #e2e8f0; }
        .form-control:focus, .form-select:focus { border-color: #1aa4f6; box-shadow: 0 0 0 0.25rem rgba(26, 164, 246, 0.15); }
        .input-koordinat { font-size: 0.75rem; padding: 0.25rem; text-align: center; background-color: #fff; cursor: not-allowed; } 
        #mapPicker { border-radius: 12px; overflow: hidden; border: 2px solid #e2e8f0; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card p-4 p-md-5">
                
                <div class="d-flex justify-content-between align-items-start mb-4 border-bottom pb-4">
                    <div class="text-center flex-grow-1 px-3">
                        <span class="badge bg-danger rounded-pill mb-2 px-3 py-2 fw-medium"><i class="fas fa-file-signature me-1"></i> Form SitRep</span>
                        <h2 class="fw-bold text-dark mb-1">LAPORAN SITUASI (SITREP) BENCANA</h2>
                        <h6 class="text-secondary fw-normal">Muhammadiyah Disaster Management Center Wilayah Kalimantan Barat</h6>
                    </div>
                </div>

                <form action="{{ route('pengguna.laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="section-header"><i class="fas fa-info-circle me-2"></i> A. Informasi Kunci</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Jenis Bencana <span class="text-danger">*</span></label>
                            <select name="jenis_bencana" class="form-select" required>
                                <option value="" disabled selected>Pilih Bencana</option>
                                <option value="Banjir">Banjir</option>
                                <option value="Karhutla">Karhutla</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Keluar SitRep <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_sitrep" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        
                        <input type="hidden" name="latitude" id="pusat_lat" value="{{ Auth::user()->latitude ?? '' }}">
                        <input type="hidden" name="longitude" id="pusat_lng" value="{{ Auth::user()->longitude ?? '' }}">
                    </div>

                    <p class="fw-bold small mb-2 text-dark"><i class="far fa-calendar-alt text-primary me-1"></i>Waktu Kejadian</p>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle" id="tb_waktu">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Kejadian</th>
                                    <th>Lokasi</th>
                                    <th width="220">Koordinat Peta</th>
                                    <th width="50">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="wk_waktu[]" class="form-control" placeholder="Jam / Tgl"></td>
                                    <td><input type="text" name="wk_kejadian[]" class="form-control" placeholder="Contoh: Air meluap"></td>
                                    <td><input type="text" name="wk_lokasi[]" class="form-control" placeholder="Nama desa/jalan"></td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="wk_latitude[]" class="form-control input-koordinat" placeholder="Lat" readonly>
                                            <input type="text" name="wk_longitude[]" class="form-control input-koordinat" placeholder="Lng" readonly>
                                            <button class="btn btn-primary" type="button" onclick="bukaPeta(this)" title="Pilih Titik di Peta"><i class="fas fa-map-marker-alt"></i></button>
                                        </div>
                                    </td>
                                    <td class="text-center"><button type="button" class="btn btn-add-row btn-sm w-100" onclick="addRow('tb_waktu', 'waktu')"><i class="fas fa-plus"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="fw-bold small mb-2 text-dark"><i class="fas fa-house-damage text-danger me-1"></i>Dampak</p>
                    <div class="row g-2 mb-4">
                        <div class="col"><label class="form-label small text-center w-100">Meninggal</label><input type="number" name="dampak_meninggal" class="form-control text-center" value="0" min="0"></div>
                        <div class="col"><label class="form-label small text-center w-100">Luka-luka</label><input type="number" name="dampak_luka" class="form-control text-center" value="0" min="0"></div>
                        <div class="col"><label class="form-label small text-center w-100">Hilang</label><input type="number" name="dampak_hilang" class="form-control text-center" value="0" min="0"></div>
                        <div class="col"><label class="form-label small text-center w-100">Pengungsi</label><input type="number" name="dampak_pengungsi" class="form-control text-center" value="0" min="0"></div>
                        <div class="col"><label class="form-label small text-center w-100">Terdampak</label><input type="number" name="dampak_terdampak" class="form-control text-center" value="0" min="0"></div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-12"><label class="form-label">Dampak Material</label><textarea name="dampak_material" class="form-control" rows="2" placeholder="Sebutkan rincian kerusakan material..."></textarea></div>
                        <div class="col-md-6"><label class="form-label">Lokasi Poskor Muhammadiyah</label><textarea name="lokasi_poskor" class="form-control" rows="2"></textarea></div>
                        <div class="col-md-6"><label class="form-label">Lokasi Pos Pelayanan Muhammadiyah (Opsional)</label><textarea name="lokasi_pos_pelayanan" class="form-control" rows="2"></textarea></div>
                    </div>

                    <div class="section-header"><i class="fas fa-history me-2"></i> B. Kronologi Kejadian</div>
                    <textarea name="kronologi" class="form-control" rows="4" placeholder="Ceritakan awal mula kejadian bencana secara runtut..." required></textarea>

                    <div class="section-header"><i class="fas fa-eye me-2"></i> C. Situasi Terkini</div>
                    <textarea name="situasi_terkini" class="form-control" rows="3" placeholder="Bagaimana kondisi terkini di lapangan saat laporan ini dibuat?"></textarea>

                    <div class="section-header"><i class="fas fa-hands-helping me-2"></i> D. Respon Muhammadiyah</div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tb_respon">
                            <thead>
                                <tr><th>Kluster</th><th>Lokasi</th><th>Penerima Manfaat / Keterangan</th><th width="50">Aksi</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="resp_kluster[]" class="form-control" placeholder="Misal: Dapur Umum / Medis"></td>
                                    <td><input type="text" name="resp_lokasi[]" class="form-control"></td>
                                    <td><input type="text" name="resp_keterangan[]" class="form-control"></td>
                                    <td class="text-center"><button type="button" class="btn btn-add-row btn-sm w-100" onclick="addRow('tb_respon', 'respon')"><i class="fas fa-plus"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="section-header"><i class="fas fa-users me-2"></i> E. Penerima Manfaat</div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tb_manfaat">
                            <thead>
                                <tr><th>Kegiatan</th><th>Tanggal Kegiatan</th><th>Jumlah Penerima Manfaat</th><th width="50">Aksi</th></tr>
                            </thead>
                            
                            <tbody>
                                <tr>
                                    <td><input type="text" name="pm_kegiatan[]" class="form-control" placeholder="Misal: Distribusi Makanan"></td>
                                    <td><input type="date" name="pm_tanggal[]" class="form-control"></td>
                                    <td><input type="text" name="pm_jumlah[]" class="form-control"></td>
                                    <td class="text-center"><button type="button" class="btn btn-add-row btn-sm w-100" onclick="addRow('tb_manfaat', 'manfaat')"><i class="fas fa-plus"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="section-header"><i class="fas fa-hard-hat me-2"></i> F. Tim Respon MDMC </div>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle" id="tb_tim">
                            <thead>
                                <tr><th>Kluster</th><th>Total</th><th>Pulang</th><th>Bertugas</th><th width="50">Aksi</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="tim_kluster[]" class="form-control" placeholder="Misal: Tim Evakuasi"></td>
                                    <td><input type="number" name="tim_total[]" class="form-control text-center" value="0" min="0"></td>
                                    <td><input type="number" name="tim_pulang[]" class="form-control text-center" value="0" min="0"></td>
                                    <td><input type="number" name="tim_bertugas[]" class="form-control text-center" value="0" min="0"></td>
                                    <td class="text-center"><button type="button" class="btn btn-add-row btn-sm w-100" onclick="addRow('tb_tim', 'tim')"><i class="fas fa-plus"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row g-3 mb-3 bg-light p-3 rounded-3 border">
                        <div class="col-md-4"><label class="form-label mt-0">Total</label><input type="number" name="tim_total_semua" class="form-control fw-bold text-primary" value="0" min="0"></div>
                        <div class="col-md-4"><label class="form-label mt-0">Laki-Laki</label><input type="number" name="tim_laki" class="form-control" value="0" min="0"></div>
                        <div class="col-md-4"><label class="form-label mt-0">Perempuan</label><input type="number" name="tim_perempuan" class="form-control" value="0" min="0"></div>
                        <div class="col-md-12"><label class="form-label">Asal Instansi</label><textarea name="asal_instansi" class="form-control" rows="2" placeholder="Sebutkan dari mana saja relawan berasal..."></textarea></div>
                    </div>

                    <div class="section-header"><i class="fas fa-box-open me-2"></i> G. Kebutuhan</div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tb_kebutuhan">
                            <thead>
                                <tr><th>Kebutuhan</th><th>Jumlah Kebutuhan</th><th width="50">Aksi</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="keb_item[]" class="form-control" placeholder="Misal: Selimut "></td>
                                    <td><input type="text" name="keb_jumlah[]" class="form-control" placeholder="Misal: 100 Buah "></td>
                                    <td class="text-center"><button type="button" class="btn btn-add-row btn-sm w-100" onclick="addRow('tb_kebutuhan', 'kebutuhan')"><i class="fas fa-plus"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="section-header"><i class="fas fa-bullhorn me-2"></i> H. Sumber Informasi</div>
                    <textarea name="sumber_informasi" class="form-control" rows="2" placeholder="Dari mana data ini didapat? (Misal: Assesment Lapangan, BPBD, dll)"></textarea>

                    <div class="section-header"><i class="fas fa-address-book me-2"></i> I. Contact Person </div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tb_kontak">
                            <thead>
                                <tr><th>Nama </th><th>No Telepon / HP </th><th width="50">Aksi</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="cp_nama[]" class="form-control"></td>
                                    <td><input type="text" name="cp_nohp[]" class="form-control"></td>
                                    <td class="text-center"><button type="button" class="btn btn-add-row btn-sm w-100" onclick="addRow('tb_kontak', 'kontak')"><i class="fas fa-plus"></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="section-header"><i class="fas fa-wallet me-2"></i> J. Rekening Penggalangan Dana</div>
                    <textarea name="rekening_donasi" class="form-control" rows="3" placeholder="Contoh:&#10;1. BSI - Lazismu  (12345678)&#10;2. BSI - MDMC Daerah (87654321)"></textarea>

                    <div class="section-header"><i class="fas fa-flag-checkered me-2"></i> K. Penutup </div>
                    <div class="alert alert-secondary border p-4 mb-4" style="background-color: #f8f9fa;">
                        <p class="mb-4 text-dark fw-medium" style="line-height: 1.8;">Demikian laporan ini kami buat sebagai sumber informasi dan diharapkan dapat menjadi pertimbangan dalam pengambilan keputusan.</p>
                        
                        <div class="row g-3 mb-4 border-top border-secondary pt-3">
                            <div class="col-md-6">
                                <label class="form-label text-dark">Lokasi Dikeluarkan SitRep <span class="text-danger">*</span></label>
                                <input type="text" name="penutup_lokasi" class="form-control" placeholder="Contoh: Pontianak" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-dark">Nama Tim  <span class="text-danger">*</span></label>
                                <input type="text" name="penutup_nama_tim" class="form-control" placeholder="Contoh: Tim Assesment MDMC Kubu Raya" required>
                            </div>
                        </div>

                        <hr class="border-secondary my-4 opacity-25">
                        
                        <div class="col-md-12 bg-white p-4 rounded-3 border">
                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-paperclip me-2 text-primary"></i>LAMPIRAN (FOTO AKTIVITAS dan LAMPIRAN LAPORAN) </h6>
                            <label class="form-label text-muted d-block mb-3" style="font-size: 0.8rem;">Unggah foto-foto aktivitas respon atau keadaan (Bisa memilih lebih dari 1 file sekaligus).</label>
                            <input type="file" name="foto_dokumentasi[]" multiple class="form-control form-control-lg shadow-sm" accept="image/*,.pdf">
                        </div>
                    </div>

                    <div class="d-flex gap-3 pt-3 mt-5 border-top">
                        <a href="{{ route('pengguna.laporan.index') }}" class="btn btn-light btn-lg fw-bold rounded-pill shadow-sm border px-5 text-muted hover-dark">Kembali</a>
                        <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill shadow flex-grow-1" style="background: linear-gradient(90deg, #0047ba, #1aa4f6); border: none;">
                            <i class="fas fa-paper-plane me-2"></i> KIRIM LAPORAN SEKARANG
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPeta" tabindex="-1" aria-labelledby="modalPetaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-light border-0 px-4 py-3">
                <h5 class="modal-title fw-bold text-primary" id="modalPetaLabel"><i class="fas fa-map-marked-alt me-2"></i>Tandai Titik Lokasi Bencana</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 position-relative">
                <div id="mapPicker" style="height: 480px; width: 100%;"></div>
                
                <div class="position-absolute bottom-0 start-50 translate-middle-x mb-4 w-75" style="z-index: 1000;">
                    <div class="card shadow-lg border-0 rounded-pill px-4 py-3 text-center bg-white" style="background: rgba(255,255,255,0.95) !important; backdrop-filter: blur(5px);">
                        <span class="fw-bold text-dark fs-6" id="kordinatTerpilih">
                            <i class="fas fa-hand-pointer text-primary me-2 pulse-animation"></i> Klik area pada peta untuk meletakkan pin
                        </span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0 px-4 py-3">
                <button type="button" class="btn btn-light border fw-bold rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary fw-bold rounded-pill px-5" id="btnSimpanKoordinat" style="background: linear-gradient(90deg, #0047ba, #1aa4f6); border: none;">
                    <i class="fas fa-save me-2"></i>Gunakan Titik Ini
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    
    function addRow(tableId, type) {
        var table = document.getElementById(tableId).getElementsByTagName('tbody')[0];
        var row = table.insertRow();
        var content = '';

        if(type === 'waktu') {
            content = `<td><input type="text" name="wk_waktu[]" class="form-control" placeholder="Jam / Tgl"></td>
                       <td><input type="text" name="wk_kejadian[]" class="form-control"></td>
                       <td><input type="text" name="wk_lokasi[]" class="form-control"></td>
                       <td>
                           <div class="input-group input-group-sm">
                               <input type="text" name="wk_latitude[]" class="form-control input-koordinat" placeholder="Lat" readonly>
                               <input type="text" name="wk_longitude[]" class="form-control input-koordinat" placeholder="Lng" readonly>
                               <button class="btn btn-primary" type="button" onclick="bukaPeta(this)" title="Pilih Titik di Peta"><i class="fas fa-map-marker-alt"></i></button>
                           </div>
                       </td>`;
        } else if(type === 'respon') {
            content = `<td><input type="text" name="resp_kluster[]" class="form-control"></td>
                       <td><input type="text" name="resp_lokasi[]" class="form-control"></td>
                       <td><input type="text" name="resp_keterangan[]" class="form-control"></td>`;
        } else if(type === 'manfaat') {
            
            content = `<td><input type="text" name="pm_kegiatan[]" class="form-control"></td>
                       <td><input type="date" name="pm_tanggal[]" class="form-control"></td>
                       <td><input type="text" name="pm_jumlah[]" class="form-control"></td>`;
        } else if(type === 'tim') {
            content = `<td><input type="text" name="tim_kluster[]" class="form-control"></td>
                       <td><input type="number" name="tim_total[]" class="form-control text-center" value="0" min="0"></td>
                       <td><input type="number" name="tim_pulang[]" class="form-control text-center" value="0" min="0"></td>
                       <td><input type="number" name="tim_bertugas[]" class="form-control text-center" value="0" min="0"></td>`;
        } else if(type === 'kebutuhan') {
            content = `<td><input type="text" name="keb_item[]" class="form-control"></td>
                       <td><input type="text" name="keb_jumlah[]" class="form-control"></td>`;
        } else if(type === 'kontak') {
            content = `<td><input type="text" name="cp_nama[]" class="form-control"></td>
                       <td><input type="text" name="cp_nohp[]" class="form-control"></td>`;
        }

        
        content += `<td class="text-center"><button type="button" class="btn btn-danger btn-sm w-100 shadow-sm" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-trash-alt"></i></button></td>`;
        row.innerHTML = content;
    }
</script>

<script>
    
    let mapPicker, markerPicker;
    let inputLatAktif = null, inputLngAktif = null;
    let tempLat = null, tempLng = null;
    
    const defaultLat = document.getElementById('pusat_lat').value || -0.0263;
    const defaultLng = document.getElementById('pusat_lng').value || 109.3425;

    function bukaPeta(button) {
        let divGroup = button.parentElement;
        inputLatAktif = divGroup.querySelector('input[name="wk_latitude[]"]');
        inputLngAktif = divGroup.querySelector('input[name="wk_longitude[]"]');

        let modal = new bootstrap.Modal(document.getElementById('modalPeta'));
        modal.show();

        document.getElementById('modalPeta').addEventListener('shown.bs.modal', function () {
            if (!mapPicker) {
                mapPicker = L.map('mapPicker').setView([defaultLat, defaultLng], 12);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; OpenStreetMap contributors'
                }).addTo(mapPicker);

                mapPicker.on('click', function(e) {
                    tempLat = e.latlng.lat.toFixed(6);
                    tempLng = e.latlng.lng.toFixed(6);

                    if (markerPicker) {
                        markerPicker.setLatLng(e.latlng);
                    } else {
                        markerPicker = L.marker(e.latlng).addTo(mapPicker);
                    }
                    
                    document.getElementById('kordinatTerpilih').innerHTML = `<i class="fas fa-check-circle text-success me-2"></i> Koordinat Terkunci: <strong>${tempLat} , ${tempLng}</strong>`;
                });
            }

            mapPicker.invalidateSize(); 

            if (inputLatAktif.value && inputLngAktif.value) {
                let eksisLat = parseFloat(inputLatAktif.value);
                let eksisLng = parseFloat(inputLngAktif.value);
                mapPicker.setView([eksisLat, eksisLng], 15);
                
                if (markerPicker) markerPicker.setLatLng([eksisLat, eksisLng]);
                else markerPicker = L.marker([eksisLat, eksisLng]).addTo(mapPicker);
                
                document.getElementById('kordinatTerpilih').innerHTML = `<i class="fas fa-check-circle text-success me-2"></i> Koordinat Terkunci: <strong>${eksisLat} , ${eksisLng}</strong>`;
                tempLat = eksisLat; tempLng = eksisLng;
            } else {
                if(markerPicker) mapPicker.removeLayer(markerPicker);
                markerPicker = null;
                document.getElementById('kordinatTerpilih').innerHTML = '<i class="fas fa-hand-pointer text-primary me-2"></i> Klik area pada peta untuk meletakkan pin';
                tempLat = null; tempLng = null;
                mapPicker.setView([defaultLat, defaultLng], 12);
            }
        }, { once: true });
    }
    
    document.getElementById('btnSimpanKoordinat').addEventListener('click', function() {
        if (tempLat && tempLng && inputLatAktif && inputLngAktif) {
            inputLatAktif.value = tempLat;
            inputLngAktif.value = tempLng;
            let modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalPeta'));
            modalInstance.hide();
        } else {
            alert('Harap klik lokasi kejadian pada peta terlebih dahulu untuk meletakkan pin!');
        }
    });
</script>

<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        let submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> MENGUNGGAH DATA...';
        submitBtn.disabled = true;
        let cancelBtn = this.querySelector('.btn-light');
        if(cancelBtn) cancelBtn.style.pointerEvents = 'none';
    });
</script>
</body>
</html>