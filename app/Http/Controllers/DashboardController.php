<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lansia;
use App\Models\Jadwal;
use App\Models\Berita;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Jumlah total lansia
        $jumlahLansia = Lansia::count();

        // Jadwal selanjutnya berdasarkan tanggal dan waktu sekarang ke atas
        $jadwalSelanjutnya = Jadwal::where('tanggal', '>=', Carbon::now()->format('Y-m-d'))
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu', 'asc')
            ->first();

        // Ambil 5 berita terbaru berdasarkan waktu upload (created_at)
        $beritaTerbaru = Berita::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Jumlah total berita
        $jumlahBerita = Berita::count();

        // Total semua jadwal
        $totalJadwal = Jadwal::count();

        // Kirim data ke view
        return view('pages.dashboard', [
            'jumlahLansia' => $jumlahLansia,
            'jadwalSelanjutnya' => $jadwalSelanjutnya,
            'beritaTerbaru' => $beritaTerbaru,
            'jumlahBerita' => $jumlahBerita,
            'totalJadwal' => $totalJadwal,
        ]);
    }
}
