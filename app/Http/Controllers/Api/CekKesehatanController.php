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
use Kreait\Firebase\Factory;

class CekKesehatanController extends Controller
{

    // public function store(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'lansia_id' => 'required|exists:lansia,id',
    //         'jadwal_id' => 'required|exists:jadwal,id',
    //         'berat_badan' => 'nullable|numeric|min:0',
    //         'tekanan_darah_sistolik' => 'nullable|integer|min:0',
    //         'tekanan_darah_diastolik' => 'nullable|integer|min:0',
    //         'gula_darah' => 'nullable|numeric|min:0',
    //         'kolestrol' => 'nullable|numeric|min:0',
    //         'asam_urat' => 'nullable|numeric|min:0',
    //         'diagnosa' => 'nullable|string'
    //     ]);

    //     // Ambil data dengan nilai default 0 jika kosong
    //     $berat_badan = $request->berat_badan ?? 0;
    //     $tekanan_darah_sistolik = $request->tekanan_darah_sistolik ?? 0;
    //     $tekanan_darah_diastolik = $request->tekanan_darah_diastolik ?? 0;
    //     $gula_darah = $request->gula_darah ?? 0;
    //     $kolestrol = $request->kolestrol ?? 0;
    //     $asam_urat = $request->asam_urat ?? 0;

    //     // Diagnosa otomatis
    //     $diagnosa = [];

    //     if ($berat_badan > 0) {
    //         if ($berat_badan < 40) {
    //             $diagnosa['berat_badan'] = "Berat badan terlalu rendah";
    //         } elseif ($berat_badan > 90) {
    //             $diagnosa['berat_badan'] = "Berat badan berlebihan";
    //         } else {
    //             $diagnosa['berat_badan'] = "Normal";
    //         }
    //     }

    //     if ($tekanan_darah_sistolik > 0 && $tekanan_darah_diastolik > 0) {
    //         if ($tekanan_darah_sistolik < 90 || $tekanan_darah_diastolik < 60) {
    //             $diagnosa['tekanan_darah'] = "Hipotensi (tekanan darah rendah)";
    //         } elseif ($tekanan_darah_sistolik > 140 || $tekanan_darah_diastolik > 90) {
    //             $diagnosa['tekanan_darah'] = "Hipertensi (tekanan darah tinggi)";
    //         } else {
    //             $diagnosa['tekanan_darah'] = "Normal";
    //         }
    //     }

    //     if ($gula_darah > 0) {
    //         if ($gula_darah < 70) {
    //             $diagnosa['gula_darah'] = "Hipoglikemia (gula darah rendah)";
    //         } elseif ($gula_darah > 126) {
    //             $diagnosa['gula_darah'] = "Diabetes (gula darah tinggi)";
    //         } else {
    //             $diagnosa['gula_darah'] = "Normal";
    //         }
    //     }

    //     if ($kolestrol > 0) {
    //         if ($kolestrol > 200) {
    //             $diagnosa['kolestrol'] = "Kolesterol tinggi";
    //         } else {
    //             $diagnosa['kolestrol'] = "Normal";
    //         }
    //     }

    //     if ($asam_urat > 0) {
    //         if ($asam_urat > 7) {
    //             $diagnosa['asam_urat'] = "Asam urat tinggi";
    //         } else {
    //             $diagnosa['asam_urat'] = "Normal";
    //         }
    //     }

    //     // Menentukan kualitas kesehatan secara keseluruhan
    //     $kualitas_kesehatan = "Sehat";

    //     if (
    //         in_array("Hipertensi (tekanan darah tinggi)", $diagnosa) ||
    //         in_array("Diabetes (gula darah tinggi)", $diagnosa) ||
    //         in_array("Kolesterol tinggi", $diagnosa) ||
    //         in_array("Asam urat tinggi", $diagnosa)
    //     ) {
    //         $kualitas_kesehatan = "Berisiko";
    //     }

    //     if (
    //         in_array("Hipotensi (tekanan darah rendah)", $diagnosa) ||
    //         in_array("Hipoglikemia (gula darah rendah)", $diagnosa) ||
    //         in_array("Berat badan terlalu rendah", $diagnosa)
    //     ) {
    //         $kualitas_kesehatan = "Tidak Sehat";
    //     }

    //     $jadwal = Jadwal::findOrFail($request->jadwal_id);

    //     try {
    //         // Simpan data cek kesehatan
    //         $cekKesehatan = new CekKesehatan();
    //         $cekKesehatan->fill([
    //             'lansia_id' => $request->lansia_id,
    //             'jadwal_id' => $request->jadwal_id,
    //             'tanggal' => $jadwal->tanggal,
    //             'berat_badan' => $berat_badan,
    //             'tekanan_darah_sistolik' => $tekanan_darah_sistolik,
    //             'tekanan_darah_diastolik' => $tekanan_darah_diastolik,
    //             'gula_darah' => $gula_darah,
    //             'kolestrol' => $kolestrol,
    //             'asam_urat' => $asam_urat,
    //             'diagnosa' => json_encode($diagnosa)
    //         ]);
    //         $cekKesehatan->save();

    //         // Jalankan update tambahan setelah penyimpanan sukses
    //         $this->hapusAntrian($request->jadwal_id, $request->lansia_id);
    //         $this->updateTotal($request->jadwal_id);

    //         return response()->json([
    //             'message' => 'Data berhasil disimpan.',
    //             'data' => $cekKesehatan,
    //             'diagnosa' => $diagnosa,
    //             'kualitas_kesehatan' => $kualitas_kesehatan
    //         ], 201);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Terjadi kesalahan saat menyimpan data.',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function store(Request $request)
    {
        $request->validate([
            'lansia_id' => 'required|exists:lansia,id',
            'jadwal_id' => 'required|exists:jadwal,id', 
            'berat_badan' => 'required|numeric',
            'tekanan_darah_sistolik' => 'required|numeric',
            'tekanan_darah_diastolik' => 'required|numeric',
            'gula_darah' => 'required|numeric',
            'kolestrol' => 'required|numeric',
            'asam_urat' => 'required|numeric',
        ]);

        try {
            $jadwal = Jadwal::findOrFail($request->jadwal_id);

            $berat_badan = $request->berat_badan;
            $sistolik = $request->tekanan_darah_sistolik;
            $diastolik = $request->tekanan_darah_diastolik;
            $gula_darah = $request->gula_darah;
            $kolestrol = $request->kolestrol;
            $asam_urat = $request->asam_urat;

            $diagnosa = [];
            $kesimpulan = [];

            // Diagnosa individu
            if ($sistolik > 140 || $diastolik > 90) {
                $diagnosa[] = 'Hipertensi';
            }
            if ($gula_darah > 140) {
                $diagnosa[] = 'Diabetes Mellitus';
            }
            if ($kolestrol > 200) {
                $diagnosa[] = 'Hiperkolesterolemia';
            }
            if ($asam_urat > 7) {
                $diagnosa[] = 'Asam Urat Tinggi';
            }
 
            // Kesimpulan
            $jumlahDiagnosa = count($diagnosa);

            // Kesimpulan detail berdasarkan kombinasi diagnosa
            // sort($diagnosa); // untuk konsistensi dalam pencocokan
            // dd($diagnosa);

            if ($jumlahDiagnosa === 0) {
                $kesimpulan[] = "Kondisi kesehatan dalam batas normal.";
            } elseif ($jumlahDiagnosa === 1) {
                $single = $diagnosa[0];
                switch ($single) {
                    case 'Hipertensi':
                        $kesimpulan[] = "Tensi Tinggi → Hipertensi";
                        $kesimpulan[] = "Meningkatkan risiko serangan jantung, stroke, gagal ginjal, dan penyakit mata.";
                        $kesimpulan[] = "Bisa menyebabkan pembuluh darah kaku (aterosklerosis) dan mempercepat kerusakan organ.";
                        break;
                    case 'Diabetes Mellitus':
                        $kesimpulan[] = "Gula Darah Tinggi → Diabetes Mellitus";
                        $kesimpulan[] = "Berisiko mengalami komplikasi seperti kerusakan saraf, gangguan ginjal, gangguan mata, dan luka sulit sembuh.";
                        $kesimpulan[] = "Jika tidak dikontrol, dapat menyebabkan penyakit jantung dan stroke.";
                        break;
                    case 'Hiperkolesterolemia':
                        $kesimpulan[] = "Kolesterol Tinggi → Hiperkolesterolemia";
                        $kesimpulan[] = "Menyebabkan penyempitan pembuluh darah (aterosklerosis), meningkatkan risiko serangan jantung dan stroke.";
                        break;
                    case 'Asam Urat Tinggi':
                        $kesimpulan[] = "Asam Urat Tinggi → Gout & Gangguan Ginjal";
                        $kesimpulan[] = "Berisiko mengalami radang sendi (gout), batu ginjal, serta gangguan ginjal kronis.";
                        break;
                }
            } elseif ($jumlahDiagnosa === 2) {
                $combination = implode(' + ', $diagnosa);
                switch ($combination) {
                    case 'Diabetes Mellitus + Hiperkolesterolemia':
                        $kesimpulan[] = "Gula Darah dan Kolesterol Tinggi → Diabetes dengan Risiko Jantung";
                        $kesimpulan[] = "Kondisi ini sering terjadi bersamaan dan meningkatkan risiko serangan jantung, stroke, dan gangguan saraf.";
                        break;
                    case 'Diabetes Mellitus + Asam Urat Tinggi':
                        $kesimpulan[] = "Gula Darah dan Asam Urat Tinggi → Diabetes dengan Risiko Gout dan Batu Ginjal";
                        $kesimpulan[] = "Lansia dengan kombinasi ini berisiko mengalami gout, batu ginjal, serta komplikasi diabetes seperti gangguan saraf dan ginjal.";
                        break;
                    case 'Hiperkolesterolemia + Asam Urat Tinggi':
                        $kesimpulan[] = "Kolesterol dan Asam Urat Tinggi → Penyakit Jantung dan Gangguan Sendi";
                        $kesimpulan[] = "Bisa menyebabkan penyakit jantung, radang sendi (gout), dan batu ginjal.";
                        break;
                    case 'Hipertensi + Diabetes Mellitus':
                        $kesimpulan[] = "Tensi dan Gula Darah Tinggi → Risiko Sindrom Metabolik dan Penyakit Jantung";
                        $kesimpulan[] = "Hipertensi dan diabetes sering terjadi bersamaan, meningkatkan risiko penyakit jantung, stroke, dan gagal ginjal.";
                        break;
                    case 'Hipertensi + Hiperkolesterolemia':
                        $kesimpulan[] = "Tensi dan Kolesterol Tinggi → Risiko Serangan Jantung dan Stroke";
                        $kesimpulan[] = "Kombinasi ini mempercepat penyumbatan pembuluh darah, berisiko tinggi mengalami serangan jantung dan stroke.";
                        break;
                    case 'Hipertensi + Asam Urat Tinggi':
                        $kesimpulan[] = "Tensi dan Asam Urat Tinggi → Risiko Hipertensi Kronis dan Gangguan Ginjal";
                        $kesimpulan[] = "Hipertensi dan asam urat tinggi dapat menyebabkan gangguan ginjal kronis dan memperburuk kerusakan pembuluh darah.";
                        break;
                    default:
                        $kesimpulan[] = "Terdapat kombinasi dua penyakit: " . implode(', ', $diagnosa) . ". Disarankan pemeriksaan lanjutan.";
                        break;
                }
            } elseif ($jumlahDiagnosa === 3) {
                if (in_array('Hipertensi', $diagnosa) && in_array('Diabetes Mellitus', $diagnosa) && in_array('Hiperkolesterolemia', $diagnosa)) {
                    $kesimpulan[] = "Tensi, Gula Darah, dan Kolesterol Tinggi → Sindrom Metabolik Parah dan Risiko Jantung";
                    $kesimpulan[] = "Kombinasi ini mempercepat aterosklerosis, gagal ginjal, stroke, dan serangan jantung.";
                } elseif (in_array('Hipertensi', $diagnosa) && in_array('Diabetes Mellitus', $diagnosa) && in_array('Asam Urat Tinggi', $diagnosa)) {
                    $kesimpulan[] = "Tensi, Gula Darah, dan Asam Urat Tinggi → Diabetes dan Komplikasi Ginjal";
                    $kesimpulan[] = "Berisiko mengalami gagal ginjal, neuropati (kerusakan saraf), serta penyakit jantung.";
                } elseif (in_array('Hipertensi', $diagnosa) && in_array('Hiperkolesterolemia', $diagnosa) && in_array('Asam Urat Tinggi', $diagnosa)) {
                    $kesimpulan[] = "Tensi, Kolesterol, dan Asam Urat Tinggi → Risiko Jantung dan Stroke dengan Gangguan Sendi";
                    $kesimpulan[] = "Bisa menyebabkan penyumbatan pembuluh darah, stroke, serta peradangan sendi akibat gout.";
                } elseif (in_array('Diabetes Mellitus', $diagnosa) && in_array('Hiperkolesterolemia', $diagnosa) && in_array('Asam Urat Tinggi', $diagnosa)) {
                    $kesimpulan[] = "Gula Darah, Kolesterol, dan Asam Urat Tinggi → Diabetes dengan Risiko Jantung dan Gout";
                    $kesimpulan[] = "Bisa menyebabkan serangan jantung, stroke, neuropati, serta nyeri sendi akibat gout.";
                } else {
                    $kesimpulan[] = "Terdapat tiga parameter yang tinggi: " . implode(', ', $diagnosa) . ". Disarankan evaluasi menyeluruh.";
                }
            } else {
                $kesimpulan[] = "Tensi, Gula Darah, Kolesterol, dan Asam Urat Tinggi → Sindrom Metabolik Berat dan Risiko Fatal";
                $kesimpulan[] = "Kombinasi ini sangat berbahaya karena meningkatkan risiko serangan jantung, stroke, gagal ginjal, neuropati, serta peradangan sendi parah.";
                $kesimpulan[] = "Lansia dengan kondisi ini membutuhkan pemantauan ketat, pengobatan, dan perubahan gaya hidup drastis.";
            }

            // Cek apakah data sudah ada
            $cekDataSama = CekKesehatan::where('lansia_id', $request->lansia_id)
                ->where('jadwal_id', $request->jadwal_id)
                ->exists();

            if ($cekDataSama) {
                return redirect()->back()
                    ->with('error', 'Data sudah pernah diinput sebelumnya. Tidak dapat memasukkan data yang sama.');
            }

            // Simpan ke DB
            $cekKesehatan = new CekKesehatan();
            $cekKesehatan->fill([
                'lansia_id' => $request->lansia_id,
                'jadwal_id' => $request->jadwal_id,
                'tanggal' => $jadwal->tanggal,
                'berat_badan' => $berat_badan,
                'tekanan_darah_sistolik' => $sistolik,
                'tekanan_darah_diastolik' => $diastolik,
                'gula_darah' => $gula_darah,
                'kolestrol' => $kolestrol,
                'asam_urat' => $asam_urat,
                'diagnosa' => json_encode($diagnosa),
            ]);
            $cekKesehatan->save();

            // Update antrian & total
            $this->hapusAntrian($request->jadwal_id, $request->lansia_id);
            $this->updateTotal($request->jadwal_id);

            return response()->json([
                'message' => 'Data berhasil disimpan.',
                'data' => $cekKesehatan,
                'kesimpulan' => $kesimpulan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function hapusAntrian($jadwalId, $lansiaId): bool
    {
        $credentialsPath = storage_path('app/firebase_credentials.json');

        try {
            // Menginisialisasi Firebase
            $firebase = (new Factory)
                ->withServiceAccount($credentialsPath)
                ->withDatabaseUri('https://posyandulansia-f9b02-default-rtdb.asia-southeast1.firebasedatabase.app/');

            $database = $firebase->createDatabase();

            // Ambil data lansias dari Firebase
            $lansiasRef = $database->getReference("jadwal/$jadwalId/lansias");
            $lansias = $lansiasRef->getValue();

            if (empty($lansias)) {
                \Log::warning("Data lansia tidak ditemukan untuk jadwal ID: $jadwalId");
                return false;
            }

            // Cari key berdasarkan lansia_id
            $firebaseKey = null;
            foreach ($lansias as $key => $lansia) {
                if (isset($lansia['lansia_id']) && $lansia['lansia_id'] == $lansiaId) {
                    $firebaseKey = $key;
                    break;
                }
            }

            if ($firebaseKey === null) {
                \Log::warning("Lansia dengan ID $lansiaId tidak ditemukan pada jadwal ID: $jadwalId");
                return false;
            }

            // Hapus data lansia berdasarkan key
            $lansiasRef->getChild($firebaseKey)->remove();

            // Update total setelah penghapusan
            $this->updateTotal($jadwalId);

            return true;

        } catch (\Exception $e) {
            \Log::error('Gagal menghapus antrian Firebase:', ['message' => $e->getMessage()]);
            return false;
        }
    }

    // Method untuk update total kehadiran dan cek kesehatan
    public function updateTotal($jadwalId): bool
    {
        $credentialsPath = storage_path('app/firebase_credentials.json');

        try {
            // Menginisialisasi Firebase
            $firebase = (new Factory)
                ->withServiceAccount($credentialsPath)
                ->withDatabaseUri('https://posyandulansia-f9b02-default-rtdb.asia-southeast1.firebasedatabase.app/');

            $database = $firebase->createDatabase();

            // Menghitung total kehadiran dan cek kesehatan di database lokal
            $totalHadir = Kehadiran::where('jadwal_id', $jadwalId)->count();
            $totalSudahCek = CekKesehatan::where('jadwal_id', $jadwalId)->count();
            $totalBelumCek = max(0, $totalHadir - $totalSudahCek);

            // Validasi: Jika semua nilai adalah 0, jangan update Firebase
            if ($totalHadir === 0 && $totalSudahCek === 0 && $totalBelumCek === 0) {
                \Log::warning("Update dibatalkan: semua nilai total adalah 0 untuk jadwal ID: $jadwalId");
                return false;
            }

            // Data yang akan diupdate ke Firebase
            $firebaseData = [
                'totalHadir' => $totalHadir,
                'totalSudahCek' => $totalSudahCek,
                'totalBelumCek' => $totalBelumCek,
            ];

            // Update ke Firebase
            $database->getReference("jadwal/$jadwalId")->update($firebaseData);

            return true;

        } catch (\Exception $e) {
            \Log::error('Update Total Firebase Error:', ['message' => $e->getMessage()]);
            return false;
        }
    }


    public function getByLansiaId(Request $request)
    {
        $lansia_id = $request->input('lansia_id');
        $jadwal_id = $request->input('jadwal_id');

        if (empty($lansia_id) || empty($jadwal_id)) {
            return response()->json([
                'message' => 'Lansia ID dan Jadwal ID tidak boleh kosong.',
                'data' => []
            ], 400);
        }
        
        $data = CekKesehatan::where('lansia_id', $lansia_id)
                ->where('jadwal_id', $jadwal_id) 
                ->orderBy('tanggal', 'desc')
                ->get();

        if ($data->isEmpty()) {
            return response()->json([
                'message' => 'Data cek kesehatan tidak ditemukan.',
                'data' => []
            ], 404);
        }

        // Process diagnoses and conclusions for each entry
        $processedData = $data->map(function ($item) {
            $diagnosa = json_decode($item->diagnosa, true);
            $kesimpulan = [];

            $jumlahDiagnosa = count($diagnosa);

            // Conclusion generation based on diagnoses
            if ($jumlahDiagnosa === 0) {
                $kesimpulan[] = "Kondisi kesehatan dalam batas normal.";
            } elseif ($jumlahDiagnosa === 1) {
                $single = $diagnosa[0];
                switch ($single) {
                    case 'Hipertensi':
                        $kesimpulan[] = "Tensi Tinggi → Hipertensi";
                        $kesimpulan[] = "Meningkatkan risiko serangan jantung, stroke, gagal ginjal, dan penyakit mata.";
                        $kesimpulan[] = "Bisa menyebabkan pembuluh darah kaku (aterosklerosis) dan mempercepat kerusakan organ.";
                        break;
                    case 'Diabetes Mellitus':
                        $kesimpulan[] = "Gula Darah Tinggi → Diabetes Mellitus";
                        $kesimpulan[] = "Berisiko mengalami komplikasi seperti kerusakan saraf, gangguan ginjal, gangguan mata, dan luka sulit sembuh.";
                        $kesimpulan[] = "Jika tidak dikontrol, dapat menyebabkan penyakit jantung dan stroke.";
                        break;
                    case 'Hiperkolesterolemia':
                        $kesimpulan[] = "Kolesterol Tinggi → Hiperkolesterolemia";
                        $kesimpulan[] = "Menyebabkan penyempitan pembuluh darah (aterosklerosis), meningkatkan risiko serangan jantung dan stroke.";
                        break;
                    case 'Asam Urat Tinggi':
                        $kesimpulan[] = "Asam Urat Tinggi → Gout & Gangguan Ginjal";
                        $kesimpulan[] = "Berisiko mengalami radang sendi (gout), batu ginjal, serta gangguan ginjal kronis.";
                        break;
                }
            } elseif ($jumlahDiagnosa === 2) {
                $combination = implode(' + ', $diagnosa);
                switch ($combination) {
                    case 'Diabetes Mellitus + Hiperkolesterolemia':
                        $kesimpulan[] = "Gula Darah dan Kolesterol Tinggi → Diabetes dengan Risiko Jantung";
                        $kesimpulan[] = "Kondisi ini sering terjadi bersamaan dan meningkatkan risiko serangan jantung, stroke, dan gangguan saraf.";
                        break;
                    case 'Diabetes Mellitus + Asam Urat Tinggi':
                        $kesimpulan[] = "Gula Darah dan Asam Urat Tinggi → Diabetes dengan Risiko Gout dan Batu Ginjal";
                        $kesimpulan[] = "Lansia dengan kombinasi ini berisiko mengalami gout, batu ginjal, serta komplikasi diabetes seperti gangguan saraf dan ginjal.";
                        break;
                    case 'Hiperkolesterolemia + Asam Urat Tinggi':
                        $kesimpulan[] = "Kolesterol dan Asam Urat Tinggi → Penyakit Jantung dan Gangguan Sendi";
                        $kesimpulan[] = "Bisa menyebabkan penyakit jantung, radang sendi (gout), dan batu ginjal.";
                        break;
                    case 'Hipertensi + Diabetes Mellitus':
                        $kesimpulan[] = "Tensi dan Gula Darah Tinggi → Risiko Sindrom Metabolik dan Penyakit Jantung";
                        $kesimpulan[] = "Hipertensi dan diabetes sering terjadi bersamaan, meningkatkan risiko penyakit jantung, stroke, dan gagal ginjal.";
                        break;
                    case 'Hipertensi + Hiperkolesterolemia':
                        $kesimpulan[] = "Tensi dan Kolesterol Tinggi → Risiko Serangan Jantung dan Stroke";
                        $kesimpulan[] = "Kombinasi ini mempercepat penyumbatan pembuluh darah, berisiko tinggi mengalami serangan jantung dan stroke.";
                        break;
                    case 'Hipertensi + Asam Urat Tinggi':
                        $kesimpulan[] = "Tensi dan Asam Urat Tinggi → Risiko Hipertensi Kronis dan Gangguan Ginjal";
                        $kesimpulan[] = "Hipertensi dan asam urat tinggi dapat menyebabkan gangguan ginjal kronis dan memperburuk kerusakan pembuluh darah.";
                        break;
                    default:
                        $kesimpulan[] = "Terdapat kombinasi dua penyakit: " . implode(', ', $diagnosa) . ". Disarankan pemeriksaan lanjutan.";
                        break;
                }
            } elseif ($jumlahDiagnosa === 3) {
                if (in_array('Hipertensi', $diagnosa) && in_array('Diabetes Mellitus', $diagnosa) && in_array('Hiperkolesterolemia', $diagnosa)) {
                    $kesimpulan[] = "Tensi, Gula Darah, dan Kolesterol Tinggi → Sindrom Metabolik Parah dan Risiko Jantung";
                    $kesimpulan[] = "Kombinasi ini mempercepat aterosklerosis, gagal ginjal, stroke, dan serangan jantung.";
                } elseif (in_array('Hipertensi', $diagnosa) && in_array('Diabetes Mellitus', $diagnosa) && in_array('Asam Urat Tinggi', $diagnosa)) {
                    $kesimpulan[] = "Tensi, Gula Darah, dan Asam Urat Tinggi → Diabetes dan Komplikasi Ginjal";
                    $kesimpulan[] = "Berisiko mengalami gagal ginjal, neuropati (kerusakan saraf), serta penyakit jantung.";
                } elseif (in_array('Hipertensi', $diagnosa) && in_array('Hiperkolesterolemia', $diagnosa) && in_array('Asam Urat Tinggi', $diagnosa)) {
                    $kesimpulan[] = "Tensi, Kolesterol, dan Asam Urat Tinggi → Risiko Jantung dan Stroke dengan Gangguan Sendi";
                    $kesimpulan[] = "Bisa menyebabkan penyumbatan pembuluh darah, stroke, serta peradangan sendi akibat gout.";
                } elseif (in_array('Diabetes Mellitus', $diagnosa) && in_array('Hiperkolesterolemia', $diagnosa) && in_array('Asam Urat Tinggi', $diagnosa)) {
                    $kesimpulan[] = "Gula Darah, Kolesterol, dan Asam Urat Tinggi → Diabetes dengan Risiko Jantung dan Gout";
                    $kesimpulan[] = "Bisa menyebabkan serangan jantung, stroke, neuropati, serta nyeri sendi akibat gout.";
                } else {
                    $kesimpulan[] = "Terdapat tiga parameter yang tinggi: " . implode(', ', $diagnosa) . ". Disarankan evaluasi menyeluruh.";
                }
            } else {
                $kesimpulan[] = "Tensi, Gula Darah, Kolesterol, dan Asam Urat Tinggi → Sindrom Metabolik Berat dan Risiko Fatal";
                $kesimpulan[] = "Kombinasi ini sangat berbahaya karena meningkatkan risiko serangan jantung, stroke, gagal ginjal, neuropati, serta peradangan sendi parah.";
                $kesimpulan[] = "Lansia dengan kondisi ini membutuhkan pemantauan ketat, pengobatan, dan perubahan gaya hidup drastis.";
            }

            $item->kesimpulan = $kesimpulan;
            return $item;
        });

        return response()->json([
            'message' => 'Data cek kesehatan ditemukan.',
            'data' => $processedData
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
