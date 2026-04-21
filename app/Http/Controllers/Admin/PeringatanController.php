<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peringatan;
use App\Models\User;
use Illuminate\Http\Request;

class PeringatanController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'daerah')->orderBy('name', 'asc')->get();
        $peringatans = Peringatan::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.peringatan.index', compact('users', 'peringatans'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tingkat_potensi' => 'required|in:Monitoring,Siaga,Waspada',
            'instruksi' => 'required|string',
        ]);

        // 2. Simpan ke Database (Pastikan kolom 'status_konfirmasi' sesuai migration)
        Peringatan::create([
            'user_id' => $request->user_id,
            'tingkat_potensi' => $request->tingkat_potensi,
            'instruksi' => $request->instruksi,
            'status_konfirmasi' => 'Belum Direspon',
        ]);

        // 3. Ambil data penerima
        $penerima = User::find($request->user_id);

        // PENGECEKAN: Menggunakan 'no_whatsapp' sesuai struktur databasemu
        if ($penerima && $penerima->no_whatsapp) {
            
            // Format pesan WhatsApp
            $pesanWa = "*⚠️ PERINGATAN BENCANA - SIMBAMU ⚠️*\n\n";
            $pesanWa .= "Halo *{$penerima->name}*,\n";
            $pesanWa .= "Terdapat informasi peringatan dini untuk daerah Anda.\n\n";
            $pesanWa .= "🚨 *Status :* {$request->tingkat_potensi}\n";
            $pesanWa .= "📋 *Instruksi:*\n{$request->instruksi}\n\n";
            $pesanWa .= "Mohon segera cek dashboard SIMBAMU untuk melakukan konfirmasi.\n";
            $pesanWa .= "_Pesan ini dikirim otomatis oleh sistem SIMBAMU MDMC Wilayah Kalbar._";

            // 4. PROSES KIRIM WA - MENGGUNAKAN CURL (LEBIH STABIL DI LOCALHOST)
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $penerima->no_whatsapp, // Menggunakan no_whatsapp
                    'message' => $pesanWa,
                    'countryCode' => '62',
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: y4FU3UJn1JZQdvhzLvGe' // Token Fonnte kamu
                ),
                CURLOPT_SSL_VERIFYPEER => false, // Mengabaikan SSL di Localhost agar tembus
            ));

            $response = curl_exec($curl);
            curl_close($curl);
        }

        return redirect()->back()->with('success', 'Peringatan berhasil dikirim ke Dashboard dan WhatsApp Daerah!');
    }
}

