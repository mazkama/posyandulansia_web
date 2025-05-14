<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kehadiran;

class KehadiranController extends Controller
{
    public function getLansiaByJadwal(Request $request, $jadwal_id)
    {
        $query = Kehadiran::with(['lansia' => function ($query) use ($jadwal_id) {
            $query->whereHas('cekKesehatan', function ($q) use ($jadwal_id) {
                $q->where('jadwal_id', $jadwal_id);
            })
            ->with(['cekKesehatan' => function ($q) {
                $q->orderBy('created_at', 'desc'); // Urutkan berdasarkan created_at
            }]);
        }])
        ->where('jadwal_id', $jadwal_id)
        ->whereHas('lansia', function ($q) use ($request) {
            if ($request->has('keyword')) {
                $keyword = $request->input('keyword');
                $q->where('nama', 'like', "%$keyword%")
                ->orWhere('nik', 'like', "%$keyword%");
            }
        });

        $data = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Data lansia yang sudah cek kesehatan berhasil diambil',
            'data' => $data
        ]);
    }

}
