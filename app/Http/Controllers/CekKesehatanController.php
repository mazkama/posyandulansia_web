<?php

namespace App\Http\Controllers;

use App\Models\CekKesehatan;
use App\Models\Jadwal;
use App\Models\Kehadiran;
use App\Models\Lansia;
use Illuminate\Http\Request;

class CekKesehatanController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::orderBy('tanggal', 'desc')->get();
        return view('pages.cekkesehatan.index', compact('jadwals'));
    }

    public function show(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');

        //Ambil semua lansia yang hadir pada jadwal tersebut
        $lansiaHadirIds = Kehadiran::where('jadwal_id', $jadwalId)->pluck('lansia_id');

        //Ambil semua lansia yang sudah cek kesehatan pada jadwal tersebut
        $lansiaSudahCekIds = CekKesehatan::where('jadwal_id', $jadwalId)->pluck('lansia_id');

        //Ambil semua lansia yang hadir tapi belum cek kesehatan
        $lansiaBelumCek = Lansia::whereIn('id', $lansiaHadirIds)
            ->whereNotIn('id', $lansiaSudahCekIds)
            ->get();

        //Ambil jumlah lansia yang hadir
        $totalHadir = $lansiaHadirIds->count();

        //Ambil jumlah lansia yang belum cek kesehatan
        $totalBelumCek = $lansiaBelumCek->count();

        //Ambil jumlah lansia yang sudah cek kesehatan
        $totalSudahCek = $lansiaSudahCekIds->count();

        //Kirim data ke view
        return view('pages.cekkesehatan.show', compact('lansiaBelumCek', 'jadwalId', 'totalHadir', 'totalBelumCek', 'totalSudahCek'));
    }

    //remake function show
    // public function show(Request $request)
    // {
    //     $jadwalId = $request->query('jadwal_id');
    //     $lansiaBelumCek = CekKesehatan::with('lansia','kehadiran')->where('jadwal_id', $jadwalId)->get();

    //     dd($lansiaBelumCek);



    //     $kehadirans = kehadiran::with('lansia')->where('jadwal_id', $jadwalId)->get();
    //     // $c = Kehadiran::where('jadwal_id', $jadwalId)
    //     //     ->pluck('lansia_id')
    //     //     ->toArray();
    //     // $selesaiCek = CekKesehatan::where('jadwal_id',$jadwalId)
    //     //     ->pluck('lansia_id')
    //     //     ->count();
    //     return view('pages.cekkesehatan.show', compact('kehadirans', 'jadwalId'));
    // }

    public function create(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');
        $lansiaId = $request->query('lansia_id');

        return view('pages.cekkesehatan.create', compact('jadwalId', 'lansiaId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lansia_id' => 'required',
            'jadwal_id' => 'required',
            'tanggal' => 'required|date',
            'berat_badan' => 'required|numeric',
            'tekanan_darah' => 'required|numeric',
            'gula_darah' => 'required|numeric',
            'kolestrol' => 'required|numeric',
        ]);

        CekKesehatan::create($request->all());

        return redirect()
            ->route('cekKesehatan.show', ['jadwal_id' => $request['jadwal_id']])
            ->with('success', 'Data berhasil disimpan.');
    }







    // public function edit($id)
    // {
    //     $cekKesehatan = CekKesehatan::findOrFail($id);
    //     $lansias = Lansia::all();
    //     $jadwals = Jadwal::all();
    //     return view('cek_kesehatan.edit', compact('cekKesehatan', 'lansias', 'jadwals'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'lansia_id' => 'required|exists:lansia,id',
    //         'jadwal_id' => 'required|exists:jadwal,id',
    //         'tanggal' => 'required|date',
    //         'berat_badan' => 'required|numeric',
    //         'tekanan_darah' => 'required|string',
    //         'gula_darah' => 'required|numeric',
    //         'kolestrol' => 'required|numeric',
    //     ]);

    //     $cekKesehatan = CekKesehatan::findOrFail($id);
    //     $cekKesehatan->update($request->all());

    //     return redirect()->route('cek_kesehatan.index')->with('success', 'Data berhasil diperbarui.');
    // }

    // public function destroy($id)
    // {
    //     $cekKesehatan = CekKesehatan::findOrFail($id);
    //     $cekKesehatan->delete();

    //     return redirect()->route('cek_kesehatan.index')->with('success', 'Data berhasil dihapus.');
    // }


}
