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
         $now = Carbon::now();
     
         foreach ($jadwals as $jadwal) {
             if ($jadwal->status !== 'selesai') {
                 if ($now->lt(Carbon::parse($jadwal->tanggal))) {
                     $jadwal->status = 'belum_dimulai';
                 } elseif ($now->isSameDay(Carbon::parse($jadwal->tanggal))) {
                     $jadwal->status = 'berlangsung';
                 } else {
                     $jadwal->status = 'selesai';
                 }
     
                 // Simpan jika status berubah
                 if ($jadwal->isDirty('status')) {
                     $jadwal->save();
                 }
             }
         }
     
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
    
        // Tentukan status secara otomatis**
        $now = Carbon::now();
        $tanggalMulai = Carbon::parse($validated['tanggal']);
    
        if ($now->lt($tanggalMulai)) {
            $status = 'belum_dimulai';
        } elseif ($now->isSameDay($tanggalMulai)) {
            $status = 'berlangsung';
        } else {
            $status = 'selesai';
        }
    
        // Format pesan notifikasi
        $pesan = "Posyandu lansia selanjutnya akan dilaksanakan {$hari}, {$tanggal} pukul {$validated['waktu']} di {$validated['lokasi']}. Kami mengharapkan kehadiran saudara/saudari.";
    
        $lastJadwal = Jadwal::orderBy('tanggal', 'desc')->first();
        if ($lastJadwal && $lastJadwal->status !== 'selesai') {
            return redirect()->route('jadwal.index')->with('error', 'Jadwal sebelumnya belum selesai, tidak bisa membuat jadwal baru.');
        }
        // Simpan jadwal dengan status otomatis
        $jadwal = Jadwal::create([
            'tanggal' => $validated['tanggal'],
            'waktu'   => $validated['waktu'],
            'lokasi'  => $validated['lokasi'],
            'status'  => $status, 
        ]);
    
        // Kirim notifikasi dengan pesan 
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
        $validated = $request->validate([
            'tanggal'    => 'required|date',
            'waktu'      => 'required|date_format:H:i',
            'lokasi'     => 'required|string|max:255',
        ], [
            'tanggal.required'   => 'Tanggal wajib diisi.',
            'tanggal.date'       => 'Format tanggal tidak valid.',
            'waktu.required'     => 'Waktu wajib diisi.',
            'waktu.date_format'  => 'Format waktu tidak valid, gunakan format HH:MM.',
            'lokasi.required'    => 'Lokasi wajib diisi.',
            'lokasi.max'         => 'Lokasi maksimal 255 karakter.',
        ]);
    
        // Ambil data jadwal lama
        $jadwal = Jadwal::findOrFail($id);
    
        // Update data dengan input baru
        $jadwal->update($validated);
    
        // Tentukan status secara otomatis
        $now = Carbon::now();
        $tanggalMulai = Carbon::parse($validated['tanggal']);
    
        if ($now->lt($tanggalMulai)) {
            $jadwal->status = 'belum_dimulai';
        } elseif ($now->isSameDay($tanggalMulai)) {
            $jadwal->status = 'berlangsung';
        } else {
            $jadwal->status = 'selesai';
        }
    
        $jadwal->save();
    
        // Redirect dengan pesan sukses**
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
