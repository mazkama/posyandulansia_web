<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kader;

class KaderController extends Controller
{
    public function show($userId)
    {
        $kader = Kader::where('user_id', $userId)->first();


        if (!$kader) {
            return response()->json([
                'message' => 'Kader tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'message' => 'Data kader ditemukan',
            'data' => $kader
        ], 200);
    }
}
