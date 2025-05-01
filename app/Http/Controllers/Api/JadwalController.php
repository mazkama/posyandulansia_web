<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Carbon\Carbon;
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
}
