<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;
use Illuminate\Support\Facades\DB;

class KehadiranController extends Controller
{
    // public function getLansiaByJadwal(Request $request, $jadwal_id)
    // {
    //     $query = Kehadiran::with(['lansia' => function ($query) use ($jadwal_id) {
    //         $query->whereHas('cekKesehatan', function ($q) use ($jadwal_id) {
    //             $q->where('jadwal_id', $jadwal_id);
    //         })
    //         ->with(['cekKesehatan' => function ($q) {
    //             $q->orderBy('created_at', 'desc'); // Urutkan berdasarkan created_at
    //         }]);
    //     }])
    //     ->where('jadwal_id', $jadwal_id)
    //     ->whereHas('lansia', function ($q) use ($request) {
    //         if ($request->has('keyword')) {
    //             $keyword = $request->input('keyword');
    //             $q->where('nama', 'like', "%$keyword%")
    //             ->orWhere('nik', 'like', "%$keyword%");
    //         }
    //     });

    //     $data = $query->paginate(10);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Data lansia yang sudah cek kesehatan berhasil diambil',
    //         'data' => $data
    //     ]);
    // }
 
    public function getLansiaByJadwal(Request $request, $jadwal_id)
{
    $query = \App\Models\CekKesehatan::with('lansia') // relasi lansia harus didefinisikan di model CekKesehatan
        ->where('jadwal_id', $jadwal_id)
        ->orderBy('created_at', 'desc');

    if ($request->has('keyword')) {
        $keyword = $request->input('keyword');
        $query->whereHas('lansia', function ($q) use ($keyword) {
            $q->where('nama', 'like', "%$keyword%")
              ->orWhere('nik', 'like', "%$keyword%");
        });
    }

    $data = $query->paginate(10);

    return response()->json([
        'status' => 'success',
        'message' => 'Data kehadiran berhasil diambil',
        'data' => $data,
    ]);
}

    
    public function lansiaByJadwalAndDesa(Request $request, $jadwal_id, $desa_id)
    {
        // 1. Jumlah lansia pada desa tersebut
        $totalLansia = \App\Models\Lansia::where('desa_id', $desa_id)->count();

        // 2. Jumlah lansia yang sudah cek kesehatan pada jadwal tersebut
        $lansiaCekKesehatan = \App\Models\CekKesehatan::where('jadwal_id', $jadwal_id)
            ->whereHas('lansia', function($q) use ($desa_id) {
                $q->where('desa_id', $desa_id);
            })
            ->distinct('lansia_id')
            ->count('lansia_id');

        // 3. Data lansia pada desa_id yang BELUM cek kesehatan pada jadwal_id, pencarian keyword, pagination 10
        $query = \App\Models\Lansia::where('desa_id', $desa_id)
            ->whereDoesntHave('cekKesehatan', function($q) use ($jadwal_id) {
                $q->where('jadwal_id', $jadwal_id);
            });

        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->where(function($q) use ($keyword) {
                $q->where('nama', 'like', "%$keyword%")
                  ->orWhere('nik', 'like', "%$keyword%");
            });
        }

        $lansia = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Data lansia yang belum cek kesehatan pada jadwal dan desa',
            'total_lansia' => $totalLansia,
            'total_lansia_cek_kesehatan' => $lansiaCekKesehatan,
            'data' => $lansia
        ]);
    }
}
