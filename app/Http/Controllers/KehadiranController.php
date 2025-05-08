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

        // Cek waktu sekarang dengan waktu mulai dan selesai jadwal
        $currentTime = Carbon::now();
        $startTime = Carbon::parse($cekJadwal->start_time); 
        $endTime = Carbon::parse($cekJadwal->end_time);

        $jadwalStatus = $cekJadwal->status === 'selesai';

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
            'lansia_id' => 'required',
            'jadwal_id' => 'required',
        ]);

        // **Cek status jadwal sebelum simpan absensi**
        $jadwal = Jadwal::find($validated['jadwal_id']);
        if ($jadwal->status === 'selesai') {
            return redirect()->back()->with('error', 'Jadwal sudah selesai, absensi tidak bisa dilakukan.');
        }

        // Periksa apakah data kehadiran sudah ada
        $exists = Kehadiran::where('lansia_id', $validated['lansia_id'])
            ->where('jadwal_id', $validated['jadwal_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Data kehadiran sudah ada.');
        }

        // Jika belum ada, simpan data kehadiran
        Kehadiran::create([
            'lansia_id' => $validated['lansia_id'],
            'jadwal_id' => $validated['jadwal_id'],
            'status'    => 'hadir'
        ]);

        return redirect()->back()->with('success', 'Kehadiran berhasil disimpan.');
    }

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
    
        // Ambil data kehadiran berdasarkan jadwal_id
        $kehadiran = Kehadiran::where('jadwal_id', $jadwalId)->get();
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
