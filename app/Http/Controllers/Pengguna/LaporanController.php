<?php

namespace App\Http\Controllers\Pengguna;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\LaporanUpdate;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    // Menampilkan riwayat laporan yang pernah dikirim daerah
    public function index()
    {
        $logLaporan = Laporan::where('user_id', Auth::id())
                    ->with('updates')
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('pengguna.laporan.index', compact('logLaporan'));
    }

    // MARKUP
    public function peta()
    {
        
        $semuaLaporan = Laporan::with(['user', 'updates' => function($query) {
            $query->orderBy('id', 'asc');
        }])->where('status', 'Aktif')->get();

        return view('pengguna.laporan.peta', compact('semuaLaporan'));
    }

    // Menampilkan halaman form tambah laporan
    public function create()
    {
        return view('pengguna.laporan.create');
    }

    
    public function store(Request $request)
    {
        $laporan = Laporan::create([
            'user_id' => Auth::id(),
            'jenis_bencana' => $request->jenis_bencana,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'Aktif',
        ]);

        // Untuk mengupload lebih dari 1 foto
        $fotoPath = null;
        if ($request->hasFile('foto_dokumentasi')) {
            $path_fotos = [];
            
            foreach ($request->file('foto_dokumentasi') as $foto) {
                $path_fotos[] = $foto->store('sitrep_lampiran', 'public');
            }
            
            $fotoPath = json_encode($path_fotos);
        }

        LaporanUpdate::create([
            'laporan_id' => $laporan->id,
            'update_ke' => 1,
            'tanggal_sitrep' => $request->tanggal_sitrep,
            'wk_waktu' => json_encode($request->wk_waktu ?? []),
            'wk_kejadian' => json_encode($request->wk_kejadian ?? []),
            'wk_lokasi' => json_encode($request->wk_lokasi ?? []),
            'wk_latitude' => json_encode($request->wk_latitude ?? []),
            'wk_longitude' => json_encode($request->wk_longitude ?? []),
            'dampak_meninggal' => $request->dampak_meninggal ?? 0,
            'dampak_luka' => $request->dampak_luka ?? 0,
            'dampak_hilang' => $request->dampak_hilang ?? 0,
            'dampak_pengungsi' => $request->dampak_pengungsi ?? 0,
            'dampak_terdampak' => $request->dampak_terdampak ?? 0,
            'dampak_material' => $request->dampak_material,
            'lokasi_poskor' => $request->lokasi_poskor,
            'lokasi_pos_pelayanan' => $request->lokasi_pos_pelayanan,
            'kronologi' => $request->kronologi,
            'situasi_terkini' => $request->situasi_terkini,
            'resp_kluster' => json_encode($request->resp_kluster ?? []),
            'resp_lokasi' => json_encode($request->resp_lokasi ?? []),
            'resp_keterangan' => json_encode($request->resp_keterangan ?? []),
            'pm_kegiatan' => json_encode($request->pm_kegiatan ?? []),
            'pm_tanggal' => json_encode($request->pm_tanggal ?? []),
            'pm_jumlah' => json_encode($request->pm_jumlah ?? []),
            'tim_kluster' => json_encode($request->tim_kluster ?? []),
            'tim_total' => json_encode($request->tim_total ?? []),
            'tim_pulang' => json_encode($request->tim_pulang ?? []),
            'tim_bertugas' => json_encode($request->tim_bertugas ?? []),
            'tim_total_semua' => $request->tim_total_semua ?? 0,
            'tim_laki' => $request->tim_laki ?? 0,
            'tim_perempuan' => $request->tim_perempuan ?? 0,
            'asal_instansi' => $request->asal_instansi,
            'keb_item' => json_encode($request->keb_item ?? []),
            'keb_jumlah' => json_encode($request->keb_jumlah ?? []),
            'sumber_informasi' => $request->sumber_informasi,
            'cp_nama' => json_encode($request->cp_nama ?? []),
            'cp_nohp' => json_encode($request->cp_nohp ?? []),
            'rekening_donasi' => $request->rekening_donasi,
            'penutup_lokasi' => $request->penutup_lokasi,
            'penutup_tanggal' => date('Y-m-d'),
            'penutup_nama_tim' => $request->penutup_nama_tim,
            'foto_dokumentasi' => $fotoPath,
        ]);

        return redirect()->route('pengguna.laporan.index')->with('success', 'SitRep #1 berhasil dikirim dan Peta telah diperbarui!');
    }

     
    // Menampilkan halaman Detail Laporan dengan Pagination 
    public function show($id, Request $request)
    {
       
        $laporan = Laporan::with(['updates' => function($query) {
            $query->orderBy('id', 'asc'); 
        }])->findOrFail($id);
        
        $sitrepId = $request->query('sitrep');
        
        if ($sitrepId) {
            
            $currentSitrep = $laporan->updates->where('id', $sitrepId)->first();
        } else {
           
            $currentSitrep = $laporan->updates->first(); 
            
            
        }

        return view('pengguna.laporan.show', compact('laporan', 'currentSitrep'));
    }

    // DOWNLOAD PDF
    public function downloadPdf($update_id)
    {
        $sitrep = \App\Models\LaporanUpdate::with('laporan')->findOrFail($update_id);
        
       
        if ($sitrep->laporan->user_id != \Auth::id()) {
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengunduh laporan daerah lain.');
        }
        
        return view('pengguna.laporan.pdf', compact('sitrep'));
    }

    // Menampilkan halaman form update laporan
    public function createUpdate($id)
    {
        $laporan = Laporan::findOrFail($id);
        
        
        $latestSitrep = LaporanUpdate::where('laporan_id', $id)->orderBy('id', 'desc')->first(); 
        
        return view('pengguna.laporan.update_create', compact('laporan', 'latestSitrep'));
    }

    
    public function storeUpdate(Request $request, $id)
    {
        $laporan = Laporan::findOrFail($id);
        
        
        $latestSitrep = LaporanUpdate::where('laporan_id', $id)->orderBy('id', 'desc')->first();
        
        
        $fotoLama = [];
        if ($latestSitrep && $latestSitrep->foto_dokumentasi) {
            $rawFoto = $latestSitrep->foto_dokumentasi;
            if (is_string($rawFoto) && strpos($rawFoto, '[') === 0) {
                $fotoLama = json_decode($rawFoto, true) ?? [];
            } elseif (is_string($rawFoto) && !empty($rawFoto)) {
                $fotoLama = [$rawFoto];
            } elseif (is_array($rawFoto)) {
                $fotoLama = $rawFoto;
            }
        }

        
        $fotoPath = $latestSitrep ? $latestSitrep->foto_dokumentasi : null;

      
        if ($request->hasFile('foto_dokumentasi')) {
            $fotoBaru = [];
            foreach ($request->file('foto_dokumentasi') as $foto) {
                $fotoBaru[] = $foto->store('sitrep_lampiran', 'public');
            }
            
            
            $kumpulanFoto = array_merge($fotoLama, $fotoBaru);
            $fotoPath = json_encode($kumpulanFoto);
        }

      
        $updateKe = $latestSitrep ? ($latestSitrep->update_ke + 1) : 2;

        $laporan->updates()->create([
            'update_ke' => $updateKe, 
            'tanggal_sitrep' => $request->tanggal_sitrep,
            'wk_waktu' => json_encode($request->wk_waktu ?? []),
            'wk_kejadian' => json_encode($request->wk_kejadian ?? []),
            'wk_lokasi' => json_encode($request->wk_lokasi ?? []),
            'wk_latitude' => json_encode($request->wk_latitude ?? []),
            'wk_longitude' => json_encode($request->wk_longitude ?? []),
            'dampak_meninggal' => $request->dampak_meninggal ?? 0,
            'dampak_luka' => $request->dampak_luka ?? 0,
            'dampak_hilang' => $request->dampak_hilang ?? 0,
            'dampak_pengungsi' => $request->dampak_pengungsi ?? 0,
            'dampak_terdampak' => $request->dampak_terdampak ?? 0,
            'dampak_material' => $request->dampak_material,
            'lokasi_poskor' => $request->lokasi_poskor,
            'lokasi_pos_pelayanan' => $request->lokasi_pos_pelayanan,
            'kronologi' => $request->kronologi,
            'situasi_terkini' => $request->situasi_terkini,
            'resp_kluster' => json_encode($request->resp_kluster ?? []),
            'resp_lokasi' => json_encode($request->resp_lokasi ?? []),
            'resp_keterangan' => json_encode($request->resp_keterangan ?? []),
            'pm_kegiatan' => json_encode($request->pm_kegiatan ?? []),
            'pm_tanggal' => json_encode($request->pm_tanggal ?? []),
            'pm_jumlah' => json_encode($request->pm_jumlah ?? []),
            'tim_kluster' => json_encode($request->tim_kluster ?? []),
            'tim_total' => json_encode($request->tim_total ?? []),
            'tim_pulang' => json_encode($request->tim_pulang ?? []),
            'tim_bertugas' => json_encode($request->tim_bertugas ?? []),
            'tim_total_semua' => $request->tim_total_semua ?? 0,
            'tim_laki' => $request->tim_laki ?? 0,
            'tim_perempuan' => $request->tim_perempuan ?? 0,
            'asal_instansi' => $request->asal_instansi,
            'keb_item' => json_encode($request->keb_item ?? []),
            'keb_jumlah' => json_encode($request->keb_jumlah ?? []),
            'sumber_informasi' => $request->sumber_informasi,
            'cp_nama' => json_encode($request->cp_nama ?? []),
            'cp_nohp' => json_encode($request->cp_nohp ?? []),
            'rekening_donasi' => $request->rekening_donasi,
            'penutup_lokasi' => $request->penutup_lokasi,
            'penutup_tanggal' => date('Y-m-d'), 
            'penutup_nama_tim' => $request->penutup_nama_tim,
            'foto_dokumentasi' => $fotoPath, 
        ]);

        $laporan->touch(); 

        return redirect()->route('pengguna.laporan.index')
                         ->with('success', 'SitRep lanjutan berhasil ditambahkan!');
    }
}