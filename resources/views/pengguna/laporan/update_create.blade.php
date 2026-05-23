<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update SITREP MDMC - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

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
        .input-locked { background-color: #e9ecef; cursor: not-allowed; }
        .input-koordinat { font-size: 0.75rem; padding: 0.25rem; text-align: center; } /* Style khusus kotak koordinat */
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="card p-4 p-md-5">
                
                <div class="d-flex justify-content-between align-items-start mb-4 border-bottom pb-4">
                    <div class="text-center flex-grow-1 px-3">
                        <span class="badge bg-danger mb-2 px-3 py-1 rounded-pill">UPDATE SITREP BERIKUTNYA</span>
                        <h2 class="fw-bold text-primary mb-1">LAPORAN SITUASI (SITREP)</h2>
                        <h6 class="text-secondary fw-normal">MDMC Wilayah Kalimantan Barat</h6>
                    </div>
                </div>

                <form action="{{ route('pengguna.laporan.store_update', $laporan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="section-header">A. Informasi Kunci</div>
                    
                    <div class="alert alert-info border-0 shadow-sm rounded-3 small">
                        <i class="fas fa-info-circle me-2"></i> Data ditarik dari SitRep terakhir. Ubah yang perlu di-update.
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Jenis Bencana</label>
                            <input type="text" class="form-control input-locked" value="{{ $laporan->jenis_bencana }}" readonly>
                            <input type="hidden" name="jenis_bencana" value="{{ $laporan->jenis_bencana }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tanggal Keluar SitRep</label>
                            <input type="date" name="tanggal_sitrep" class="form-control fw-bold" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-primary">Lat (Pusat)</label>
                            <input type="text" name="latitude" id="pusat_lat" class="form-control input-locked" value="{{ $laporan->latitude }}" readonly required>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-primary">Lng (Pusat)</label>
                            <input type="text" name="longitude" id="pusat_lng" class="form-control input-locked" value="{{ $laporan->longitude }}" readonly required>
                        </div>
                    </div>

                    <p class="fw-bold small mb-2 text-primary">Waktu Kejadian</p>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle" id="tb_waktu">
                            <thead>
                                <tr><th>Waktu Kejadian</th>
                                <th>Kejadian</th><th>Lokasi</th>
                                <th width="200">Koordinat Peta</th>
                                <th width="50">#</th></tr>
                            </thead>
                            <tbody>
                                @php 
                                    $wk_waktu = is_string($latestSitrep->wk_waktu ?? null) ? json_decode($latestSitrep->wk_waktu, true) : ($latestSitrep->wk_waktu ?? []);
                                    $wk_kejadian = is_string($latestSitrep->wk_kejadian ?? null) ? json_decode($latestSitrep->wk_kejadian, true) : ($latestSitrep->wk_kejadian ?? []);
                                    $wk_lokasi = is_string($latestSitrep->wk_lokasi ?? null) ? json_decode($latestSitrep->wk_lokasi, true) : ($latestSitrep->wk_lokasi ?? []);
                                    
                                    // Tarik data array koordinat
                                    $wk_latitude = is_string($latestSitrep->wk_latitude ?? null) ? json_decode($latestSitrep->wk_latitude, true) : ($latestSitrep->wk_latitude ?? []);
                                    $wk_longitude = is_string($latestSitrep->wk_longitude ?? null) ? json_decode($latestSitrep->wk_longitude, true) : ($latestSitrep->wk_longitude ?? []);
                                @endphp
                                @forelse($wk_waktu as $index => $waktu)
                                <tr>
                                    <td><input type="text" name="wk_waktu[]" class="form-control" value="{{ $waktu }}"></td>
                                    <td><input type="text" name="wk_kejadian[]" class="form-control" value="{{ $wk_kejadian[$index] ?? '' }}"></td>
                                    <td><input type="text" name="wk_lokasi[]" class="form-control" value="{{ $wk_lokasi[$index] ?? '' }}"></td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="wk_latitude[]" class="form-control input-koordinat" value="{{ $wk_latitude[$index] ?? '' }}" placeholder="Lat" readonly>
                                            <input type="text" name="wk_longitude[]" class="form-control input-koordinat" value="{{ $wk_longitude[$index] ?? '' }}" placeholder="Lng" readonly>
                                            <button class="btn btn-outline-primary" type="button" onclick="bukaPeta(this)" title="Pilih di Peta"><i class="fas fa-map-marker-alt"></i></button>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($index == 0)
                                            <button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_waktu', 'waktu')">+</button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td><input type="text" name="wk_waktu[]" class="form-control" placeholder="Jam/Tgl"></td>
                                    <td><input type="text" name="wk_kejadian[]" class="form-control"></td>
                                    <td><input type="text" name="wk_lokasi[]" class="form-control"></td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <input type="text" name="wk_latitude[]" class="form-control input-koordinat" placeholder="Lat" readonly>
                                            <input type="text" name="wk_longitude[]" class="form-control input-koordinat" placeholder="Lng" readonly>
                                            <button class="btn btn-outline-primary" type="button" onclick="bukaPeta(this)" title="Pilih di Peta"><i class="fas fa-map-marker-alt"></i></button>
                                        </div>
                                    </td>
                                    <td><button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_waktu', 'waktu')">+</button></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <p class="fw-bold small mb-2 text-primary">Dampak (Perkembangan Terbaru)</p>
                    <div class="row g-2 mb-4 text-center">
                        <div class="col"><label class="form-label small">Meninggal</label><input type="number" name="dampak_meninggal" class="form-control text-center fw-bold" value="{{ $latestSitrep->dampak_meninggal ?? 0 }}"></div>
                        <div class="col"><label class="form-label small">Luka-luka</label><input type="number" name="dampak_luka" class="form-control text-center fw-bold" value="{{ $latestSitrep->dampak_luka ?? 0 }}"></div>
                        <div class="col"><label class="form-label small">Hilang</label><input type="number" name="dampak_hilang" class="form-control text-center fw-bold" value="{{ $latestSitrep->dampak_hilang ?? 0 }}"></div>
                        <div class="col"><label class="form-label small">Pengungsi</label><input type="number" name="dampak_pengungsi" class="form-control text-center fw-bold" value="{{ $latestSitrep->dampak_pengungsi ?? 0 }}"></div>
                        <div class="col"><label class="form-label small">Terdampak</label><input type="number" name="dampak_terdampak" class="form-control text-center fw-bold" value="{{ $latestSitrep->dampak_terdampak ?? 0 }}"></div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-12"><label class="form-label">Dampak Material</label><textarea name="dampak_material" class="form-control" rows="2">{{ $latestSitrep->dampak_material ?? '' }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Lokasi Poskor</label><textarea name="lokasi_poskor" class="form-control" rows="2">{{ $latestSitrep->lokasi_poskor ?? '' }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Lokasi Pos Pelayanan</label><textarea name="lokasi_pos_pelayanan" class="form-control" rows="2">{{ $latestSitrep->lokasi_pos_pelayanan ?? '' }}</textarea></div>
                    </div>

                    <div class="section-header">B. Kronologi Kejadian</div>
                    <textarea name="kronologi" class="form-control" rows="4" required>{{ $latestSitrep->kronologi ?? '' }}</textarea>

                    <div class="section-header">C. Situasi Terkini</div>
                    <textarea name="situasi_terkini" class="form-control" rows="4">{{ $latestSitrep->situasi_terkini ?? '' }}</textarea>

                    <div class="section-header">D. Respon Muhammadiyah</div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tb_respon">
                            <thead><tr><th>Kluster</th><th>Lokasi</th><th>Penerima Manfaat / Ket</th><th width="50">#</th></tr></thead>
                            <tbody>
                                @php 
                                    $resp_kluster = is_string($latestSitrep->resp_kluster ?? null) ? json_decode($latestSitrep->resp_kluster, true) : ($latestSitrep->resp_kluster ?? []);
                                    $resp_lokasi = is_string($latestSitrep->resp_lokasi ?? null) ? json_decode($latestSitrep->resp_lokasi, true) : ($latestSitrep->resp_lokasi ?? []);
                                    $resp_keterangan = is_string($latestSitrep->resp_keterangan ?? null) ? json_decode($latestSitrep->resp_keterangan, true) : ($latestSitrep->resp_keterangan ?? []);
                                @endphp
                                @forelse($resp_kluster as $index => $kluster)
                                <tr>
                                    <td><input type="text" name="resp_kluster[]" class="form-control" value="{{ $kluster }}"></td>
                                    <td><input type="text" name="resp_lokasi[]" class="form-control" value="{{ $resp_lokasi[$index] ?? '' }}"></td>
                                    <td><input type="text" name="resp_keterangan[]" class="form-control" value="{{ $resp_keterangan[$index] ?? '' }}"></td>
                                    <td class="text-center">
                                        @if($index == 0)
                                            <button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_respon', 'respon')">+</button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td><input type="text" name="resp_kluster[]" class="form-control"></td>
                                    <td><input type="text" name="resp_lokasi[]" class="form-control"></td>
                                    <td><input type="text" name="resp_keterangan[]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_respon', 'respon')">+</button></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="section-header">E. Penerima Manfaat</div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tb_manfaat">
                            <thead><tr><th>Kegiatan</th><th>Tanggal</th><th>Jumlah Penerima</th><th width="50">#</th></tr></thead>
                            <tbody>
                                @php 
                                    $pm_kegiatan = is_string($latestSitrep->pm_kegiatan ?? null) ? json_decode($latestSitrep->pm_kegiatan, true) : ($latestSitrep->pm_kegiatan ?? []);
                                    $pm_tanggal = is_string($latestSitrep->pm_tanggal ?? null) ? json_decode($latestSitrep->pm_tanggal, true) : ($latestSitrep->pm_tanggal ?? []);
                                    $pm_jumlah = is_string($latestSitrep->pm_jumlah ?? null) ? json_decode($latestSitrep->pm_jumlah, true) : ($latestSitrep->pm_jumlah ?? []);
                                @endphp
                                @forelse($pm_kegiatan as $index => $kegiatan)
                                <tr>
                                    <td><input type="text" name="pm_kegiatan[]" class="form-control" value="{{ $kegiatan }}"></td>
                                    <td><input type="text" name="pm_tanggal[]" class="form-control" value="{{ $pm_tanggal[$index] ?? '' }}"></td>
                                    <td><input type="text" name="pm_jumlah[]" class="form-control" value="{{ $pm_jumlah[$index] ?? '' }}"></td>
                                    <td class="text-center">
                                        @if($index == 0)
                                            <button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_manfaat', 'manfaat')">+</button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td><input type="text" name="pm_kegiatan[]" class="form-control"></td>
                                    <td><input type="text" name="pm_tanggal[]" class="form-control"></td>
                                    <td><input type="text" name="pm_jumlah[]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_manfaat', 'manfaat')">+</button></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="section-header">F. Tim Respon MDMC</div>
                    <div class="table-responsive mb-3">
                        <table class="table table-bordered align-middle" id="tb_tim">
                            <thead><tr><th>Kluster</th><th>Total</th><th>Pulang</th><th>Bertugas</th><th width="50">#</th></tr></thead>
                            <tbody>
                                @php 
                                    $tim_kluster = is_string($latestSitrep->tim_kluster ?? null) ? json_decode($latestSitrep->tim_kluster, true) : ($latestSitrep->tim_kluster ?? []);
                                    $tim_total = is_string($latestSitrep->tim_total ?? null) ? json_decode($latestSitrep->tim_total, true) : ($latestSitrep->tim_total ?? []);
                                    $tim_pulang = is_string($latestSitrep->tim_pulang ?? null) ? json_decode($latestSitrep->tim_pulang, true) : ($latestSitrep->tim_pulang ?? []);
                                    $tim_bertugas = is_string($latestSitrep->tim_bertugas ?? null) ? json_decode($latestSitrep->tim_bertugas, true) : ($latestSitrep->tim_bertugas ?? []);
                                @endphp
                                @forelse($tim_kluster as $index => $kluster)
                                <tr>
                                    <td><input type="text" name="tim_kluster[]" class="form-control" value="{{ $kluster }}"></td>
                                    <td><input type="number" name="tim_total[]" class="form-control text-center" value="{{ $tim_total[$index] ?? 0 }}"></td>
                                    <td><input type="number" name="tim_pulang[]" class="form-control text-center" value="{{ $tim_pulang[$index] ?? 0 }}"></td>
                                    <td><input type="number" name="tim_bertugas[]" class="form-control text-center" value="{{ $tim_bertugas[$index] ?? 0 }}"></td>
                                    <td class="text-center">
                                        @if($index == 0)
                                            <button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_tim', 'tim')">+</button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td><input type="text" name="tim_kluster[]" class="form-control"></td>
                                    <td><input type="number" name="tim_total[]" class="form-control text-center" value="0"></td>
                                    <td><input type="number" name="tim_pulang[]" class="form-control text-center" value="0"></td>
                                    <td><input type="number" name="tim_bertugas[]" class="form-control text-center" value="0"></td>
                                    <td><button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_tim', 'tim')">+</button></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4"><label class="form-label">Total Personil</label><input type="number" name="tim_total_semua" class="form-control" value="{{ $latestSitrep->tim_total_semua ?? 0 }}"></div>
                        <div class="col-md-4"><label class="form-label">Laki-Laki</label><input type="number" name="tim_laki" class="form-control" value="{{ $latestSitrep->tim_laki ?? 0 }}"></div>
                        <div class="col-md-4"><label class="form-label">Perempuan</label><input type="number" name="tim_perempuan" class="form-control" value="{{ $latestSitrep->tim_perempuan ?? 0 }}"></div>
                        <div class="col-md-12"><label class="form-label">Asal Instansi</label><textarea name="asal_instansi" class="form-control" rows="2">{{ $latestSitrep->asal_instansi ?? '' }}</textarea></div>
                    </div>

                    <div class="section-header">G. Kebutuhan</div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tb_kebutuhan">
                            <thead><tr><th>Kebutuhan</th><th>Jumlah Kebutuhan</th><th width="50">#</th></tr></thead>
                            <tbody>
                                @php 
                                    $keb_item = is_string($latestSitrep->keb_item ?? null) ? json_decode($latestSitrep->keb_item, true) : ($latestSitrep->keb_item ?? []);
                                    $keb_jumlah = is_string($latestSitrep->keb_jumlah ?? null) ? json_decode($latestSitrep->keb_jumlah, true) : ($latestSitrep->keb_jumlah ?? []);
                                @endphp
                                @forelse($keb_item as $index => $item)
                                <tr>
                                    <td><input type="text" name="keb_item[]" class="form-control" value="{{ $item }}"></td>
                                    <td><input type="text" name="keb_jumlah[]" class="form-control" value="{{ $keb_jumlah[$index] ?? '' }}"></td>
                                    <td class="text-center">
                                        @if($index == 0)
                                            <button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_kebutuhan', 'kebutuhan')">+</button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td><input type="text" name="keb_item[]" class="form-control"></td>
                                    <td><input type="text" name="keb_jumlah[]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_kebutuhan', 'kebutuhan')">+</button></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="section-header">H. Sumber Informasi</div>
                    <textarea name="sumber_informasi" class="form-control" rows="2">{{ $latestSitrep->sumber_informasi ?? '' }}</textarea>

                    <div class="section-header">I. Contact Person</div>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle" id="tb_kontak">
                            <thead><tr><th>Nama</th><th>No Telepon/HP</th><th width="50">#</th></tr></thead>
                            <tbody>
                                @php 
                                    $cp_nama = is_string($latestSitrep->cp_nama ?? null) ? json_decode($latestSitrep->cp_nama, true) : ($latestSitrep->cp_nama ?? []);
                                    $cp_nohp = is_string($latestSitrep->cp_nohp ?? null) ? json_decode($latestSitrep->cp_nohp, true) : ($latestSitrep->cp_nohp ?? []);
                                @endphp
                                @forelse($cp_nama as $index => $nama)
                                <tr>
                                    <td><input type="text" name="cp_nama[]" class="form-control" value="{{ $nama }}"></td>
                                    <td><input type="text" name="cp_nohp[]" class="form-control" value="{{ $cp_nohp[$index] ?? '' }}"></td>
                                    <td class="text-center">
                                        @if($index == 0)
                                            <button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_kontak', 'kontak')">+</button>
                                        @else
                                            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-trash"></i></button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td><input type="text" name="cp_nama[]" class="form-control"></td>
                                    <td><input type="text" name="cp_nohp[]" class="form-control"></td>
                                    <td><button type="button" class="btn btn-add-row btn-sm" onclick="addRow('tb_kontak', 'kontak')">+</button></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="section-header">J. Rekening Penggalangan Dana</div>
                    <textarea name="rekening_donasi" class="form-control" rows="3">{{ $latestSitrep->rekening_donasi ?? '' }}</textarea>

                    <div class="section-header">K. Penutup</div>
                    <div class="alert alert-light border p-4 mb-4">
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label text-primary">Lokasi Dikeluarkan</label>
                                <input type="text" name="penutup_lokasi" class="form-control" value="{{ $latestSitrep->penutup_lokasi ?? '' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-primary">Nama Tim / Daerah</label>
                                <input type="text" name="penutup_nama_tim" class="form-control" value="{{ $latestSitrep->penutup_nama_tim ?? '' }}" required>
                            </div>
                        </div>

                        <hr class="my-4">
                        <div class="col-md-12">
                            <h6 class="fw-bold text-dark mb-2">LAMPIRAN BARU</h6>
                            <label class="form-label text-muted">Upload dokumentasi baru (Opsional)</label>
                            <input type="file" name="foto_dokumentasi[]" multiple class="form-control shadow-sm" accept="image/*">
                        </div>
                    </div>

                    <div class="d-flex gap-3 pt-4">
                        <a href="{{ route('pengguna.laporan.index') }}" class="btn btn-light btn-lg fw-bold rounded-pill shadow-sm border px-5">Batal</a>
                        <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-pill shadow flex-grow-1"><i class="fas fa-paper-plane me-2"></i> UPDATE SITREP SEKARANG</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalPeta" tabindex="-1" aria-labelledby="modalPetaLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-primary" id="modalPetaLabel"><i class="fas fa-map-marked-alt me-2"></i>Pilih Titik Lokasi Bencana (Update)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div id="mapPicker" style="height: 450px; width: 100%;"></div>
                <div class="p-3 text-center bg-light">
                    <span class="badge bg-primary fs-6 py-2 px-3 shadow-sm" id="kordinatTerpilih">Klik pada peta untuk menetapkan titik</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light border fw-bold" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success fw-bold" id="btnSimpanKoordinat"><i class="fas fa-save me-2"></i>Simpan Titik Ini</button>
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
            content = `<td><input type="text" name="wk_waktu[]" class="form-control" placeholder="Jam/Tgl"></td>
                       <td><input type="text" name="wk_kejadian[]" class="form-control"></td>
                       <td><input type="text" name="wk_lokasi[]" class="form-control"></td>
                       <td>
                           <div class="input-group input-group-sm">
                               <input type="text" name="wk_latitude[]" class="form-control input-koordinat" placeholder="Lat" readonly>
                               <input type="text" name="wk_longitude[]" class="form-control input-koordinat" placeholder="Lng" readonly>
                               <button class="btn btn-outline-primary" type="button" onclick="bukaPeta(this)" title="Pilih di Peta"><i class="fas fa-map-marker-alt"></i></button>
                           </div>
                       </td>`;
        } else if(type === 'respon') content = `<td><input type="text" name="resp_kluster[]" class="form-control"></td><td><input type="text" name="resp_lokasi[]" class="form-control"></td><td><input type="text" name="resp_keterangan[]" class="form-control"></td>`;
        else if(type === 'manfaat') content = `<td><input type="text" name="pm_kegiatan[]" class="form-control"></td><td><input type="text" name="pm_tanggal[]" class="form-control"></td><td><input type="text" name="pm_jumlah[]" class="form-control"></td>`;
        else if(type === 'tim') content = `<td><input type="text" name="tim_kluster[]" class="form-control"></td><td><input type="number" name="tim_total[]" class="form-control text-center" value="0"></td><td><input type="number" name="tim_pulang[]" class="form-control text-center" value="0"></td><td><input type="number" name="tim_bertugas[]" class="form-control text-center" value="0"></td>`;
        else if(type === 'kebutuhan') content = `<td><input type="text" name="keb_item[]" class="form-control"></td><td><input type="text" name="keb_jumlah[]" class="form-control"></td>`;
        else if(type === 'kontak') content = `<td><input type="text" name="cp_nama[]" class="form-control"></td><td><input type="text" name="cp_nohp[]" class="form-control"></td>`;
        
        content += `<td class="text-center"><button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()"><i class="fas fa-trash"></i></button></td>`;
        row.innerHTML = content;
    }
</script>

<script>
    let mapPicker, markerPicker;
    let inputLatAktif = null, inputLngAktif = null;
    let tempLat = null, tempLng = null;

    // Mengambil titik pusat dari form (atau default)
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
                    attribution: '&copy; OpenStreetMap'
                }).addTo(mapPicker);

                mapPicker.on('click', function(e) {
                    tempLat = e.latlng.lat.toFixed(6);
                    tempLng = e.latlng.lng.toFixed(6);

                    if (markerPicker) {
                        markerPicker.setLatLng(e.latlng);
                    } else {
                        markerPicker = L.marker(e.latlng).addTo(mapPicker);
                    }
                    document.getElementById('kordinatTerpilih').innerText = `Lat: ${tempLat}, Lng: ${tempLng}`;
                });
            }

            mapPicker.invalidateSize();

            
            if (inputLatAktif.value && inputLngAktif.value) {
                let eksisLat = parseFloat(inputLatAktif.value);
                let eksisLng = parseFloat(inputLngAktif.value);
                mapPicker.setView([eksisLat, eksisLng], 15);
                
                if (markerPicker) markerPicker.setLatLng([eksisLat, eksisLng]);
                else markerPicker = L.marker([eksisLat, eksisLng]).addTo(mapPicker);
                
                document.getElementById('kordinatTerpilih').innerText = `Lat: ${eksisLat}, Lng: ${eksisLng}`;
                tempLat = eksisLat; tempLng = eksisLng;
            } else {
                if(markerPicker) mapPicker.removeLayer(markerPicker);
                markerPicker = null;
                document.getElementById('kordinatTerpilih').innerText = 'Klik pada peta untuk menetapkan titik baru';
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
            alert('Harap klik lokasi kejadian pada peta terlebih dahulu!');
        }
    });
</script>
</body>
</html>