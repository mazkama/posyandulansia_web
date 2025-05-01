<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    public function getNotifikasi(){
        $data = Notifikasi::orderBy('tanggal_kirim', 'desc')->paginate(10);

        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Data notifikasi tidak ditemukan.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Data notifikasi ditemukan.',
            'data' => $data
        ], 200);
    }
}
