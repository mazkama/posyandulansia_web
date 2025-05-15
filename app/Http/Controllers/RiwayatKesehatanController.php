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
    public function index()
    {
        $jadwals = Jadwal::orderBy('tanggal', 'desc')->get();
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

        return view('pages.riwayatkesehatan.list', compact('riwayats', 'jadwal'));
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

        $data = [
            'title' => 'Laporan Pemeriksaan Kesehatan Lansia',
            'date' => date('d/m/Y'),
            'jadwal' => $jadwal,
            'cekKesehatan' => $cekKesehatan,
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
