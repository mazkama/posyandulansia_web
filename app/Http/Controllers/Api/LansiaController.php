<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lansia;
use Illuminate\Http\Request;

class LansiaController extends Controller
{
    public function getAllLansia()
    {
        $lansia = Lansia::paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Data lansia berhasil diambil.',
            'data' => $lansia
        ], 200);
    }

    public function searchLansia(Request $request)
    {
        $query = Lansia::query();

        if ($request->has('keyword')) {
            $keyword = $request->input('keyword');
            $query->where('nama', 'like', "%$keyword%")
                  ->orWhere('nik', 'like', "%$keyword%");
        }

        $lansia = $query->paginate(10);

        return response()->json([
            'status' => 'success',
            'message' => 'Hasil pencarian data lansia.',
            'data' => $lansia
        ], 200);
    }

    public function show($userId)
    {
        $lansia = Lansia::where('user_id', $userId)->first();


        if (!$lansia) {
            return response()->json([
                'message' => 'Lansia tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Data lansia ditemukan',
            'data' => $lansia
        ], 200);
    }
}
