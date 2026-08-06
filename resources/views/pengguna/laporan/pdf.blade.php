<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>SitRep MDMC - {{ $sitrep->laporan->jenis_bencana ?? 'Bencana' }}</title>
    <style>
        
        body { margin: 0; padding: 20px; background-color: #525659; font-family: Arial, sans-serif; display: flex; justify-content: center; }
        #document-wrapper { width: 210mm; min-height: 297mm; background: white; padding: 15mm; box-sizing: border-box; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .kop-surat { display: flex; align-items: center; border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 15px; }
        .kop-surat img { height: 70px; margin-right: 20px; }
        .kop-teks h2 { font-size: 18px; color: #0047ba; margin: 0 0 3px 0; font-weight: bold; }
        .kop-teks h3 { font-size: 16px; color: #000; margin: 0 0 3px 0; font-weight: bold; }
        .section-title { font-size: 13px; font-weight: bold; margin: 15px 0 10px 0; background: #e9ecef; padding: 6px 10px; border-left: 4px solid #0047ba; text-transform: uppercase; }
        .content-text { font-size: 12px; line-height: 1.6; text-align: justify; }
        table.data-table { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 11px; table-layout: fixed; }
        table.data-table th, table.data-table td { border: 1px solid #000; padding: 6px 8px; text-align: left; vertical-align: middle; word-wrap: break-word; }
        table.data-table th { background-color: #f8f9fa; text-align: center; font-weight: bold; }
        .info-grid { display: table; width: 100%; table-layout: fixed; margin-top: 5px; }
        .info-col { display: table-cell; width: 33.33%; padding-right: 5px; vertical-align: top; }
        .info-label { font-size: 10px; color: #666; text-transform: uppercase; font-weight: bold; display: block; margin-bottom: 3px; }
        .foto-grid { text-align: center; margin-top: 20px; width: 100%; }
        .foto-item { display: inline-block; width: 45%; margin: 10px; vertical-align: top; }
        .foto-item img { max-width: 100%; max-height: 250px; border: 1px solid #000; padding: 2px; }

       
        @media print {
            body { background-color: #fff; padding: 0; display: block; }
            #document-wrapper { width: 100%; max-width: 100%; padding: 0; box-shadow: none; }
            thead { display: table-header-group; }
            tr, .blok-aman, .foto-item { page-break-inside: avoid; break-inside: avoid; }
            @page { size: A4 portrait; margin: 15mm; }
        }
    </style>
</head>
<body>

    <div id="document-wrapper">
        <table style="width: 100%; border: none;">
            <thead>
                <tr>
                    <td style="border: none; padding: 0;">
                        <div class="kop-surat">
                            <img src="{{ asset('images/logo-mdmc.png') }}" onerror="this.style.display='none'">
                            <div class="kop-teks">
                                <h2>MUHAMMADIYAH DISASTER MANAGEMENT CENTER</h2>
                                <h3>PIMPINAN WILAYAH KALIMANTAN BARAT</h3>
                            </div>
                        </div>
                    </td>
                </tr>
            </thead>
            
            <tbody>
                <tr>
                    <td style="border: none; padding: 0;">
                        
                        <div class="blok-aman">
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

                            <span class="info-label" style="color: #0047ba; margin-top: 10px;">Waktu Kejadian</span>
                            <table class="data-table">
                                <thead><tr><th width="20%">Waktu</th><th>Kejadian</th><th>Lokasi</th><th width="22%">Koordinat</th></tr></thead>
                                <tbody>
                                    @php 
                                        $wk_waktu = is_string($sitrep->wk_waktu ?? null) ? json_decode($sitrep->wk_waktu, true) : ($sitrep->wk_waktu ?? []);
                                        $wk_kejadian = is_string($sitrep->wk_kejadian ?? null) ? json_decode($sitrep->wk_kejadian, true) : ($sitrep->wk_kejadian ?? []);
                                        $wk_lokasi = is_string($sitrep->wk_lokasi ?? null) ? json_decode($sitrep->wk_lokasi, true) : ($sitrep->wk_lokasi ?? []);
                                        $wk_lat = is_string($sitrep->wk_latitude ?? null) ? json_decode($sitrep->wk_latitude, true) : ($sitrep->wk_latitude ?? []);
                                        $wk_lng = is_string($sitrep->wk_longitude ?? null) ? json_decode($sitrep->wk_longitude, true) : ($sitrep->wk_longitude ?? []);
                                    @endphp
                                    @foreach($wk_waktu as $index => $waktu)
                                        @if(!empty($waktu))
                                        <tr>
                                            <td align="center">{{ $waktu }}</td>
                                            <td>{{ $wk_kejadian[$index] ?? '-' }}</td>
                                            <td>{{ $wk_lokasi[$index] ?? '-' }}</td>
                                            <td align="center" style="font-size: 9px; font-family: monospace;">{{ ($wk_lat[$index] ?? '-') }},<br>{{ ($wk_lng[$index] ?? '-') }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="blok-aman" style="margin-top: 15px;">
                            <span class="info-label" style="color: #0047ba;">Dampak</span>
                            <table class="data-table">
                                <tr><th>Meninggal</th><th>Luka-luka</th><th>Hilang</th><th>Pengungsi</th><th>Terdampak</th></tr>
                                <tr>
                                    <td align="center">{{ $sitrep->dampak_meninggal ?? 0 }}</td>
                                    <td align="center">{{ $sitrep->dampak_luka ?? 0 }}</td>
                                    <td align="center">{{ $sitrep->dampak_hilang ?? 0 }}</td>
                                    <td align="center">{{ $sitrep->dampak_pengungsi ?? 0 }}</td>
                                    <td align="center"><strong>{{ $sitrep->dampak_terdampak ?? 0 }}</strong></td>
                                </tr>
                            </table>
                            
                            <div class="content-text" style="margin-top: 8px;">
                                <span class="info-label">Dampak Material</span>
                                {!! nl2br(e($sitrep->dampak_material ?? 'Tidak ada laporan kerusakan material.')) !!}
                            </div>
                            
                            <div class="info-grid" style="margin-top: 12px;">
                                <div class="info-col" style="width: 50%;">
                                    <span class="info-label">Lokasi Poskor Muhammadiyah</span>
                                    <div class="content-text">{!! nl2br(e($sitrep->lokasi_poskor ?? '-')) !!}</div>
                                </div>
                                <div class="info-col" style="width: 50%;">
                                    <span class="info-label">Lokasi Pos Pelayanan (Opsional)</span>
                                    <div class="content-text">{!! nl2br(e($sitrep->lokasi_pos_pelayanan ?? '-')) !!}</div>
                                </div>
                            </div>
                        </div>

                        <div class="blok-aman">
                            <div class="section-title">B. KRONOLOGI KEJADIAN</div>
                            <div class="content-text">{!! nl2br(e($sitrep->kronologi ?? '-')) !!}</div>
                        </div>

                        <div class="blok-aman">
                            <div class="section-title">C. SITUASI TERKINI</div>
                            <div class="content-text">{!! nl2br(e($sitrep->situasi_terkini ?? '-')) !!}</div>
                        </div>

                        <div class="blok-aman">
                            <div class="section-title">D. RESPON MUHAMMADIYAH</div>
                            <table class="data-table">
                                <thead><tr><th>Kluster</th><th>Lokasi</th><th>Penerima Manfaat / Keterangan</th></tr></thead>
                                <tbody>
                                    @php 
                                        $resp_kluster = is_string($sitrep->resp_kluster ?? null) ? json_decode($sitrep->resp_kluster, true) : ($sitrep->resp_kluster ?? []);
                                        $resp_lokasi = is_string($sitrep->resp_lokasi ?? null) ? json_decode($sitrep->resp_lokasi, true) : ($sitrep->resp_lokasi ?? []);
                                        $resp_keterangan = is_string($sitrep->resp_keterangan ?? null) ? json_decode($sitrep->resp_keterangan, true) : ($sitrep->resp_keterangan ?? []);
                                    @endphp
                                    @foreach($resp_kluster as $index => $kluster)
                                        @if(!empty($kluster))
                                        <tr><td>{{ $kluster }}</td><td>{{ $resp_lokasi[$index] ?? '-' }}</td><td>{{ $resp_keterangan[$index] ?? '-' }}</td></tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="blok-aman">
                            <div class="section-title">E. PENERIMA MANFAAT</div>
                            <table class="data-table">
                                <thead><tr><th>Kegiatan</th><th>Tanggal Kegiatan</th><th>Jumlah Penerima Manfaat </th></tr></thead>
                                <tbody>
                                    @php 
                                        $pm_kegiatan = is_string($sitrep->pm_kegiatan ?? null) ? json_decode($sitrep->pm_kegiatan, true) : ($sitrep->pm_kegiatan ?? []);
                                        $pm_tanggal = is_string($sitrep->pm_tanggal ?? null) ? json_decode($sitrep->pm_tanggal, true) : ($sitrep->pm_tanggal ?? []);
                                        $pm_jumlah = is_string($sitrep->pm_jumlah ?? null) ? json_decode($sitrep->pm_jumlah, true) : ($sitrep->pm_jumlah ?? []);
                                    @endphp
                                    @foreach($pm_kegiatan as $index => $kegiatan)
                                        @if(!empty($kegiatan))
                                        <tr>
                                            <td>{{ $kegiatan }}</td>
                                            <td align="center">{{ isset($pm_tanggal[$index]) && !empty($pm_tanggal[$index]) ? \Carbon\Carbon::parse($pm_tanggal[$index])->format('d-m-Y') : '-' }}</td>
                                            <td align="center">{{ $pm_jumlah[$index] ?? '-' }}</td>
                                        </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="blok-aman">
                            <div class="section-title">F. TIM RESPON MDMC</div>
                            <table class="data-table">
                                <thead><tr><th>Kluster</th><th>Total</th><th>Pulang</th><th>Bertugas</th></tr></thead>
                                <tbody>
                                    @php 
                                        $tim_kluster = is_string($sitrep->tim_kluster ?? null) ? json_decode($sitrep->tim_kluster, true) : ($sitrep->tim_kluster ?? []);
                                        $tim_total = is_string($sitrep->tim_total ?? null) ? json_decode($sitrep->tim_total, true) : ($sitrep->tim_total ?? []);
                                        $tim_pulang = is_string($sitrep->tim_pulang ?? null) ? json_decode($sitrep->tim_pulang, true) : ($sitrep->tim_pulang ?? []);
                                        $tim_bertugas = is_string($sitrep->tim_bertugas ?? null) ? json_decode($sitrep->tim_bertugas, true) : ($sitrep->tim_bertugas ?? []);
                                    @endphp
                                    @if(is_array($tim_kluster))
                                        @foreach($tim_kluster as $index => $kluster)
                                            @if(!empty($kluster))
                                            <tr>
                                                <td>{{ $kluster }}</td>
                                                <td align="center">{{ $tim_total[$index] ?? '0' }}</td>
                                                <td align="center">{{ $tim_pulang[$index] ?? '0' }}</td>
                                                <td align="center">{{ $tim_bertugas[$index] ?? '0' }}</td>
                                            </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>

                            <div class="info-grid" style="margin-top: 10px; background-color: #f8f9fa; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                                <div class="info-col" style="width: 25%;">
                                    <span class="info-label">Total</span>
                                    <strong>{{ $sitrep->tim_total_semua ?? '0' }}</strong> Orang
                                </div>
                                <div class="info-col" style="width: 25%;">
                                    <span class="info-label">Laki-Laki</span>
                                    <strong>{{ $sitrep->tim_laki ?? '0' }}</strong> Orang
                                </div>
                                <div class="info-col" style="width: 25%;">
                                    <span class="info-label">Perempuan</span>
                                    <strong>{{ $sitrep->tim_perempuan ?? '0' }}</strong> Orang
                                </div>
                                <div class="info-col" style="width: 25%;">
                                    <span class="info-label">Asal Instansi</span>
                                    <strong>{{ $sitrep->asal_instansi ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="blok-aman">
                            <div class="section-title">G. KEBUTUHAN</div>
                            <table class="data-table">
                                <thead><tr><th>Kebutuhan</th><th>Jumlah Kebutuhan</th></tr></thead>
                                <tbody>
                                    @php 
                                        $keb_item = is_string($sitrep->keb_item ?? null) ? json_decode($sitrep->keb_item, true) : ($sitrep->keb_item ?? []);
                                        $keb_jumlah = is_string($sitrep->keb_jumlah ?? null) ? json_decode($sitrep->keb_jumlah, true) : ($sitrep->keb_jumlah ?? []);
                                    @endphp
                                    @foreach($keb_item as $index => $item)
                                        @if(!empty($item))
                                        <tr><td>{{ $item }}</td><td align="center">{{ $keb_jumlah[$index] ?? '-' }}</td></tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="blok-aman">
                            <div class="section-title">H. SUMBER INFORMASI</div>
                            <div class="content-text">
                                {!! nl2br(e($sitrep->sumber_informasi ?? '-')) !!}
                            </div>
                        </div>

                        <div class="blok-aman">
                            <div class="section-title">I. CONTACT PERSON</div>
                            <table class="data-table">
                                <thead><tr><th>Petugas</th><th>No. Telepon / HP</th></tr></thead>
                                <tbody>
                                    @php 
                                        $cp_nama = is_string($sitrep->cp_nama ?? null) ? json_decode($sitrep->cp_nama, true) : ($sitrep->cp_nama ?? []);
                                        $cp_nohp = is_string($sitrep->cp_nohp ?? null) ? json_decode($sitrep->cp_nohp, true) : ($sitrep->cp_nohp ?? []);
                                    @endphp
                                    @foreach($cp_nama as $index => $nama)
                                        @if(!empty($nama))
                                        <tr><td>{{ $nama }}</td><td align="center">{{ $cp_nohp[$index] ?? '-' }}</td></tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="blok-aman">
                            <div class="section-title">J. REKENING PENGGALANGAN DANA</div>
                            <div class="content-text">
                                {!! nl2br(e($sitrep->rekening_donasi ?? '-')) !!}
                            </div>
                        </div>

                        <div class="blok-aman">
                            <div class="section-title">K. PENUTUP</div>
                            <div class="content-text">Demikian laporan ini kami buat sebagai sumber informasi dan diharapkan dapat menjadi pertimbangan dalam pengambilan keputusan.</div>
                            
                            <div style="margin-top: 30px; text-align: right; padding-right: 20px;">
                                <p style="margin-bottom: 45px;">{{ $sitrep->penutup_lokasi ?? 'Lokasi' }}, {{ \Carbon\Carbon::parse($sitrep->tanggal_sitrep)->locale('id')->translatedFormat('d F Y') }}</p>
                                <p class="fw-bold" style="text-decoration: underline;">Tim MDMC {{ $sitrep->penutup_nama_tim ?? 'Daerah' }}</p>
                            </div>
                        </div>

                        @php
                            $daftar_foto = [];
                            $raw_foto = $sitrep->foto_dokumentasi;
                            if (is_string($raw_foto) && strpos($raw_foto, '[') === 0) { $daftar_foto = json_decode($raw_foto, true); }
                            elseif (is_string($raw_foto) && !empty($raw_foto)) { $daftar_foto = [$raw_foto]; }
                            elseif (is_array($raw_foto)) { $daftar_foto = $raw_foto; }
                        @endphp

                        @if(!empty($daftar_foto) && is_array($daftar_foto))
                        <div class="blok-aman">
                            <div class="section-title">L. LAMPIRAN DOKUMENTASI</div>
                            <div class="foto-grid">
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
                                        <div class="foto-item"><img src="{{ $base64 }}"></div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif

                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);

            
            window.onafterprint = function() {
                window.close();
            };
        }
    </script>
</body>
</html>