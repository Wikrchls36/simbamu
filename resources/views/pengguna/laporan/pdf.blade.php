<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Download SitRep MDMC</title>
    <style>
        /* UKURAN A4 */
        body { margin: 0; padding: 0; background-color: #ffffff; }
        #render-area { width: 794px; margin: 0; padding: 30px 40px; box-sizing: border-box; background: white; font-family: Arial, sans-serif; }

        /* KOP SURAT */
        .kop-surat { display: flex; align-items: center; border-bottom: 3px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        .kop-surat img { height: 75px; margin-right: 20px; }
        .kop-teks h2 { font-size: 18px; color: #0047ba; margin: 0 0 3px 0; }
        .kop-teks h3 { font-size: 16px; color: #000; margin: 0 0 3px 0; }
        .kop-teks p { font-size: 12px; color: #555; margin: 0; }

        /* TEKS & TABEL */
        .section-title { font-size: 13px; font-weight: bold; margin: 20px 0 10px 0; background: #e9ecef; padding: 6px 10px; border-left: 4px solid #0047ba; text-transform: uppercase; }
        .sub-title { font-size: 12px; font-weight: bold; margin: 10px 0 5px 0; color: #0047ba; }
        .content-text { font-size: 12px; line-height: 1.6; text-align: justify; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 11px; table-layout: fixed; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; vertical-align: middle; word-wrap: break-word; }
        th { background-color: #f8f9fa; text-align: center; font-weight: bold; }
        
        .info-grid { display: table; width: 100%; table-layout: fixed; margin-top: 5px; }
        .info-col { display: table-cell; width: 33.33%; padding-right: 5px; vertical-align: top; }
        .info-label { font-size: 10px; color: #666; text-transform: uppercase; font-weight: bold; display: block; margin-bottom: 3px; }

        
        .foto-grid {
            text-align: center;
            margin-top: 20px;
            width: 100%;
            font-size: 0; 
        }
        .foto-item {
            display: inline-block;
            width: 46%; 
            margin: 0 2% 20px 2%; 
            vertical-align: top;
            page-break-inside: avoid; 
            break-inside: avoid;
        }
        .foto-item img {
            max-width: 100%;
            max-height: 280px; 
            padding: 5px;
            background-color: #fff;
        }

       
        #loading { position: fixed; top:0; left:0; width:100%; height:100%; background:white; display:flex; flex-direction:column; justify-content:center; align-items:center; z-index:99; }
        .loader { border: 4px solid #f3f3f3; border-top: 4px solid #0047ba; border-radius: 50%; width: 30px; height: 30px; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>

    <div id="loading">
        <div class="loader"></div>
        <p style="font-family: Arial; color: #0047ba; margin-top: 10px; font-weight: bold;">Menyusun Ulang Halaman PDF...</p>
    </div>

    @php
    $kop_surat = '
    <div class="kop-surat">
        <img src="'.asset('images/logo-mdmc.png').'" onerror="this.style.display=\'none\'">
        <div class="kop-teks">
            <h2>MUHAMMADIYAH DISASTER MANAGEMENT CENTER</h2>
            <h3>PIMPINAN WILAYAH KALIMANTAN BARAT</h3>
        </div>
    </div>';
    @endphp

    <div id="render-area">
        
        <div class="halaman">
            {!! $kop_surat !!}

            <div class="section-title" style="margin-top: 0;">A. INFORMASI KUNCI</div>
            <div class="info-grid mb-2">
                <div class="info-col">
                    <span class="info-label">Jenis Bencana</span>
                    <strong>{{ $sitrep->laporan->jenis_bencana ?? '-' }}</strong>
                </div>
                <div class="info-col">
                    <span class="info-label">Tanggal Keluar SitRep</span>
                    <strong>{{ \Carbon\Carbon::parse($sitrep->tanggal_sitrep)->locale('id')->translatedFormat('d F Y') }}</strong>
                </div>
            </div>

            <div class="sub-title">Waktu Kejadian</div>
            <table>
                <thead><tr><th width="30%">Waktu Kejadian</th><th>Kejadian</th><th>Lokasi</th></tr></thead>
                <tbody>
                    @php 
                        $wk_waktu = is_string($sitrep->wk_waktu ?? null) ? json_decode($sitrep->wk_waktu, true) : ($sitrep->wk_waktu ?? []);
                        $wk_kejadian = is_string($sitrep->wk_kejadian ?? null) ? json_decode($sitrep->wk_kejadian, true) : ($sitrep->wk_kejadian ?? []);
                        $wk_lokasi = is_string($sitrep->wk_lokasi ?? null) ? json_decode($sitrep->wk_lokasi, true) : ($sitrep->wk_lokasi ?? []);
                    @endphp

                    @if(empty($wk_waktu) || (count($wk_waktu) == 1 && empty($wk_waktu[0])))
                        <tr><td colspan="3" align="center">Tidak ada data</td></tr>
                    @else
                        @foreach($wk_waktu as $index => $waktu)
                            @if(!empty($waktu))
                            <tr>
                                <td align="center">{{ $waktu }}</td>
                                <td>{{ $wk_kejadian[$index] ?? '-' }}</td>
                                <td>{{ $wk_lokasi[$index] ?? '-' }}</td>
                            </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>

            <div class="sub-title">Dampak</div>
            <table>
                <tr><th>Meninggal</th><th>Luka-luka</th><th>Hilang</th><th>Pengungsi</th><th>Terdampak</th></tr>
                <tr>
                    <td align="center">{{ $sitrep->dampak_meninggal ?? 0 }}</td>
                    <td align="center">{{ $sitrep->dampak_luka ?? 0 }}</td>
                    <td align="center">{{ $sitrep->dampak_hilang ?? 0 }}</td>
                    <td align="center">{{ $sitrep->dampak_pengungsi ?? 0 }}</td>
                    <td align="center"><strong>{{ $sitrep->dampak_terdampak ?? 0 }}</strong></td>
                </tr>
            </table>
            
            <div class="content-text" style="margin-top: 10px;">
                <span class="info-label">Dampak Material:</span>
                {!! nl2br(e($sitrep->dampak_material ?? '-')) !!}
            </div>

            <div class="info-grid" style="margin-top: 10px;">
                <div class="info-col" style="width: 50%;">
                    <span class="info-label">Lokasi Poskor Muhammadiyah</span>
                    <div class="content-text">{{ $sitrep->lokasi_poskor ?? '-' }}</div>
                </div>
                <div class="info-col" style="width: 50%;">
                    <span class="info-label">Lokasi Pos Pelayanan (Opsional)</span>
                    <div class="content-text">{{ $sitrep->lokasi_pos_pelayanan ?? '-' }}</div>
                </div>
            </div>

            <div class="section-title">B. KRONOLOGI KEJADIAN</div>
            <div class="content-text">{!! nl2br(e($sitrep->kronologi ?? '-')) !!}</div>

            <div class="section-title">C. SITUASI TERKINI</div>
            <div class="content-text">{!! nl2br(e($sitrep->situasi_terkini ?? '-')) !!}</div>
        </div>

        <div class="html2pdf__page-break"></div>

        <div class="halaman">
            {!! $kop_surat !!}

            <div class="section-title" style="margin-top: 0;">D. RESPON MUHAMMADIYAH</div>
            <table>
                <thead><tr><th>Kluster</th><th>Lokasi</th><th>Penerima Manfaat / Keterangan</th></tr></thead>
                <tbody>
                    @php 
                        $resp_kluster = is_string($sitrep->resp_kluster ?? null) ? json_decode($sitrep->resp_kluster, true) : ($sitrep->resp_kluster ?? []);
                        $resp_lokasi = is_string($sitrep->resp_lokasi ?? null) ? json_decode($sitrep->resp_lokasi, true) : ($sitrep->resp_lokasi ?? []);
                        $resp_keterangan = is_string($sitrep->resp_keterangan ?? null) ? json_decode($sitrep->resp_keterangan, true) : ($currentSitrep->resp_keterangan ?? []);
                    @endphp

                    @if(empty($resp_kluster) || (count($resp_kluster) == 1 && empty($resp_kluster[0])))
                        <tr><td colspan="3" align="center">Tidak ada data</td></tr>
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

            <div class="section-title">E. PENERIMA MANFAAT</div>
            <table>
                <thead><tr><th>Kegiatan</th><th>Tanggal Kegiatan</th><th>Jumlah Penerima Manfaat</th></tr></thead>
                <tbody>
                    @php 
                        $pm_kegiatan = is_string($sitrep->pm_kegiatan ?? null) ? json_decode($sitrep->pm_kegiatan, true) : ($sitrep->pm_kegiatan ?? []);
                        $pm_tanggal = is_string($sitrep->pm_tanggal ?? null) ? json_decode($sitrep->pm_tanggal, true) : ($sitrep->pm_tanggal ?? []);
                        $pm_jumlah = is_string($sitrep->pm_jumlah ?? null) ? json_decode($sitrep->pm_jumlah, true) : ($sitrep->pm_jumlah ?? []);
                    @endphp

                    @if(empty($pm_kegiatan) || (count($pm_kegiatan) == 1 && empty($pm_kegiatan[0])))
                        <tr><td colspan="3" align="center">Tidak ada data</td></tr>
                    @else
                        @foreach($pm_kegiatan as $index => $kegiatan)
                            @if(!empty($kegiatan))
                            <tr>
                                <td>{{ $kegiatan }}</td>
                                <td align="center">{{ $pm_tanggal[$index] ?? '-' }}</td>
                                <td align="center">{{ $pm_jumlah[$index] ?? '-' }}</td>
                            </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>

            <div class="section-title">F. TIM RESPON MDMC</div>
            <table>
                <thead><tr><th>Kluster</th><th>Total</th><th>Pulang</th><th>Bertugas</th></tr></thead>
                <tbody>
                    @php 
                        $tim_kluster = is_string($sitrep->tim_kluster ?? null) ? json_decode($sitrep->tim_kluster, true) : ($sitrep->tim_kluster ?? []);
                        $tim_total = is_string($sitrep->tim_total ?? null) ? json_decode($sitrep->tim_total, true) : ($sitrep->tim_total ?? []);
                        $tim_pulang = is_string($sitrep->tim_pulang ?? null) ? json_decode($sitrep->tim_pulang, true) : ($sitrep->tim_pulang ?? []);
                        $tim_bertugas = is_string($sitrep->tim_bertugas ?? null) ? json_decode($sitrep->tim_bertugas, true) : ($sitrep->tim_bertugas ?? []);
                    @endphp

                    @if(empty($tim_kluster) || (count($tim_kluster) == 1 && empty($tim_kluster[0])))
                        <tr><td colspan="4" align="center">Tidak ada data</td></tr>
                    @else
                        @foreach($tim_kluster as $index => $kluster)
                            @if(!empty($kluster))
                            <tr>
                                <td>{{ $kluster }}</td>
                                <td align="center">{{ $tim_total[$index] ?? '-' }}</td>
                                <td align="center">{{ $tim_pulang[$index] ?? '-' }}</td>
                                <td align="center">{{ $tim_bertugas[$index] ?? '-' }}</td>
                            </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>
            
            <div class="info-grid" style="background: #f8f9fa; padding: 8px; border: 1px solid #ddd; width: 100%; display: flex; margin-top: 5px;">
                <div style="flex: 1;"><span class="info-label">Total Personil</span><strong>{{ $sitrep->tim_total_semua ?? 0 }}</strong></div>
                <div style="flex: 1;"><span class="info-label">Laki-Laki</span><strong>{{ $sitrep->tim_laki ?? 0 }}</strong></div>
                <div style="flex: 1;"><span class="info-label">Perempuan</span><strong>{{ $sitrep->tim_perempuan ?? 0 }}</strong></div>
                <div style="flex: 2;"><span class="info-label">Asal Instansi</span><span>{{ $sitrep->asal_instansi ?? '-' }}</span></div>
            </div>
        </div>

        <div class="html2pdf__page-break"></div>

        <div class="halaman">
            {!! $kop_surat !!}

            <div class="section-title" style="margin-top: 0;">G. KEBUTUHAN</div>
            <table>
                <thead><tr><th>Kebutuhan</th><th>Jumlah Kebutuhan</th></tr></thead>
                <tbody>
                    @php 
                        $keb_item = is_string($sitrep->keb_item ?? null) ? json_decode($sitrep->keb_item, true) : ($sitrep->keb_item ?? []);
                        $keb_jumlah = is_string($sitrep->keb_jumlah ?? null) ? json_decode($sitrep->keb_jumlah, true) : ($sitrep->keb_jumlah ?? []);
                    @endphp

                    @if(empty($keb_item) || (count($keb_item) == 1 && empty($keb_item[0])))
                        <tr><td colspan="2" align="center">Tidak ada data</td></tr>
                    @else
                        @foreach($keb_item as $index => $item)
                            @if(!empty($item))
                            <tr>
                                <td>{{ $item }}</td>
                                <td align="center">{{ $keb_jumlah[$index] ?? '-' }}</td>
                            </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>

            <div class="section-title">H. SUMBER INFORMASI</div>
            <div class="content-text">{{ $sitrep->sumber_informasi ?? '-' }}</div>

            <div class="section-title">I. CONTACT PERSON</div>
            <table>
                <thead><tr><th>Nama</th><th>No Telepon/HP</th></tr></thead>
                <tbody>
                    @php 
                        $cp_nama = is_string($sitrep->cp_nama ?? null) ? json_decode($sitrep->cp_nama, true) : ($sitrep->cp_nama ?? []);
                        $cp_nohp = is_string($sitrep->cp_nohp ?? null) ? json_decode($sitrep->cp_nohp, true) : ($sitrep->cp_nohp ?? []);
                    @endphp

                    @if(empty($cp_nama) || (count($cp_nama) == 1 && empty($cp_nama[0])))
                        <tr><td colspan="2" align="center">Tidak ada data</td></tr>
                    @else
                        @foreach($cp_nama as $index => $nama)
                            @if(!empty($nama))
                            <tr>
                                <td>{{ $nama }}</td>
                                <td align="center">{{ $cp_nohp[$index] ?? '-' }}</td>
                            </tr>
                            @endif
                        @endforeach
                    @endif
                </tbody>
            </table>

            <div class="section-title">J. REKENING PENGGALANGAN DANA</div>
            <div class="content-text">{!! nl2br(e($sitrep->rekening_donasi ?? '-')) !!}</div>

            <div class="section-title">K. PENUTUP</div>
            <div class="content-text">Demikian laporan ini kami buat sebagai sumber informasi dan diharapkan dapat menjadi pertimbangan dalam pengambilan keputusan.</div>
            
            <div class="text-end" style="margin-top: 40px; text-align: right;">
                <p>{{ $sitrep->penutup_lokasi ?? 'Lokasi' }}, {{ \Carbon\Carbon::parse($sitrep->tanggal_sitrep)->locale('id')->translatedFormat('d F Y') }}</p>
                <p class="fw-bold" style="margin-top: 5px;">Tim MDMC {{ $sitrep->penutup_nama_tim ?? 'Daerah' }}</p>
            </div>
        </div>

        @if(!empty($sitrep->foto_dokumentasi))
        <div class="html2pdf__page-break"></div>
        <div class="halaman">
            {!! $kop_surat !!}
            <div class="section-title" style="margin-top: 0;">L. LAMPIRAN DOKUMENTASI</div>
            
            <div class="foto-grid">
                @php
                    $daftar_foto = [];
                    $raw_foto = $sitrep->foto_dokumentasi;

                    if (is_string($raw_foto) && strpos($raw_foto, '[') === 0) {
                        $daftar_foto = json_decode($raw_foto, true);
                    } elseif (is_string($raw_foto) && !empty($raw_foto)) {
                        $daftar_foto = [$raw_foto];
                    } elseif (is_array($raw_foto)) {
                        $daftar_foto = $raw_foto;
                    }
                @endphp

                @if(!empty($daftar_foto) && is_array($daftar_foto))
                    @foreach($daftar_foto as $foto_path)
                        @php
                            $path = storage_path('app/public/' . $foto_path);
                            $base64 = null;
                            if(file_exists($path)) {
                                $type = pathinfo($path, PATHINFO_EXTENSION);
                                $data = file_get_contents($path);
                                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                            }
                        @endphp

                        @if($base64)
                            <div class="foto-item">
                                <img src="{{ $base64 }}">
                            </div>
                        @endif
                    @endforeach
                @else
                    <p style="color: #555; margin-top: 50px; text-align: center; width: 100%; font-size: 14px;"><i>File foto tidak ditemukan atau format tidak valid di dalam server.</i></p>
                @endif
            </div>
        </div>
        @endif

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        window.onload = function() {
            const element = document.getElementById('render-area');
            const opt = {
                margin: 0, 
                filename: 'SitRep_{{ $sitrep->laporan->jenis_bencana ?? "Bencana" }}_{{ date("Ymd") }}.pdf',
                image: { type: 'jpeg', quality: 0.98 },
                html2canvas: { scale: 2, useCORS: true, width: 794 },
                jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak: { mode: ['css', 'legacy'] }
            };

            html2pdf().set(opt).from(element).save().then(() => {
                setTimeout(() => { window.close(); }, 1000);
            });
        }
    </script>
</body>
</html>