<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function cekJadwal()
    {
        $today = Carbon::today()->toDateString();
        $jadwal = Jadwal::whereDate('tanggal', $today)->get();

        if ($jadwal->isEmpty()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Tidak ada jadwal untuk hari ini.',
                'today' => $today,
                'data' => []
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Ada jadwal untuk hari ini.',
            'data' => $jadwal
        ], 200);
    }

    public function index(Request $request)
    {

        $query = Jadwal::query();

        // Filter berdasarkan tanggal
        if ($request->has('tanggal') && !empty($request->tanggal)) {
            $query->where('tanggal', $request->tanggal);
        }

        // Filter berdasarkan lokasi (menggunakan LIKE untuk pencarian parsial)
        if ($request->has('lokasi')) {
            $query->where('lokasi', 'like', '%' . $request->lokasi . '%');
        }
 
        $jadwals = $query->orderBy('tanggal', 'desc')
                         ->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Data jadwal berhasil diambil.',
            'data' => $jadwals
        ], 200);
    }
 
}
