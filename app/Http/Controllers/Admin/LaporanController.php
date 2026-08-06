<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laporan;
use App\Models\LaporanUpdate;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Laporan::with(['user', 'updates']);

        $filterStatus = $request->query('status', 'Semua');
        $filterJenis = $request->query('jenis_bencana', 'Semua');
 
        if ($filterStatus !== 'Semua') {
            $query->where('status', $filterStatus);
        } 

        if ($filterJenis !== 'Semua') {
            $query->where('jenis_bencana', $filterJenis);
        }
        $laporans = $query->orderBy('updated_at', 'desc')->get();

        return view('admin.laporan.index', compact('laporans', 'filterStatus', 'filterJenis'));
    }
    public function peta()
    {
        $semuaLaporan = Laporan::with(['user', 'updates' => function($query) {
            $query->orderBy('id', 'asc');
        }])->where('status', 'Aktif')->get();
        
        return view('admin.laporan.peta', compact('semuaLaporan'));
    }

    public function show($id, Request $request)
    {
        $laporan = Laporan::with(['user', 'updates' => function($query) {
            $query->orderBy('id', 'asc');
        }])->findOrFail($id);
        
        $sitrepId = $request->query('sitrep');
        if ($sitrepId) {
            $currentSitrep = $laporan->updates->where('id', $sitrepId)->first();
        } else {
            $currentSitrep = $laporan->updates->first(); 
        }

        return view('admin.laporan.show', compact('laporan', 'currentSitrep'));
    }

    public function downloadPdf($update_id)
    {
        $sitrep = LaporanUpdate::with('laporan.user')->findOrFail($update_id);
        
        return view('pengguna.laporan.pdf', compact('sitrep'));
    }

    public function tandaiSelesai($id)
    {
        if (Auth::user()->role !== 'admin') {
            return abort(403, 'Anda tidak memiliki akses.');
        }

        $laporan = Laporan::findOrFail($id);
        $laporan->update([
            'status' => 'Selesai'
        ]);

        return redirect()->back()->with('success', 'Status laporan bencana berhasil diubah menjadi Selesai / Kondusif.');
    }
}