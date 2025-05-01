<?php

namespace App\Http\Controllers;

use App\Models\CekKesehatan;
use App\Models\Lansia;
use Illuminate\Http\Request;

class RiwayatKesehatanController extends Controller
{
    public function index()
    {
        $lansias = Lansia::all();

        return view('pages.riwayatkesehatan.index', compact('lansias'));
    }

    public function list(Request $request)
    {
        $lansiaId = $request->query('lansia_id');

        $riwayats = CekKesehatan::with('lansia')->where('lansia_id', $lansiaId)->get();

        return view('pages.riwayatkesehatan.list', compact('riwayats'));
    }

    public function show($id)
    {
        $cekKesehatan = CekKesehatan::findOrFail($id);
        if (request()->ajax()) {
            return response()->json($cekKesehatan); 
        }

        // Atau, jika menggunakan halaman edit terpisah
        return view('jadwal.edit', compact('jadwal'));
    }


}
