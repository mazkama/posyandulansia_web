<?php

namespace App\Http\Controllers;

use App\Models\CekKesehatan;
use App\Models\Jadwal;
use App\Models\Kehadiran;
use App\Models\Lansia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\KehadiranExport;
use Illuminate\Support\Facades\DB;
use Kreait\Firebase\Factory;


class KehadiranController extends Controller
{
    public function index(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');
        $cekJadwal = Jadwal::find($jadwalId);

        if (!$cekJadwal) {
            return redirect('/jadwal')->with('error', 'Jadwal tidak ditemukan.');
        }

        // Ambil semua lansia yang hadir pada jadwal tersebut
        $lansiaHadirIds = Kehadiran::where('jadwal_id', $jadwalId)->pluck('lansia_id');

        // Ambil semua lansia yang sudah cek kesehatan pada jadwal tersebut
        $lansiaSudahCekIds = CekKesehatan::where('jadwal_id', $jadwalId)->pluck('lansia_id');

        // Ambil semua lansia yang hadir tapi belum cek kesehatan
        $lansiaBelumCek = Lansia::whereIn('id', $lansiaHadirIds)
            ->whereNotIn('id', $lansiaSudahCekIds)
            ->get();

        // Hitung total
        $totalHadir = $lansiaHadirIds->count();
        $totalSudahCek = $lansiaSudahCekIds->count();
        $totalBelumCek = $lansiaBelumCek->count();

        // Ambil semua data lansia
        $lansias = Lansia::all();

        // Ambil daftar ID lansia yang hadir
        $kehadiran = $lansiaHadirIds->toArray();

        // Status jadwal
        $jadwalStatus = $cekJadwal->status === 'selesai';

        // Data yang dikirim ke Firebase
        $firebaseData = [
            'totalSudahCek' => $totalSudahCek,
            'totalHadir' => $totalHadir,
            'totalBelumCek' => $totalBelumCek,
        ];

        // Path credentials Firebase
        $credentialsPath = storage_path('app/firebase_credentials.json');

        // Inisialisasi dan kirim ke Firebase
        try {
            $firebase = (new Factory)
                ->withServiceAccount($credentialsPath)
                ->withDatabaseUri('https://posyandulansia-f9b02-default-rtdb.asia-southeast1.firebasedatabase.app/');

            $firebase->createDatabase()
                ->getReference("jadwal/$jadwalId")
                ->update($firebaseData);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan ke Firebase: ' . $e->getMessage());
        }

        // Tampilkan view
        return view('pages.kehadiran.index', compact(
            'lansias',
            'kehadiran',
            'jadwalId',
            'totalSudahCek',
            'totalHadir',
            'totalBelumCek',
            'jadwalStatus'
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
    //         'lansia_id' => 'required',
    //         'jadwal_id' => 'required',
    //     ]);

    //     // **Cek status jadwal sebelum simpan absensi**
    //     $jadwal = Jadwal::find($validated['jadwal_id']);
    //     if ($jadwal->status === 'selesai') {
    //         return redirect()->back()->with('error', 'Jadwal sudah selesai, absensi tidak bisa dilakukan.');
    //     }

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

    public function cetakLaporanPdf(Request $request)
    {
        $jadwalId = $request->jadwal_id;
        
        $cekJadwal = Jadwal::find($jadwalId); 
        if (!$cekJadwal) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
        }
    
        // Ubah status jadwal menjadi selesai
        if ($cekJadwal->status !== 'selesai') {
            $cekJadwal->status = 'selesai';
            $cekJadwal->save();
        }
    
        $kehadiran = Kehadiran::with('lansia')
            ->where('jadwal_id', $jadwalId)
            ->get();
        // Ambil data lansia
        $lansias = Lansia::all();
    
        // Ambil data yang terkait dengan jadwal
        $totalHadir = Kehadiran::where('jadwal_id', $jadwalId)->count();
        $totalLansia = Lansia::count();
        $totalTidakHadir = $totalLansia - $totalHadir;
        $totalKeseluruhan = $totalLansia;
    
        // Kirim semua data ke view termasuk $jadwal
        $dompdf = new Dompdf();
        $dompdf->setOptions(new Options([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true
        ]));
    
        // Pastikan untuk mengirimkan data 'jadwal' ke view
        $html = view('pages.kehadiran.laporan_pdf', compact('cekJadwal', 'kehadiran', 'lansias', 'totalHadir', 'totalTidakHadir', 'totalKeseluruhan'))->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
    
        return $dompdf->stream('laporan_kehadiran.pdf');
    }
    
    

    
    public function cetakLaporanExcel(Request $request)
    {
        $jadwalId = $request->jadwal_id;
    
        $cekJadwal = Jadwal::find($jadwalId);
        if (!$cekJadwal) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
        }
    
        if ($cekJadwal->status !== 'selesai') {
            $cekJadwal->status = 'selesai';
            $cekJadwal->save();
        }
    
        return Excel::download(new KehadiranExport($jadwalId), 'laporan_kehadiran.xlsx');
    }
    
    
}
