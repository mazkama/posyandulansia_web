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
        $query = Kehadiran::with([
            'lansia' => function ($query) use ($jadwal_id) {
                $query
                    ->whereHas('cekKesehatan', function ($q) use ($jadwal_id) {
                        $q->where('jadwal_id', $jadwal_id);
                    })
                    ->with([
                        'cekKesehatan' => function ($q) use ($jadwal_id) {
                            $q->where('jadwal_id', $jadwal_id)->orderBy('created_at', 'desc'); // Urutkan cekKesehatan-nya
                        },
                    ]);
            },
        ])
            ->where('jadwal_id', $jadwal_id)
            ->whereHas('lansia', function ($q) use ($request, $jadwal_id) {
                if ($request->has('keyword')) {
                    $keyword = $request->input('keyword');
                    $q->where(function ($q) use ($keyword) {
                        $q->where('nama', 'like', "%$keyword%")->orWhere('nik', 'like', "%$keyword%");
                    });
                }

                // pastikan lansia hanya yang punya cekKesehatan di jadwal ini
                $q->whereHas('cekKesehatan', function ($q2) use ($jadwal_id) {
                    $q2->where('jadwal_id', $jadwal_id);
                });
            })
            // Tambahkan subquery untuk order berdasarkan cek_kesehatan terbaru
            ->orderByDesc(DB::table('cek_kesehatan')->select('created_at')->whereColumn('cek_kesehatan.lansia_id', 'kehadiran.lansia_id')->where('jadwal_id', $jadwal_id)->orderByDesc('created_at')->limit(1));

        $data = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Data lansia yang sudah cek kesehatan berhasil diambil',
            'data' => $data,
        ]);
    }
}
