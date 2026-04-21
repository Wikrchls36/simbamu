<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    // ---------------------------------------------------------
    // 1. BAGIAN PROFIL UMUM (FOTO, INFO, & PASSWORD)
    // ---------------------------------------------------------
    
    public function index()
    {
        $user = Auth::user();
        return view('admin.profile', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validasi Gabungan (Teks Lengkap & Password)
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|max:255|unique:users,email,' . $user->id,
            'no_whatsapp' => 'nullable|string|max:20',
            'password'    => 'nullable|min:8|confirmed',
        ], [
            'password.min'       => 'Password baru minimal harus 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $isUpdated = false;

        // 2. Logika Update Data Teks (Nama, Email, WA)
        // Mengecek apakah ada ketikan yang berbeda dari database sebelumnya
        if ($user->name !== $request->name || $user->email !== $request->email || $user->no_whatsapp !== $request->no_whatsapp) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_whatsapp = $request->no_whatsapp;
            $isUpdated = true;
        }

        // 3. Logika Update Foto (Cropper Base64) - Milikmu yang tidak saya ubah
        if ($request->filled('cropped_photo')) {
            $image_parts = explode(";base64,", $request->cropped_photo);
            if (count($image_parts) == 2) {
                $image_base64 = base64_decode($image_parts[1]);
                $filename = 'profile_photos/' . uniqid() . '.png';

                if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                    Storage::disk('public')->delete($user->profile_photo);
                }

                Storage::disk('public')->put($filename, $image_base64);
                $user->profile_photo = $filename;
                $isUpdated = true;
            }
        }

        // 4. Logika Update Password - Milikmu
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $isUpdated = true;
        }

        // 5. Simpan jika ada perubahan
        if ($isUpdated) {
            $user->save();
            return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
        }

        // Jika user klik simpan tapi tidak mengubah apa-apa
        return redirect()->back()->with('info', 'Tidak ada perubahan data yang disimpan.');
    }
}