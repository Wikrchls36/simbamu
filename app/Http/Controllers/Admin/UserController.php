<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // 1. Menampilkan Tabel Daftar Pengguna
    public function index()
    {
        // Ambil semua user KECUALI yang sedang login (Admin Wilayah) agar tidak terhapus sendiri
        $users = User::where('id', '!=', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('admin.users.index', compact('users'));
    }

    // 2. Menampilkan Form Tambah Pengguna
   public function create()
{
    // 1. Daftar lengkap 14 Kabupaten/Kota di Kalbar beserta koordinatnya
    $allRegencies = [
        'Kota Pontianak' => ['lat' => -0.0263, 'lng' => 109.3425],
        'Kubu Raya' => ['lat' => -0.3239, 'lng' => 109.3364],
        'Mempawah' => ['lat' => 0.3670, 'lng' => 108.9581],
        'Singkawang' => ['lat' => 0.9030, 'lng' => 108.9850],
        'Sambas' => ['lat' => 1.3503, 'lng' => 109.3179],
        'Bengkayang' => ['lat' => 0.8252, 'lng' => 109.4891],
        'Landak' => ['lat' => 0.4243, 'lng' => 109.9547],
        'Sanggau' => ['lat' => 0.1265, 'lng' => 110.5882],
        'Sekadau' => ['lat' => 0.0116, 'lng' => 110.9023],
        'Sintang' => ['lat' => 0.0711, 'lng' => 111.4984],
        'Melawi' => ['lat' => -0.5186, 'lng' => 111.6961],
        'Kapuas Hulu' => ['lat' => 0.8160, 'lng' => 112.9298],
        'Kayong Utara' => ['lat' => -1.1714, 'lng' => 109.9622],
        'Ketapang' => ['lat' => -1.8507, 'lng' => 109.9715]
    ];

    // 2. Ambil daftar daerah yang sudah memiliki akun dari database
    $usedRegencies = \App\Models\User::whereNotNull('regency')->pluck('regency')->toArray();

    // 3. untuk memfilter daerah yang belum terdaftar saja
    $regencies = array_filter($allRegencies, function($key) use ($usedRegencies) {
        return !in_array($key, $usedRegencies);
    }, ARRAY_FILTER_USE_KEY);

    return view('admin.users.create', compact('regencies'));
}

    // 3. Menyimpan Data Pengguna Baru ke Database
    public function store(Request $request)
    {
        // Validasi inputan dari form
        $request->validate([
            'regency' => 'required',
            'email' => 'required|email|unique:users',
            'no_whatsapp' => 'required',
            'password' => 'required|min:8|confirmed',
            'password.min' => 'Password minimal harus 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok.'
        ]);

        // Memecah teks "Latitude,Longitude" dari form input hidden menjadi array
        $coords = explode(',', $request->coordinates);

        // Simpan ke database
        User::create([
            'name' => 'MDMC ' . $request->regency, 
            'email' => $request->email,
            'regency' => $request->regency,
            'no_whatsapp' => $request->no_whatsapp,
            'password' => Hash::make($request->password),
            'latitude' => $coords[0] ?? null, 
            'longitude' => $coords[1] ?? null, 
        ]);

        return redirect('/users')->with('success', 'Akun MDMC Daerah berhasil didaftarkan!');
    }
    // Menghapus akun pengguna
    public function destroy($id) 
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect('/users')->with('success', 'Akun berhasil dihapus!');
    }


    // Menampilkan Form Edit
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Proses Update Data ke Database
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'no_whatsapp' => 'required|numeric',
            'password' => 'nullable|min:8|confirmed', 
        ]);

        $user->email = $request->email;
        $user->no_whatsapp = $request->no_whatsapp;

        
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/users')->with('success', 'Data akun ' . $user->name . ' berhasil diperbarui!');
    }



}