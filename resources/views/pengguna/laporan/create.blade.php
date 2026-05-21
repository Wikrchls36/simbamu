<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form SITREP MDMC - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        body { background-color: #f4f7fa; font-family: 'Poppins', sans-serif; }
        .section-header { 
            background: #0047ba; color: white; padding: 12px 20px; 
            font-weight: bold; border-radius: 8px; margin-top: 40px; 
            margin-bottom: 20px; text-transform: uppercase; font-size: 1rem;
        }
        .card { border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .form-label { font-weight: 600; font-size: 0.85rem; color: #444; margin-top: 10px; }
        .table th { font-size: 0.8rem; background-color: #f8f9fa; text-align: center; border-top: none; vertical-align: middle; }
        .btn-add-row { background-color: #1aa4f6; color: white; border: none; font-weight: bold; }
        .btn-add-row:hover { background-color: #0047ba; color: white; }
        .form-control:focus, .form-select:focus { border-color: #1aa4f6; box-shadow: 0 0 0 0.25rem rgba(26, 164, 246, 0.1); }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card p-4 p-md-5">
                
                <div class="d-flex justify-content-between align-items-start mb-4 border-bottom pb-4">
                    
                    <div class="text-center flex-grow-1 px-3">
                        <h2 class="fw-bold text-primary mb-1">LAPORAN SITUASI (SITREP)</h2>
                        <h6 class="text-secondary fw-normal">Muhammadiyah Disaster Management Center Wilayah Kalimantan Barat</h6>
                    </div>

                    <div style="width: 100px;" class="d-none d-md-block"></div>
                </div>

                <form action="{{ route('pengguna.laporan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="section-header">A. Informasi Kunci</div>
                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Jenis Bencana</label>
                            <select name="jenis_bencana" class="form-select" required>
                                <option value="Banjir">Banjir</option>
                                <option value="Karhutla">Karhutla</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Keluar SitRep</label>
                            <input type="date" name="tanggal_sitrep" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-primary">Latitude</label>
                            <input type="text" name="latitude" class="form-control bg-light text-muted" value="{{ Auth::user()->latitude ?? '' }}" readonly required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-primary">Longitude</label>
                            <input type="text" name="longitude" class="form-control bg-light text-muted" value="{{ Auth::user()->longitude ?? '' }}" readonly required>
                        </div>
                    </div>

                    <p class="fw-bold small mb-2 text-primary">Waktu Kejadian</p>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle" id="tb_waktu">
                            <thead>
                                <tr><th>Waktu Kejadian</th><th>Kejadian</th><th>Lokasi</th><th width="50">#</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="wk_waktu[]" class="form-control" placeholder="Jam/Tgl"></td>
                                    <td><input type="text" name="wk_kejadian[]" class="form-control"></td>
                                    <td><input type="text" name="wk_lokasi[]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_waktu', 'waktu')">+</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="fw-bold small mb-2 text-primary">Dampak</p>
                    <div class="row g-2 mb-4 text-center">
                        <div class="col"><label class="form-label small">Meninggal</label><input type="number" name="dampak_meninggal" class="form-control text-center" value="0"></div>
                        <div class="col"><label class="form-label small">Luka-luka</label><input type="number" name="dampak_luka" class="form-control text-center" value="0"></div>
                        <div class="col"><label class="form-label small">Hilang</label><input type="number" name="dampak_hilang" class="form-control text-center" value="0"></div>
                        <div class="col"><label class="form-label small">Pengungsi</label><input type="number" name="dampak_pengungsi" class="form-control text-center" value="0"></div>
                        <div class="col"><label class="form-label small">Terdampak</label><input type="number" name="dampak_terdampak" class="form-control text-center" value="0"></div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-12"><label class="form-label">Dampak Material</label><textarea name="dampak_material" class="form-control" rows="2"></textarea></div>
                        <div class="col-md-6"><label class="form-label">Lokasi Poskor Muhammadiyah</label><textarea name="lokasi_poskor" class="form-control" rows="2"></textarea></div>
                        <div class="col-md-6"><label class="form-label">Lokasi Pos Pelayanan (Opsional)</label><textarea name="lokasi_pos_pelayanan" class="form-control" rows="2"></textarea></div>
                    </div>

                    <div class="section-header">B. Kronologi Kejadian</div>
                    <textarea name="kronologi" class="form-control" rows="4" required></textarea>

                    <div class="section-header">C. Situasi Terkini</div>
                    <textarea name="situasi_terkini" class="form-control" rows="4"></textarea>

                    <div class="section-header">D. Respon Muhammadiyah</div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tb_respon">
                            <thead>
                                <tr><th>Kluster</th><th>Lokasi</th><th>Penerima Manfaat / Keterangan</th><th width="50">#</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="resp_kluster[]" class="form-control" placeholder="Kesehatan/Logistik"></td>
                                    <td><input type="text" name="resp_lokasi[]" class="form-control"></td>
                                    <td><input type="text" name="resp_keterangan[]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_respon', 'respon')">+</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="section-header">E. Penerima Manfaat</div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tb_manfaat">
                            <thead>
                                <tr><th>Kegiatan</th><th>Tanggal Kegiatan</th><th>Jumlah Penerima Manfaat</th><th width="50">#</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="pm_kegiatan[]" class="form-control"></td>
                                    <td><input type="text" name="pm_tanggal[]" class="form-control"></td>
                                    <td><input type="text" name="pm_jumlah[]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_manfaat', 'manfaat')">+</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="section-header">F. Tim Respon MDMC</div>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle" id="tb_tim">
                            <thead>
                                <tr><th>Kluster</th><th>Total</th><th>Pulang</th><th>Bertugas</th><th width="50">#</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="tim_kluster[]" class="form-control"></td>
                                    <td><input type="number" name="tim_total[]" class="form-control text-center" value="0"></td>
                                    <td><input type="number" name="tim_pulang[]" class="form-control text-center" value="0"></td>
                                    <td><input type="number" name="tim_bertugas[]" class="form-control text-center" value="0"></td>
                                    <td><button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_tim', 'tim')">+</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4"><label class="form-label">Total Personil</label><input type="number" name="tim_total_semua" class="form-control" value="0"></div>
                        <div class="col-md-4"><label class="form-label">Laki-Laki</label><input type="number" name="tim_laki" class="form-control" value="0"></div>
                        <div class="col-md-4"><label class="form-label">Perempuan</label><input type="number" name="tim_perempuan" class="form-control" value="0"></div>
                        <div class="col-md-12"><label class="form-label">Asal Instansi</label><textarea name="asal_instansi" class="form-control" rows="2"></textarea></div>
                    </div>

                    <div class="section-header">G. Kebutuhan</div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tb_kebutuhan">
                            <thead>
                                <tr><th>Kebutuhan</th><th>Jumlah Kebutuhan</th><th width="50">#</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="keb_item[]" class="form-control"></td>
                                    <td><input type="text" name="keb_jumlah[]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_kebutuhan', 'kebutuhan')">+</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="section-header">H. Sumber Informasi</div>
                    <textarea name="sumber_informasi" class="form-control" rows="2"></textarea>

                    <div class="section-header">I. Contact Person</div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tb_kontak">
                            <thead>
                                <tr><th>Nama</th><th>No Telepon/HP</th><th width="50">#</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="cp_nama[]" class="form-control"></td>
                                    <td><input type="text" name="cp_nohp[]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_kontak', 'kontak')">+</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="section-header">J. Rekening Penggalangan Dana</div>
                    <textarea name="rekening_donasi" class="form-control" rows="3" placeholder="Contoh:&#10;1. BSI - Lazismu MDMC (12345678)&#10;2. BRI - MDMC Daerah (87654321)"></textarea>

                    <div class="section-header">K. Penutup</div>
                    <div class="alert alert-light border p-4 mb-4">
                        <p class="mb-5 text-dark" style="white-space: pre-wrap; line-height: 1.6;">Demikian laporan ini kami buat sebagai sumber informasi dan diharapkan dapat menjadi pertimbangan dalam pengambilan keputusan.
                        
Lokasi, tanggal di keluarkan sitrep


Tim MDMC ………….</p>
                        
                        <div class="row g-3 mb-4 border-top pt-3">
                            <div class="col-md-6">
                                <label class="form-label text-primary">Lokasi Dikeluarkan SitRep</label>
                                <input type="text" name="penutup_lokasi" class="form-control" placeholder="Contoh: Pontianak" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-primary">Nama Tim / Daerah</label>
                                <input type="text" name="penutup_nama_tim" class="form-control" placeholder="Contoh: Kota Pontianak" required>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-md-12">
                            <h6 class="fw-bold text-dark mb-2">LAMPIRAN</h6>
                            <label class="form-label text-muted">FOTO AKTIVITAS dan LAMPIRAN LAPORAN (Opsional)</label>
                            <input type="file" name="foto_dokumentasi[]" multiple class="form-control shadow-sm" accept="image/*,.pdf">
                        </div>
                    </div>

                    <div class="d-flex gap-3 pt-4">
                        <a href="{{ route('pengguna.laporan.index') }}" class="btn btn-light btn-lg fw-bold rounded-pill shadow-sm border px-5">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill shadow flex-grow-1">
                            <i class="fas fa-paper-plane me-2"></i> KIRIM LAPORAN SITREP SEKARANG
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function addRow(tableId, type) {
        var table = document.getElementById(tableId).getElementsByTagName('tbody')[0];
        var row = table.insertRow();
        var content = '';

        if(type === 'waktu') {
            content = `<td><input type="text" name="wk_waktu[]" class="form-control"></td>
                       <td><input type="text" name="wk_kejadian[]" class="form-control"></td>
                       <td><input type="text" name="wk_lokasi[]" class="form-control"></td>`;
        } else if(type === 'respon') {
            content = `<td><input type="text" name="resp_kluster[]" class="form-control"></td>
                       <td><input type="text" name="resp_lokasi[]" class="form-control"></td>
                       <td><input type="text" name="resp_keterangan[]" class="form-control"></td>`;
        } else if(type === 'manfaat') {
            content = `<td><input type="text" name="pm_kegiatan[]" class="form-control"></td>
                       <td><input type="text" name="pm_tanggal[]" class="form-control"></td>
                       <td><input type="text" name="pm_jumlah[]" class="form-control"></td>`;
        } else if(type === 'tim') {
            content = `<td><input type="text" name="tim_kluster[]" class="form-control"></td>
                       <td><input type="number" name="tim_total[]" class="form-control text-center" value="0"></td>
                       <td><input type="number" name="tim_pulang[]" class="form-control text-center" value="0"></td>
                       <td><input type="number" name="tim_bertugas[]" class="form-control text-center" value="0"></td>`;
        } else if(type === 'kebutuhan') {
            content = `<td><input type="text" name="keb_item[]" class="form-control"></td>
                       <td><input type="text" name="keb_jumlah[]" class="form-control"></td>`;
        } else if(type === 'kontak') {
            content = `<td><input type="text" name="cp_nama[]" class="form-control"></td>
                       <td><input type="text" name="cp_nohp[]" class="form-control"></td>`;
        }

        content += `<td class="text-center"><button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-trash"></i></button></td>`;
        row.innerHTML = content;
    }
</script>

<script>
    // Script Mencegah Double Submit & Menampilkan Efek Loading
    document.querySelector('form').addEventListener('submit', function(e) {
        let submitBtn = this.querySelector('button[type="submit"]');
        
        // Ubah teks tombol dan tambahkan animasi putar (spinner)
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> SEDANG MENGIRIM...';
        
        // Matikan tombol agar tidak bisa diklik lagi
        submitBtn.disabled = true;
        
        // Sedikit meredupkan tombol Batal agar user fokus menunggu
        let cancelBtn = this.querySelector('.btn-light');
        if(cancelBtn) cancelBtn.style.pointerEvents = 'none';
    });
</script>
</body>
</html>