<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Http;

class JadwalController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $jadwals = Jadwal::orderBy('tanggal', 'desc')->get();
        return view('pages.jadwal.index', compact('jadwals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'waktu'      => 'required|date_format:H:i',
            'lokasi'     => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ], [
            'tanggal.required'   => 'Tanggal wajib diisi.',
            'tanggal.date'       => 'Format tanggal tidak valid.',
            'waktu.required'     => 'Waktu wajib diisi.',
            'waktu.date_format'  => 'Format waktu tidak valid, gunakan format HH:MM.',
            'lokasi.required'    => 'Lokasi wajib diisi.',
            'lokasi.max'         => 'Lokasi maksimal 255 karakter.',
        ]);

        // Konversi tanggal ke format hari
        $hari = Carbon::parse($validated['tanggal'])->translatedFormat('l');
        $tanggal = Carbon::parse($validated['tanggal'])->translatedFormat('j F Y');

        // Format pesan notifikasi dengan hari
        $pesan = "Posyandu lansia selanjutnya akan dilaksanakan {$hari}, {$tanggal} pukul {$validated['waktu']} di {$validated['lokasi']}. Kami mengharapkan kehadiran saudara/saudari.";

        // Simpan jadwal
        Jadwal::create($validated);

        // Kirim notifikasi dengan pesan yang sudah diformat
        app()->call('App\Http\Controllers\NotifikasiController@store', [
            'request' => new Request([
                'pesan' => $pesan
            ])
        ]);


        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dibuat.');
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        // Jika permintaan adalah AJAX, kembalikan data JSON
        if (request()->ajax()) {
            return response()->json($jadwal);
        }

        // Atau, jika menggunakan halaman edit terpisah
        return view('jadwal.edit', compact('jadwal'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());

        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'waktu'      => 'required|date_format:H:i:s',
            'lokasi'     => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);

        $jadwal = Jadwal::findOrFail($id);
        $jadwal->update($validated);

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            $jadwal = Jadwal::findOrFail($id);
            $jadwal->delete();

            $this->hapusJadwal($id);

            return redirect()->back()->with('success', 'Jadwal berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Jadwal gagal dihapus.');
        }
    }

    public function hapusJadwal($jadwalId)
    {

        $credentialsPath = storage_path('app/firebase_credentials.json');
        try {
            // Inisialisasi Firebase Database dengan Kreait
            $firebase = (new Factory)
                ->withServiceAccount($credentialsPath) // Pastikan path benar
                ->withDatabaseUri('https://posyandulansia-f9b02-default-rtdb.asia-southeast1.firebasedatabase.app/');

            $database = $firebase->createDatabase();

            // Hapus data jadwal dari Firebase
            $database->getReference("jadwal/{$jadwalId}")->remove();

            return response()->json(['success' => true, 'message' => 'Jadwal berhasil dihapus dari Firebase.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus dari Firebase: ' . $e->getMessage()]);
        }
    }
}
