<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index(Request $request)
    {  
        // Ambil data berita dengan pagination
        $berita = Berita::orderBy('tanggal_publish', 'desc')->paginate(10);

        // Struktur response standar untuk konsumsi di Kotlin/Android
        return response()->json([
            'status' => true,
            'message' => 'Data berita berhasil diambil',
            'data' => $berita, 
        ], 200);
    }
}
