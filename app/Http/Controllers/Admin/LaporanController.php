<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;

class LaporanController extends Controller
{
    // Menampilkan Halaman Laporan
    public function index()
    {
        // Ambil semua laporan beserta nama pelapornya
        // Diurutkan berdasarkan laporan yang paling baru di-update (updated_at)
        $laporans = Laporan::with('user')->orderBy('updated_at', 'desc')->get();

        return view('admin.laporan.index', compact('laporans'));
    }

    // Menampilkan Peta Penyebaran Laporan Bencana
    public function peta()
    {
        // Ambil HANYA laporan yang berstatus 'Aktif'
        $laporans = Laporan::with('user')->where('status', 'Aktif')->latest()->get();
        return view('admin.laporan.peta', compact('laporans'));
    }
    // Fungsi Tombol Konfirmasi "Selesai" (Centang Hijau)
    public function tandaiSelesai($id)
    {
        $laporan = Laporan::findOrFail($id);
        $laporan->update([
            'status' => 'Selesai'
        ]);

        return redirect()->back()->with('success', 'Laporan bencana berhasil dikonfirmasi sebagai Selesai / Kondusif.');
    }

    // Fungsi untuk memproses data dari Form Update SitRep
    public function storeUpdate(Request $request, $id)
    {
        // 1. Cari laporan induknya
        $laporan = \App\Models\Laporan::findOrFail($id);

        // 2. Proses semua tabel dinamis menjadi format JSON
        $waktu_kejadian = [];
        if ($request->has('wk_waktu')) {
            foreach ($request->wk_waktu as $i => $waktu) {
                if (!empty($waktu)) {
                    $waktu_kejadian[] = [
                        'waktu' => $waktu, 
                        'kejadian' => $request->wk_kejadian[$i] ?? '', 
                        'lokasi' => $request->wk_lokasi[$i] ?? ''
                    ];
                }
            }
        }

        $respon_muhammadiyah = [];
        if ($request->has('resp_kluster')) {
            foreach ($request->resp_kluster as $i => $kluster) {
                if (!empty($kluster)) {
                    $respon_muhammadiyah[] = [
                        'kluster' => $kluster, 
                        'lokasi' => $request->resp_lokasi[$i] ?? '', 
                        'keterangan' => $request->resp_keterangan[$i] ?? ''
                    ];
                }
            }
        }

        $penerima_manfaat = [];
        if ($request->has('pm_kegiatan')) {
            foreach ($request->pm_kegiatan as $i => $kegiatan) {
                if (!empty($kegiatan)) {
                    $penerima_manfaat[] = [
                        'kegiatan' => $kegiatan, 
                        'tanggal' => $request->pm_tanggal[$i] ?? '', 
                        'jumlah' => $request->pm_jumlah[$i] ?? ''
                    ];
                }
            }
        }

        $tim_respon = [];
        if ($request->has('tim_kluster')) {
            foreach ($request->tim_kluster as $i => $kluster) {
                if (!empty($kluster)) {
                    $tim_respon[] = [
                        'kluster' => $kluster, 
                        'total' => $request->tim_total[$i] ?? 0, 
                        'pulang' => $request->tim_pulang[$i] ?? 0,
                        'bertugas' => $request->tim_bertugas[$i] ?? 0
                    ];
                }
            }
        }

        $kebutuhan = [];
        if ($request->has('keb_item')) {
            foreach ($request->keb_item as $i => $item) {
                if (!empty($item)) {
                    $kebutuhan[] = [
                        'item' => $item, 
                        'jumlah' => $request->keb_jumlah[$i] ?? ''
                    ];
                }
            }
        }

        $contact_person = [];
        if ($request->has('cp_nama')) {
            foreach ($request->cp_nama as $i => $nama) {
                if (!empty($nama)) {
                    $contact_person[] = [
                        'nama' => $nama, 
                        'nohp' => $request->cp_nohp[$i] ?? ''
                    ];
                }
            }
        }

        // 3. Proses Upload File Dokumentasi Baru (Jika ada)
        $latestSitrep = $laporan->updates->last();
        $fotoPath = $latestSitrep ? $latestSitrep->foto_dokumentasi : null; // Bawa foto lama sebagai default

        if ($request->hasFile('foto_dokumentasi')) {
            $fotoPath = $request->file('foto_dokumentasi')->store('dokumentasi_sitrep', 'public');
        }

        // 4. Simpan ke database sebagai SitRep / Update Baru menggunakan relasi
        $laporan->updates()->create([
            'tanggal_sitrep' => $request->tanggal_sitrep,
            'waktu_kejadian' => json_encode($waktu_kejadian),
            
            // Dampak
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
            
            'respon_muhammadiyah' => json_encode($respon_muhammadiyah),
            'penerima_manfaat' => json_encode($penerima_manfaat),
            'tim_respon' => json_encode($tim_respon),
            
            // Rekap Tim
            'tim_total_semua' => $request->tim_total_semua ?? 0,
            'tim_laki' => $request->tim_laki ?? 0,
            'tim_perempuan' => $request->tim_perempuan ?? 0,
            'asal_instansi' => $request->asal_instansi,
            
            'kebutuhan' => json_encode($kebutuhan),
            'sumber_informasi' => $request->sumber_informasi,
            'contact_person' => json_encode($contact_person),
            'rekening_donasi' => $request->rekening_donasi,
            
            // Penutup
            'penutup_lokasi' => $request->penutup_lokasi,
            'penutup_nama_tim' => $request->penutup_nama_tim,
            'foto_dokumentasi' => $fotoPath,
        ]);

        // 5. Perbarui timestamp 'updated_at' di tabel laporan induk agar muncul paling atas di urutan terbaru
        $laporan->touch();

        return redirect()->route('pengguna.laporan.index')
                         ->with('success', 'SitRep lanjutan berhasil ditambahkan!');
    }
}