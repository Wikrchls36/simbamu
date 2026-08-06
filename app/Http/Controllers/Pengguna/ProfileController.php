<?php

namespace App\Http\Controllers\Pengguna; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        return view('pengguna.profile', compact('user')); 
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

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

        if ($user->name !== $request->name || $user->email !== $request->email || $user->no_whatsapp !== $request->no_whatsapp) {
            $user->name = $request->name;
            $user->email = $request->email;
            $user->no_whatsapp = $request->no_whatsapp;
            $isUpdated = true;
        }

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

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $isUpdated = true;
        }

        if ($isUpdated) {
            $user->save();
            return redirect()->back()->with('success', 'Profil daerah berhasil diperbarui!');
        }

        return redirect()->back()->with('info', 'Tidak ada perubahan data yang disimpan.');
    }
}