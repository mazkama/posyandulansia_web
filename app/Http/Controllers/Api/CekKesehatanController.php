<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CekKesehatan;
use App\Models\Kehadiran;
use App\Models\Jadwal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CekKesehatanController extends Controller
{
    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'lansia_id' => 'required',
    //         'jadwal_id' => 'required',
    //         'berat_badan' => 'required|numeric',
    //         'tekanan_darah' => 'required|numeric',
    //         'gula_darah' => 'required|numeric',
    //         'kolestrol' => 'required|numeric',
    //         'asam_urat' => 'required|numeric',
    //     ]);

    //     $today = Carbon::today()->toDateString();

    //     // Tambahkan tanggal hari ini ke data request
    //     $data = $request->all();
    //     $data['tanggal'] = $today;

    //     try {
    //         // Menyimpan data cek kesehatan
    //         $cekKesehatan = CekKesehatan::create($data);

    //         // Cek jika berhasil disimpan, baru jalankan update
    //         if ($cekKesehatan) {
    //             // Update total kehadiran dan cek kesehatan
    //             $this->hapusAntrian($request->jadwal_id, $request->lansia_id);

    //             // Update total kehadiran dan cek kesehatan
    //             $this->updateTotal($request->jadwal_id);
    //         }

    //         return response()->json([
    //             'message' => 'Data berhasil disimpan.',
    //             'data' => $cekKesehatan
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Terjadi kesalahan saat menyimpan data.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'lansia_id' => 'required|exists:lansia,id',
            'jadwal_id' => 'required|exists:jadwal,id',
            'berat_badan' => 'nullable|numeric|min:0',
            'tekanan_darah_sistolik' => 'nullable|integer|min:0',
            'tekanan_darah_diastolik' => 'nullable|integer|min:0',
            'gula_darah' => 'nullable|numeric|min:0',
            'kolestrol' => 'nullable|numeric|min:0',
            'asam_urat' => 'nullable|numeric|min:0',
            'diagnosa' => 'nullable|string'
        ]);

        // Ambil data dengan nilai default 0 jika kosong
        $berat_badan = $request->berat_badan ?? 0;
        $tekanan_darah_sistolik = $request->tekanan_darah_sistolik ?? 0;
        $tekanan_darah_diastolik = $request->tekanan_darah_diastolik ?? 0;
        $gula_darah = $request->gula_darah ?? 0;
        $kolestrol = $request->kolestrol ?? 0;
        $asam_urat = $request->asam_urat ?? 0;

        // Diagnosa otomatis
        $diagnosa = [];

        if ($berat_badan > 0) {
            if ($berat_badan < 40) {
                $diagnosa['berat_badan'] = "Berat badan terlalu rendah";
            } elseif ($berat_badan > 90) {
                $diagnosa['berat_badan'] = "Berat badan berlebihan";
            } else {
                $diagnosa['berat_badan'] = "Normal";
            }
        }

        if ($tekanan_darah_sistolik > 0 && $tekanan_darah_diastolik > 0) {
            if ($tekanan_darah_sistolik < 90 || $tekanan_darah_diastolik < 60) {
                $diagnosa['tekanan_darah'] = "Hipotensi (tekanan darah rendah)";
            } elseif ($tekanan_darah_sistolik > 140 || $tekanan_darah_diastolik > 90) {
                $diagnosa['tekanan_darah'] = "Hipertensi (tekanan darah tinggi)";
            } else {
                $diagnosa['tekanan_darah'] = "Normal";
            }
        }

        if ($gula_darah > 0) {
            if ($gula_darah < 70) {
                $diagnosa['gula_darah'] = "Hipoglikemia (gula darah rendah)";
            } elseif ($gula_darah > 126) {
                $diagnosa['gula_darah'] = "Diabetes (gula darah tinggi)";
            } else {
                $diagnosa['gula_darah'] = "Normal";
            }
        }

        if ($kolestrol > 0) {
            if ($kolestrol > 200) {
                $diagnosa['kolestrol'] = "Kolesterol tinggi";
            } else {
                $diagnosa['kolestrol'] = "Normal";
            }
        }

        if ($asam_urat > 0) {
            if ($asam_urat > 7) {
                $diagnosa['asam_urat'] = "Asam urat tinggi";
            } else {
                $diagnosa['asam_urat'] = "Normal";
            }
        }

        // Menentukan kualitas kesehatan secara keseluruhan
        $kualitas_kesehatan = "Sehat";

        if (
            in_array("Hipertensi (tekanan darah tinggi)", $diagnosa) ||
            in_array("Diabetes (gula darah tinggi)", $diagnosa) ||
            in_array("Kolesterol tinggi", $diagnosa) ||
            in_array("Asam urat tinggi", $diagnosa)
        ) {
            $kualitas_kesehatan = "Berisiko";
        }

        if (
            in_array("Hipotensi (tekanan darah rendah)", $diagnosa) ||
            in_array("Hipoglikemia (gula darah rendah)", $diagnosa) ||
            in_array("Berat badan terlalu rendah", $diagnosa)
        ) {
            $kualitas_kesehatan = "Tidak Sehat";
        }

        $jadwal = Jadwal::findOrFail($request->jadwal_id);

        try {
            // Simpan data cek kesehatan
            $cekKesehatan = new CekKesehatan();
            $cekKesehatan->fill([
                'lansia_id' => $request->lansia_id,
                'jadwal_id' => $request->jadwal_id,
                'tanggal' => $jadwal->tanggal,
                'berat_badan' => $berat_badan,
                'tekanan_darah_sistolik' => $tekanan_darah_sistolik,
                'tekanan_darah_diastolik' => $tekanan_darah_diastolik,
                'gula_darah' => $gula_darah,
                'kolestrol' => $kolestrol,
                'asam_urat' => $asam_urat,
                'diagnosa' => json_encode($diagnosa)
            ]);
            $cekKesehatan->save();

            // Jalankan update tambahan setelah penyimpanan sukses
            $this->hapusAntrian($request->jadwal_id, $request->lansia_id);
            $this->updateTotal($request->jadwal_id);

            return response()->json([
                'message' => 'Data berhasil disimpan.',
                'data' => $cekKesehatan,
                'diagnosa' => $diagnosa,
                'kualitas_kesehatan' => $kualitas_kesehatan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    // public function hapusAntrian($jadwalId, $lansiaId)
    // {
    //     // URL Firebase untuk menghapus data
    //     $firebaseUrl = "https://posyandulansia-f9b02-default-rtdb.asia-southeast1.firebasedatabase.app/jadwal/$jadwalId/lansias/$lansiaId.json?auth=HutyVJgFtIlKCC2KWW4RTRTz6q254w2Qpqv0x3DJ";

    //     // Kirim request DELETE ke Firebase untuk menghapus data
    //     $response = Http::delete($firebaseUrl);

    //     // Cek apakah request ke Firebase berhasil
    //     if ($response->successful()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function hapusAntrian($jadwalId, $lansiaId)
    {
        // URL Firebase untuk mendapatkan daftar lansia dalam jadwal tertentu
        $firebaseUrl = "https://posyandulansia-f9b02-default-rtdb.asia-southeast1.firebasedatabase.app/jadwal/$jadwalId/lansias.json?auth=HutyVJgFtIlKCC2KWW4RTRTz6q254w2Qpqv0x3DJ";

        // Ambil data lansia dari Firebase
        $response = Http::get($firebaseUrl);

        // Cek apakah request berhasil
        if (!$response->successful()) {
            return response()->json(['error' => 'Gagal mengambil data lansia'], 500);
        }

        $lansias = $response->json();

        if (!$lansias) {
            return response()->json(['error' => 'Tidak ada data lansia'], 404);
        }

        // Cari key berdasarkan lansia_id
        $firebaseKey = null;
        foreach ($lansias as $key => $lansia) {
            if (isset($lansia['lansia_id']) && $lansia['lansia_id'] == $lansiaId) {
                $firebaseKey = $key;
                break;
            }
        }

        // Jika tidak ditemukan
        if (!$firebaseKey) {
            return response()->json(['error' => 'Lansia tidak ditemukan'], 404);
        }

        // Hapus data berdasarkan key unik
        $deleteUrl = "https://posyandulansia-f9b02-default-rtdb.asia-southeast1.firebasedatabase.app/jadwal/$jadwalId/lansias/$firebaseKey.json?auth=HutyVJgFtIlKCC2KWW4RTRTz6q254w2Qpqv0x3DJ";
        $deleteResponse = Http::delete($deleteUrl);

        // Cek apakah penghapusan berhasil
        if ($deleteResponse->successful()) {
            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } else {
            return response()->json(['error' => 'Gagal menghapus data'], 500);
        }
    }


    // Method untuk update total kehadiran dan cek kesehatan
    public function updateTotal($jadwalId)
    {
        // Ambil total lansia yang sudah hadir
        $totalHadir = Kehadiran::where('jadwal_id', $jadwalId)->count();

        // Hitung jumlah lansia yang sudah cek kesehatan
        $totalSudahCek = CekKesehatan::where('jadwal_id', $jadwalId)->count();

        // Total lansia yang belum cek kesehatan (Hadir - Sudah Cek)
        $totalBelumCek = $totalHadir - $totalSudahCek;

        // Data yang akan dikirim ke Firebase
        $firebaseData = [
            'totalSudahCek' => $totalSudahCek,
            'totalHadir' => $totalHadir,
            'totalBelumCek' => $totalBelumCek,
        ];

        // URL Firebase untuk update data
        $firebaseUrl = "https://posyandulansia-f9b02-default-rtdb.asia-southeast1.firebasedatabase.app/jadwal/$jadwalId/.json?auth=HutyVJgFtIlKCC2KWW4RTRTz6q254w2Qpqv0x3DJ";

        // Kirim data ke Firebase
        $response = Http::patch($firebaseUrl, $firebaseData);

        // Cek apakah request ke Firebase berhasil
        if ($response->successful()) {
            return true;
        } else {
            return false;
        }
    }

    public function getByLansiaId($lansia_id)
    {
        $data = CekKesehatan::where('lansia_id', $lansia_id)
                ->orderBy('tanggal', 'desc')
                ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Data cek kesehatan tidak ditemukan.',
                'data' => []
            ], 404);
        }

        return response()->json([
            'message' => 'Data cek kesehatan ditemukan.',
            'data' => $data
        ], 200);
    }

    // public function getKesehatanParameter($id, $parameter)
    // {
    //     $data = [
    //         'id'=> $id,
    //         'parameter' => $parameter
    //     ];

    //     return response()->json([
    //         'message' => 'Data parameter.',
    //         'data' => $data
    //     ], 200);
    // }

    public function getKesehatanParameter($id, $parameter)
    {
        // Daftar parameter yang valid
        $columns = [
            'berat_badan',
            'gula_darah',
            'kolestrol',
            'asam_urat',
            'tekanan_darah'
        ];

        // Cek apakah parameter yang diminta valid
        if (!in_array($parameter, $columns)) {
            return response()->json([
                'message' => 'Parameter yang diminta tidak valid.'
            ], 400);
        }

        // Ambil data cek kesehatan berdasarkan lansia_id
        $cekKesehatan = CekKesehatan::where('lansia_id', $id)
            ->get(); // Dapatkan semua data cek kesehatan tanpa filter berdasarkan parameter

        // Jika tidak ada data ditemukan
        if ($cekKesehatan->isEmpty()) {
            return response()->json([
                'message' => 'Data ' . str_replace('_', ' ', $parameter) . ' tidak ditemukan.',
                'data' => []
            ], 404);
        }

        // Mengatur response
        $response = [
            'message' => 'Data ' . str_replace('_', ' ', $parameter) . ' ditemukan.',
            'data' => $cekKesehatan->map(function ($record) use ($parameter) {

                if ($parameter === 'tekanan_darah') {
                    $tekananDarah = $record->tekanan_darah_sistolik . '/' . $record->tekanan_darah_diastolik;
                    $parameterValue = $tekananDarah;

                    // Parsing diagnosa untuk mendapatkan tekanan_darah
                    $diagnosa = json_decode($record->diagnosa, true);
                    $diagnosaValue = $diagnosa['tekanan_darah'] ?? null;

                } else {
                    $parameterValue = $record->$parameter; // Ambil nilai parameter yang diminta

                    // Parsing diagnosa untuk mendapatkan tekanan_darah
                    $diagnosa = json_decode($record->diagnosa, true);
                    $diagnosaValue = $diagnosa[$parameter] ?? null;

                }

                return [
                    'id' => $record->id,
                    'lansia_id' => $record->lansia_id,
                    'jadwal_id' => $record->jadwal_id,
                    'tanggal' => $record->tanggal,
                    $parameter => $parameterValue, // Menggunakan nilai parameter yang sudah digabung
                    'diagnosa' => $diagnosaValue,
                    'created_at' => $record->created_at,
                    'updated_at' => $record->updated_at,
                ];
            })
        ];

        return response()->json($response);
    }
}
