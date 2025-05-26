<?php

namespace App\Http\Controllers;

use App\Models\CekKesehatan;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CekKesehatanExport;

class RiwayatKesehatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::orderBy('tanggal', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '=', $request->start_date);
        }

        $jadwals = $query->get();

        return view('pages.riwayatkesehatan.index', compact('jadwals'));
    }

    public function riwayatKesehatan($jadwal_id)
    {
        $jadwal = Jadwal::find($jadwal_id);
        if (!$jadwal) {
            return redirect()->route('riwayatkesehatan.index')->with('error', 'Jadwal tidak ditemukan.');
        }

        $riwayats = CekKesehatan::with('lansia')
            ->where('jadwal_id', $jadwal_id)
            ->get();

        // Statistik berdasarkan parameter langsung
        $jumlah_gula_tinggi = $riwayats->where('gula_darah', '>', 140)->count();
        $jumlah_asam_urat_tinggi = $riwayats->where('asam_urat', '>', 7)->count();
        $jumlah_kolestrol_tinggi = $riwayats->where('kolestrol', '>', 200)->count();

        // Hipertensi: sistolik > 140 atau diastolik > 90
        $jumlah_hipertensi = $riwayats->filter(function ($item) {
            $sistolik = $item->tekanan_darah ?? 0;
            $diastolik = $item->tekanan_darah_diastolik ?? 0; // pastikan ada kolom ini
            return $sistolik > 140 || $diastolik > 90;
        })->count();

        // Diagnosa
        $jumlah_diabetes_mellitus = $riwayats->where('gula_darah', '>', 140)->count();
        $jumlah_hiperkolesterolemia = $riwayats->where('kolestrol', '>', 200)->count();
        $jumlah_asam_urat = $riwayats->where('asam_urat', '>', 7)->count();

        return view('pages.riwayatkesehatan.list', compact(
            'riwayats',
            'jadwal',
            'jumlah_gula_tinggi',
            'jumlah_asam_urat_tinggi',
            'jumlah_kolestrol_tinggi',
            'jumlah_hipertensi',
            'jumlah_diabetes_mellitus',
            'jumlah_hiperkolesterolemia',
            'jumlah_asam_urat'
        ));
    }



    public function show($id)
    {
        $cekKesehatan = CekKesehatan::findOrFail($id);

        if (request()->ajax()) {
            return response()->json($cekKesehatan);
        }

        return redirect()->route('riwayatkesehatan.index')->with('error', 'Permintaan tidak valid.');
    }

    public function exportPDF(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');

        if (!$jadwalId) {
            return redirect()->back()->with('error', 'Parameter jadwal_id tidak ditemukan.');
        }

        $jadwal = Jadwal::find($jadwalId);
        if (!$jadwal) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
        }

        $cekKesehatan = CekKesehatan::with('lansia')
            ->where('jadwal_id', $jadwalId)
            ->get();

        // jika data kurang dari 10, hasil PDF akan blank (tidak render view)
        if ($cekKesehatan->count() < 10) {
            $dompdf = new Dompdf();
            $dompdf->loadHtml('');
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            return $dompdf->stream('Cek_Riwayat_Kese.pdf');
        }

        // Lanjutkan seperti biasa jika data ≥ 10
        $jumlah_gula_tinggi = $cekKesehatan->where('gula_darah', '>', 140)->count();
        $jumlah_asam_urat_tinggi = $cekKesehatan->where('asam_urat', '>', 7)->count();
        $jumlah_tekanan_darah_tinggi = $cekKesehatan->filter(function ($item) {
            return ($item->tekanan_darah_sistolik > 140 || $item->tekanan_darah_diastolik > 90);
        })->count();
        $jumlah_kolestrol_tinggi = $cekKesehatan->where('kolestrol', '>', 200)->count();
        $jumlah_diabetes_mellitus = $cekKesehatan->where('gula_darah', '>', 140)->count();

        $data = [
            'title' => 'Laporan Pemeriksaan Kesehatan Lansia',
            'date' => date('d/m/Y'),
            'jadwal' => $jadwal,
            'cekKesehatan' => $cekKesehatan,
            'jumlah_gula_tinggi' => $jumlah_gula_tinggi,
            'jumlah_asam_urat_tinggi' => $jumlah_asam_urat_tinggi,
            'jumlah_tekanan_darah_tinggi' => $jumlah_tekanan_darah_tinggi,
            'jumlah_kolestrol_tinggi' => $jumlah_kolestrol_tinggi,
            'jumlah_diabetes_mellitus' => $jumlah_diabetes_mellitus,
        ];

        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->setIsPhpEnabled(true);

        $dompdf = new Dompdf($options);

        $html = view('pages.riwayatkesehatan.pdf', $data)->render();

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        return $dompdf->stream('laporan-pemeriksaan-kesehatan-lansia-' . date('Y-m-d') . '.pdf');
    }


    public function exportExcel(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');

        if (!$jadwalId) {
            return redirect()->back()->with('error', 'Parameter jadwal_id tidak ditemukan.');
        }

        $jadwal = Jadwal::find($jadwalId);
        if (!$jadwal) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan.');
        }

        $fileName = 'laporan-pemeriksaan-kesehatan-lansia-' . date('Y-m-d') . '.xlsx';

        return Excel::download(new CekKesehatanExport($jadwalId), $fileName);
    }
}
