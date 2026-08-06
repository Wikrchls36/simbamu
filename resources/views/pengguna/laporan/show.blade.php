<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Laporan - SIMBAMU</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --mdmc-blue: #0047ba; --mdmc-light-blue: #1aa4f6; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7fa; }
        
        .btn-back { transition: all 0.3s ease; }
        .btn-back:hover { transform: translateX(-5px); background-color: #e9ecef !important; border-color: #ced4da !important;}
        
        .sitrep-tab {
            transition: all 0.2s ease; border: 2px solid #0d6efd; background-color: white; color: #0d6efd;
            font-weight: 600; padding: 8px 24px; border-radius: 30px; text-decoration: none; white-space: nowrap;
        }
        .sitrep-tab:hover { background-color: #f0f7ff; color: #0d6efd; }
        .sitrep-tab.active { background-color: #0d6efd; color: white; box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3); }
        
        .document-card { background: #fff; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #eaeaea; }
        
        .doc-section-title {
            background: #0047ba; border-radius: 8px;
            padding: 10px 15px; font-weight: 700; color: white;
            margin: 35px 0 15px 0; font-size: 1rem; text-transform: uppercase;
        }
        
        .stat-box { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 12px; padding: 15px; text-align: center; }
        .stat-box strong { font-size: 20px; color: #0047ba; display: block; line-height: 1.2; }
        .stat-box span { font-size: 12px; color: #6c757d; font-weight: 600; text-transform: uppercase; }
        
        .info-label { font-size: 12px; color: #888; text-transform: uppercase; font-weight: 600; margin-bottom: 2px; }
        .info-value { font-size: 14px; color: #333; font-weight: 500; }
        .content-box { background-color: #fdfdfd; border: 1px solid #eee; padding: 15px; border-radius: 8px; text-align: justify; line-height: 1.6; color: #444; font-size: 14px; min-height: 50px;}
        
        .table-doc th { background-color: #f8f9fa; font-size: 13px; color: #555; text-align: center; vertical-align: middle;}
        .table-doc td { font-size: 14px; color: #444; vertical-align: middle;}

        /* CSS untuk Modal Image Viewer */
        .modal-image { max-width: 100%; max-height: 80vh; object-fit: contain; }
    </style>
</head>
<body>

<div class="container py-4 py-md-5" style="max-width: 1000px;">
    
    <div class="d-flex align-items-center mb-4 gap-4">
        <a href="{{ route('pengguna.laporan.index') }}" class="btn btn-light bg-white border shadow-sm rounded-pill px-4 py-2 fw-bold text-secondary btn-back flex-shrink-0">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
        <div>
            <h3 class="fw-bold text-dark m-0">Log Laporan Bencana</h3>
            <p class="text-muted m-0 mt-1"><i class="fas fa-map-marker-alt text-danger me-2"></i>{{ $laporan->user->name ?? 'MDMC Daerah' }}</p>
        </div>
    </div>

    <div class="d-flex gap-2 overflow-auto pb-3 mb-2" style="scrollbar-width: thin;">
        @php
            $sortedUpdates = $laporan->updates->sortBy('id')->values();
        @endphp
        
        @foreach($sortedUpdates as $index => $update)
            <a href="{{ route('pengguna.laporan.show', ['id' => $laporan->id, 'sitrep' => $update->id]) }}" 
               class="sitrep-tab {{ ($currentSitrep && $currentSitrep->id == $update->id) ? 'active' : '' }}">
               <i class="fas {{ $index == 0 ? 'fa-flag' : 'fa-sync-alt' }} me-2"></i> 
               SitRep {{ $index + 1 }}
            </a>
        @endforeach
    </div>

    @if($currentSitrep)
    <div class="document-card overflow-hidden mb-5">
        
        <div class="bg-light border-bottom p-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="text-muted small fw-medium">
                <i class="fas fa-clock me-1"></i> Diperbarui: 
                {{ \Carbon\Carbon::parse($currentSitrep->updated_at)->timezone('Asia/Jakarta')->locale('id')->translatedFormat('l, d F Y - H:i') }} WIB
            </div>
            @if(Auth::id() == $laporan->user_id)
            <a href="{{ route('pengguna.laporan.pdf', $currentSitrep->id) }}" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" target="_blank">
                <i class="fas fa-file-pdf me-2"></i> Download PDF
            </a>
            @endif
        </div>

        <div class="p-4 p-md-5 pt-4">
            
            <div class="d-flex flex-column flex-md-row align-items-center border-bottom pb-4 mb-4 gap-3 text-center text-md-start">
                <img src="{{ asset('images/logo-mdmc.png') }}" style="height: 80px;" onerror="this.style.display='none'">
                <div>
                    <h5 class="fw-bold m-0" style="color: #0047ba; letter-spacing: 0.5px;">MUHAMMADIYAH DISASTER MANAGEMENT CENTER</h5>
                    <h6 class="fw-bold m-0 text-dark" style="letter-spacing: 0.5px;">PIMPINAN WILAYAH KALIMANTAN BARAT</h6>
                </div>
            </div>

            <div class="doc-section-title">A. Informasi Kunci</div>
            
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <div class="info-label">Jenis Bencana</div>
                    <div class="info-value fw-bold text-dark">{{ $laporan->jenis_bencana ?? '-' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="info-label">Tanggal Keluar SitRep</div>
                    <div class="info-value fw-bold text-dark">{{ \Carbon\Carbon::parse($currentSitrep->tanggal_sitrep)->locale('id')->translatedFormat('d F Y') }}</div>
                </div>
            </div>

            <p class="fw-bold text-primary mb-2 small"><i class="fas fa-clock me-2"></i>Waktu Kejadian</p>
            <div class="table-responsive mb-4">
                <table class="table table-bordered table-doc">
                    <thead><tr><th>Waktu Kejadian</th><th>Kejadian</th><th>Lokasi</th></tr></thead>
                    <tbody>
                        @php 
                            $wk_waktu = is_string($currentSitrep->wk_waktu ?? null) ? json_decode($currentSitrep->wk_waktu, true) : ($currentSitrep->wk_waktu ?? []);
                            $wk_kejadian = is_string($currentSitrep->wk_kejadian ?? null) ? json_decode($currentSitrep->wk_kejadian, true) : ($currentSitrep->wk_kejadian ?? []);
                            $wk_lokasi = is_string($currentSitrep->wk_lokasi ?? null) ? json_decode($currentSitrep->wk_lokasi, true) : ($currentSitrep->wk_lokasi ?? []);
                        @endphp

                        @if(empty($wk_waktu) || (count($wk_waktu) == 1 && empty($wk_waktu[0])))
                            <tr><td colspan="3" class="text-center text-muted">Tidak ada data terinput</td></tr>
                        @else
                            @foreach($wk_waktu as $index => $waktu)
                                @if(!empty($waktu))
                                <tr>
                                    <td class="text-center">{{ $waktu }}</td>
                                    <td>{{ $wk_kejadian[$index] ?? '-' }}</td>
                                    <td>{{ $wk_lokasi[$index] ?? '-' }}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <p class="fw-bold text-primary mb-2 small"><i class="fas fa-users me-2"></i>Dampak</p>
            <div class="row g-2 mb-4">
                <div class="col"><div class="stat-box"><strong>{{ $currentSitrep->dampak_meninggal ?? 0 }}</strong><span>Meninggal</span></div></div>
                <div class="col"><div class="stat-box"><strong>{{ $currentSitrep->dampak_luka ?? 0 }}</strong><span>Luka-luka</span></div></div>
                <div class="col"><div class="stat-box"><strong>{{ $currentSitrep->dampak_hilang ?? 0 }}</strong><span>Hilang</span></div></div>
                <div class="col"><div class="stat-box"><strong>{{ $currentSitrep->dampak_pengungsi ?? 0 }}</strong><span>Pengungsi</span></div></div>
                <div class="col"><div class="stat-box" style="background:#eef2f7;"><strong>{{ $currentSitrep->dampak_terdampak ?? 0 }}</strong><span>Terdampak</span></div></div>
            </div>

            <div class="row mb-4">
                <div class="col-md-12 mb-3">
                    <div class="info-label mb-1">Dampak Material</div>
                    <div class="content-box m-0 p-3 bg-light border-0">{!! nl2br(e($currentSitrep->dampak_material ?? '-')) !!}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="info-label">Lokasi Poskor Muhammadiyah</div>
                    <div class="info-value bg-light p-2 rounded">{{ $currentSitrep->lokasi_poskor ?? '-' }}</div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="info-label">Lokasi Pos Pelayanan (Opsional)</div>
                    <div class="info-value bg-light p-2 rounded">{{ $currentSitrep->lokasi_pos_pelayanan ?? '-' }}</div>
                </div>
            </div>

            <div class="doc-section-title">B. Kronologi Kejadian</div>
            <div class="content-box">{!! nl2br(e($currentSitrep->kronologi ?? '-')) !!}</div>

            <div class="doc-section-title">C. Situasi Terkini</div>
            <div class="content-box">{!! nl2br(e($currentSitrep->situasi_terkini ?? '-')) !!}</div>

            <div class="doc-section-title">D. Respon Muhammadiyah</div>
            <div class="table-responsive">
                <table class="table table-bordered table-doc">
                    <thead><tr><th>Kluster</th><th>Lokasi</th><th>Penerima Manfaat / Keterangan</th></tr></thead>
                    <tbody>
                        @php 
                            $resp_kluster = is_string($currentSitrep->resp_kluster ?? null) ? json_decode($currentSitrep->resp_kluster, true) : ($currentSitrep->resp_kluster ?? []);
                            $resp_lokasi = is_string($currentSitrep->resp_lokasi ?? null) ? json_decode($currentSitrep->resp_lokasi, true) : ($currentSitrep->resp_lokasi ?? []);
                            $resp_keterangan = is_string($currentSitrep->resp_keterangan ?? null) ? json_decode($currentSitrep->resp_keterangan, true) : ($currentSitrep->resp_keterangan ?? []);
                        @endphp

                        @if(empty($resp_kluster) || (count($resp_kluster) == 1 && empty($resp_kluster[0])))
                            <tr><td colspan="3" class="text-center text-muted">Tidak ada data terinput</td></tr>
                        @else
                            @foreach($resp_kluster as $index => $kluster)
                                @if(!empty($kluster))
                                <tr>
                                    <td>{{ $kluster }}</td>
                                    <td>{{ $resp_lokasi[$index] ?? '-' }}</td>
                                    <td>{{ $resp_keterangan[$index] ?? '-' }}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="doc-section-title">E. Penerima Manfaat</div>
            <div class="table-responsive">
                <table class="table table-bordered table-doc">
                    <thead><tr><th>Kegiatan</th><th>Tanggal Kegiatan</th><th>Jumlah Penerima Manfaat</th></tr></thead>
                   <tbody>
                        @php 
                            $pm_kegiatan = is_string($currentSitrep->pm_kegiatan ?? null) ? json_decode($currentSitrep->pm_kegiatan, true) : ($currentSitrep->pm_kegiatan ?? []);
                            $pm_tanggal = is_string($currentSitrep->pm_tanggal ?? null) ? json_decode($currentSitrep->pm_tanggal, true) : ($currentSitrep->pm_tanggal ?? []);
                            $pm_jumlah = is_string($currentSitrep->pm_jumlah ?? null) ? json_decode($currentSitrep->pm_jumlah, true) : ($currentSitrep->pm_jumlah ?? []);
                        @endphp

                        @if(empty($pm_kegiatan) || (count($pm_kegiatan) == 1 && empty($pm_kegiatan[0])))
                            <tr><td colspan="3" class="text-center text-muted">Tidak ada data terinput</td></tr>
                        @else
                            @foreach($pm_kegiatan as $index => $kegiatan)
                                @if(!empty($kegiatan))
                                <tr>
                                    <td>{{ $kegiatan }}</td>
                            
                                    <td class="text-center">{{ $pm_tanggal[$index] ?? '-' }}</td>
                                    
                                    <td class="text-center">{{ $pm_jumlah[$index] ?? '-' }}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="doc-section-title">F. Tim Respon MDMC</div>
            <div class="table-responsive mb-3">
                <table class="table table-bordered table-doc">
                    <thead><tr><th>Kluster</th><th>Total</th><th>Pulang</th><th>Bertugas</th></tr></thead>
                    <tbody>
                        @php 
                            $tim_kluster = is_string($currentSitrep->tim_kluster ?? null) ? json_decode($currentSitrep->tim_kluster, true) : ($currentSitrep->tim_kluster ?? []);
                            $tim_total = is_string($currentSitrep->tim_total ?? null) ? json_decode($currentSitrep->tim_total, true) : ($currentSitrep->tim_total ?? []);
                            $tim_pulang = is_string($currentSitrep->tim_pulang ?? null) ? json_decode($currentSitrep->tim_pulang, true) : ($currentSitrep->tim_pulang ?? []);
                            $tim_bertugas = is_string($currentSitrep->tim_bertugas ?? null) ? json_decode($currentSitrep->tim_bertugas, true) : ($currentSitrep->tim_bertugas ?? []);
                        @endphp

                        @if(empty($tim_kluster) || (count($tim_kluster) == 1 && empty($tim_kluster[0])))
                            <tr><td colspan="4" class="text-center text-muted">Tidak ada data terinput</td></tr>
                        @else
                            @foreach($tim_kluster as $index => $kluster)
                                @if(!empty($kluster))
                                <tr>
                                    <td>{{ $kluster }}</td>
                                    <td class="text-center">{{ $tim_total[$index] ?? '-' }}</td>
                                    <td class="text-center">{{ $tim_pulang[$index] ?? '-' }}</td>
                                    <td class="text-center">{{ $tim_bertugas[$index] ?? '-' }}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="row bg-light border p-3 rounded-3 g-2 mx-0">
                <div class="col-md-3"><span class="info-label">Total Personil</span><br><strong>{{ $currentSitrep->tim_total_semua ?? 0 }}</strong> Orang</div>
                <div class="col-md-3"><span class="info-label">Laki-Laki</span><br><strong>{{ $currentSitrep->tim_laki ?? 0 }}</strong> Orang</div>
                <div class="col-md-3"><span class="info-label">Perempuan</span><br><strong>{{ $currentSitrep->tim_perempuan ?? 0 }}</strong> Orang</div>
                <div class="col-md-3"><span class="info-label">Asal Instansi</span><br><span class="small">{{ $currentSitrep->asal_instansi ?? '-' }}</span></div>
            </div>

            <div class="doc-section-title">G. Kebutuhan</div>
            <div class="table-responsive">
                <table class="table table-bordered table-doc">
                    <thead><tr><th>Kebutuhan</th><th>Jumlah Kebutuhan</th></tr></thead>
                    <tbody>
                        @php 
                            $keb_item = is_string($currentSitrep->keb_item ?? null) ? json_decode($currentSitrep->keb_item, true) : ($currentSitrep->keb_item ?? []);
                            $keb_jumlah = is_string($currentSitrep->keb_jumlah ?? null) ? json_decode($currentSitrep->keb_jumlah, true) : ($currentSitrep->keb_jumlah ?? []);
                        @endphp

                        @if(empty($keb_item) || (count($keb_item) == 1 && empty($keb_item[0])))
                            <tr><td colspan="2" class="text-center text-muted">Tidak ada data terinput</td></tr>
                        @else
                            @foreach($keb_item as $index => $item)
                                @if(!empty($item))
                                <tr>
                                    <td>{{ $item }}</td>
                                    <td class="text-center">{{ $keb_jumlah[$index] ?? '-' }}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="doc-section-title">H. Sumber Informasi</div>
            <div class="content-box">{{ $currentSitrep->sumber_informasi ?? '-' }}</div>

            <div class="doc-section-title">I. Contact Person</div>
            <div class="table-responsive">
                <table class="table table-bordered table-doc">
                    <thead><tr><th>Nama</th><th>No Telepon/HP</th></tr></thead>
                    <tbody>
                        @php 
                            $cp_nama = is_string($currentSitrep->cp_nama ?? null) ? json_decode($currentSitrep->cp_nama, true) : ($currentSitrep->cp_nama ?? []);
                            $cp_nohp = is_string($currentSitrep->cp_nohp ?? null) ? json_decode($currentSitrep->cp_nohp, true) : ($currentSitrep->cp_nohp ?? []);
                        @endphp

                        @if(empty($cp_nama) || (count($cp_nama) == 1 && empty($cp_nama[0])))
                            <tr><td colspan="2" class="text-center text-muted">Tidak ada data terinput</td></tr>
                        @else
                            @foreach($cp_nama as $index => $nama)
                                @if(!empty($nama))
                                <tr>
                                    <td>{{ $nama }}</td>
                                    <td class="text-center">{{ $cp_nohp[$index] ?? '-' }}</td>
                                </tr>
                                @endif
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <div class="doc-section-title">J. Rekening Penggalangan Dana</div>
            <div class="content-box">{!! nl2br(e($currentSitrep->rekening_donasi ?? '-')) !!}</div>

            <div class="doc-section-title">K. Penutup</div>
            <div class="content-box border-0 bg-transparent px-0 text-dark">
                Demikian laporan ini kami buat sebagai sumber informasi dan diharapkan dapat menjadi pertimbangan dalam pengambilan keputusan.
            </div>

            <div class="mt-4 pt-3 text-end pe-md-4">
                <p class="mb-1 text-dark">{{ $currentSitrep->penutup_lokasi ?? 'Lokasi' }}, {{ \Carbon\Carbon::parse($currentSitrep->tanggal_sitrep)->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="fw-bold text-dark m-0">Tim MDMC {{ $currentSitrep->penutup_nama_tim ?? 'Daerah' }}</p>
            </div>
            
            @php
                $lampirans = [];
                $raw_foto = $currentSitrep->foto_dokumentasi ?? null;
                
                if (is_string($raw_foto) && strpos($raw_foto, '[') === 0) {
                    $lampirans = json_decode($raw_foto, true);
                } elseif (is_string($raw_foto) && !empty($raw_foto)) {
                    $lampirans = [$raw_foto]; 
                } elseif (is_array($raw_foto)) {
                    $lampirans = $raw_foto;
                }
            @endphp

            @if(!empty($lampirans) && count($lampirans) > 0)
            <div class="mt-5 pt-4 border-top">
                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-image me-2 text-primary"></i>Lampiran Dokumentasi</h6>
                <div class="row g-3">
                    @foreach($lampirans as $idx => $lampiran)
                        <div class="col-6 col-md-4 col-lg-3">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#imageModal{{ $idx }}" class="d-block border rounded-3 overflow-hidden shadow-sm" style="height: 120px;">
                                <img src="{{ asset('storage/' . $lampiran) }}" alt="Lampiran" class="w-100 h-100 object-fit-cover bg-light">
                            </a>
                        </div>

                        <div class="modal fade" id="imageModal{{ $idx }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content bg-transparent border-0">
                                    <div class="modal-header border-0 pb-0">
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ asset('storage/' . $lampiran) }}" class="modal-image img-fluid rounded" alt="Dokumentasi Penuh">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
    @else
    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 d-flex align-items-center mt-4">
        <i class="fas fa-exclamation-triangle fs-3 me-3 text-warning"></i>
        <div>
            <h6 class="fw-bold mb-1">SitRep Belum Tersedia</h6>
            <p class="mb-0 text-muted">Belum ada update laporan yang dikirimkan oleh daerah terkait bencana ini.</p>
        </div>
    </div>
    @endif

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>