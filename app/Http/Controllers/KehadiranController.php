<?php

namespace App\Http\Controllers;

use App\Models\CekKesehatan;
use App\Models\Jadwal;
use App\Models\Kehadiran;
use App\Models\Lansia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Kreait\Firebase\Factory;


class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');
        $cekJadwal = Jadwal::find($jadwalId);

        if (is_null($cekJadwal)) {
            return redirect('/jadwal');
        }

        // Ambil data lansia
        $lansias = Lansia::all();

        // Ambil daftar lansia yang sudah hadir
        $kehadiran = Kehadiran::where('jadwal_id', $jadwalId)
            ->pluck('lansia_id')
            ->toArray();

        // Hitung jumlah lansia yang sudah cek kesehatan
        $totalSudahCek = CekKesehatan::where('jadwal_id', $jadwalId)
            ->pluck('lansia_id')
            ->count();

        // Total yang hadir
        $totalHadir = count($kehadiran);

        // Total lansia yang belum cek kesehatan (Hadir - Sudah Cek)
        $totalBelumCek =  $totalHadir - $totalSudahCek;

        // Data yang akan dikirim ke Firebase
        $firebaseData = [
            'totalSudahCek' => $totalSudahCek,
            'totalHadir' => $totalHadir,
            'totalBelumCek' => $totalBelumCek,
        ];

        $credentialsPath = storage_path('app/firebase_credentials.json');

        // Inisialisasi Firebase Database dengan Kreait
        $firebase = (new Factory)
            ->withServiceAccount($credentialsPath) // Pastikan path benar
            ->withDatabaseUri('https://posyandulansia-f9b02-default-rtdb.asia-southeast1.firebasedatabase.app/');

        $database = $firebase->createDatabase();

        // Simpan data ke Firebase
        try {
            $database->getReference("jadwal/$jadwalId")->update($firebaseData);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan ke Firebase: ' . $e->getMessage());
        }

        // Jika berhasil, tampilkan view dengan data yang diperlukan
        return view('pages.kehadiran.index', compact(
            'lansias',
            'kehadiran',
            'jadwalId',
            'totalSudahCek',
            'totalHadir',
            'totalBelumCek'
        ));
    }


    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'lansia_id' => 'required|exists:lansia,id',
            'jadwal_id' => 'required|exists:jadwal,id',
            'totalSudahCek' => 'required|integer',
            'totalHadir' => 'required|integer',
            'totalBelumCek' => 'required|integer',
        ]);

        // Periksa apakah data kehadiran sudah ada di MySQL
        $exists = Kehadiran::where('lansia_id', $validated['lansia_id'])
            ->where('jadwal_id', $validated['jadwal_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Data kehadiran sudah ada.');
        }

        // Ambil data lansia dari tabel Lansia
        $lansia = Lansia::findOrFail($validated['lansia_id']);

        // Data yang akan dikirim ke Firebase
        $firebaseData = [
            'lansia_id' => $lansia->id,
            'nama' => $lansia->nama,
            'nik'  => $lansia->nik,
        ];

        $credentialsPath = storage_path('app/firebase_credentials.json');

        // Inisialisasi Firebase Database dengan Kreait
        $firebase = (new Factory)
            ->withServiceAccount($credentialsPath) // Pastikan path benar
            ->withDatabaseUri('https://posyandulansia-f9b02-default-rtdb.asia-southeast1.firebasedatabase.app/');

        $database = $firebase->createDatabase();

        try {
            // Simpan data ke Firebase
            // $database->getReference("jadwal/{$validated['jadwal_id']}/lansias/{$validated['lansia_id']}")
            //     ->set($firebaseData);
              $database->getReference("jadwal/{$validated['jadwal_id']}/lansias/")
                ->push($firebaseData);

            // Jika berhasil, simpan ke database MySQL
            Kehadiran::create([
                'lansia_id' => $validated['lansia_id'],
                'jadwal_id' => $validated['jadwal_id'],
                'status'    => 'hadir'
            ]);

            return redirect()->back()->with('success', 'Kehadiran berhasil disimpan ke Firebase dan MySQL.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan ke Firebase: ' . $e->getMessage());
        }
    }


    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $validated = $request->validate([
    //         'lansia_id' => 'required',  // Pastikan lansia_id ada di tabel lansias
    //         'jadwal_id' => 'required',
    //     ]);

    //     // Periksa apakah data kehadiran sudah ada
    //     $exists = Kehadiran::where('lansia_id', $validated['lansia_id'])
    //         ->where('jadwal_id', $validated['jadwal_id'])
    //         ->exists();

    //     if ($exists) {
    //         return redirect()->back()->with('error', 'Data kehadiran sudah ada.');
    //     }

    //     // Jika belum ada, simpan data kehadiran
    //     Kehadiran::create([
    //         'lansia_id' => $validated['lansia_id'],
    //         'jadwal_id' => $validated['jadwal_id'],
    //         'status'    => 'hadir'
    //     ]);

    //     return redirect()->back()->with('success', 'Kehadiran berhasil disimpan.');
    // }
}
