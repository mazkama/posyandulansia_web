<?php

namespace App\Http\Controllers;

use App\Models\CekKesehatan;
use App\Models\Jadwal;
use App\Models\Kehadiran;
use App\Models\Lansia;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;


class CekKesehatanController extends Controller
{
    public function index(Request $request)
    {
        $query = Jadwal::orderBy('tanggal', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal', '=', $request->start_date);
        }

        $jadwals = $query->get();

        return view('pages.cekkesehatan.index', compact('jadwals'));
    }


    public function show(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');

        //Ambil semua lansia yang hadir pada jadwal tersebut
        $lansiaHadirIds = Kehadiran::where('jadwal_id', $jadwalId)->pluck('lansia_id');

        //Ambil semua lansia yang sudah cek kesehatan pada jadwal tersebut
        $lansiaSudahCekIds = CekKesehatan::where('jadwal_id', $jadwalId)->pluck('lansia_id');

        //Ambil semua lansia yang hadir tapi belum cek kesehatan
        $lansiaBelumCek = Lansia::whereIn('id', $lansiaHadirIds)
            ->whereNotIn('id', $lansiaSudahCekIds)
            ->get();

        //Ambil jumlah lansia yang hadir
        $totalHadir = $lansiaHadirIds->count();

        //Ambil jumlah lansia yang belum cek kesehatan
        $totalBelumCek = $lansiaBelumCek->count();

        //Ambil jumlah lansia yang sudah cek kesehatan
        $totalSudahCek = $lansiaSudahCekIds->count();

        //Kirim data ke view
        return view('pages.cekkesehatan.show', compact('lansiaBelumCek', 'jadwalId', 'totalHadir', 'totalBelumCek', 'totalSudahCek'));
    }

    //remake function show
    // public function show(Request $request)
    // {
    //     $jadwalId = $request->query('jadwal_id');
    //     $lansiaBelumCek = CekKesehatan::with('lansia','kehadiran')->where('jadwal_id', $jadwalId)->get();

    //     dd($lansiaBelumCek);



    //     $kehadirans = kehadiran::with('lansia')->where('jadwal_id', $jadwalId)->get();
    //     // $c = Kehadiran::where('jadwal_id', $jadwalId)
    //     //     ->pluck('lansia_id')
    //     //     ->toArray();
    //     // $selesaiCek = CekKesehatan::where('jadwal_id',$jadwalId)
    //     //     ->pluck('lansia_id')
    //     //     ->count();
    //     return view('pages.cekkesehatan.show', compact('kehadirans', 'jadwalId'));
    // }

    public function create(Request $request)
    {
        $jadwalId = $request->query('jadwal_id');
        $lansiaId = $request->query('lansia_id');

        return view('pages.cekkesehatan.create', compact('jadwalId', 'lansiaId'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'lansia_id' => 'required',
    //         'jadwal_id' => 'required',
    //         'tanggal' => 'required|date',
    //         'berat_badan' => 'required|numeric',
    //         'tekanan_darah' => 'required|numeric',
    //         'gula_darah' => 'required|numeric',
    //         'kolestrol' => 'required|numeric',
    //     ]);

    //     CekKesehatan::create($request->all());

    //     return redirect()
    //         ->route('cekKesehatan.show', ['jadwal_id' => $request['jadwal_id']])
    //         ->with('success', 'Data berhasil disimpan.');
    // }

    // public function store(Request $request)
    // {  
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


    //     // Ambil nilai atau set ke 0 jika null
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
    //         $diagnosa['kolestrol'] = $kolestrol > 200 ? "Kolesterol tinggi" : "Normal";
    //     }

    //     if ($asam_urat > 0) {
    //         $diagnosa['asam_urat'] = $asam_urat > 7 ? "Asam urat tinggi" : "Normal";
    //     }

    //     // Tentukan kualitas kesehatan
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
    //         // Simpan ke database
    //         $cekKesehatan = new CekKesehatan();
    //         $cekKesehatan->fill([
    //             'lansia_id' => $request->lansia_id,
    //             'jadwal_id' => $request->jadwal_id,
    //             'tanggal' => $jadwal->tanggal,
    //             'berat_badan' => $request->berat_badan,
    //             'tekanan_darah_sistolik' => $request->tekanan_darah_sistolik,
    //             'tekanan_darah_diastolik' => $request->tekanan_darah_diastolik,
    //             'gula_darah' => $request->gula_darah,
    //             'kolestrol' => $request->kolestrol,
    //             'asam_urat' => $request->asam_urat,
    //             'diagnosa' => json_encode($diagnosa)
    //         ]);
    //         $cekKesehatan->save();

    //         // Update antrian & total
    //         $this->hapusAntrian($request->jadwal_id, $request->lansia_id);
    //         $this->updateTotal($request->jadwal_id);

    //         return redirect()->route('cekKesehatan.show', ['jadwal_id' => $request->jadwal_id])
    //             ->with('success', 'Data berhasil disimpan.')
    //             ->with('kualitas_kesehatan', $kualitas_kesehatan)
    //             ->with('diagnosa', $diagnosa);
    //     } catch (\Exception $e) {
    //         \Log::error('Gagal menyimpan cek kesehatan: ' . $e->getMessage());
    //         return redirect()->back()
    //             ->withInput()
    //             ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    //     }
    // }

    public function store(Request $request)
    {
        $request->validate([
            'lansia_id' => 'required|exists:lansia,id',
            'jadwal_id' => 'required|exists:jadwal,id',
            'berat_badan' => 'required|numeric|min:1',
            'tekanan_darah_sistolik' => 'required|numeric|min:1',
            'tekanan_darah_diastolik' => 'required|numeric|min:1',
            'gula_darah' => 'required|numeric|min:1',
            'kolestrol' => 'required|numeric|min:1',
            'asam_urat' => 'required|numeric|min:1',
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

            return redirect()->route('cekKesehatan.show', ['jadwal_id' => $request->jadwal_id])
                ->with('success', 'Data berhasil disimpan.')
                ->with('diagnosa', $diagnosa)
                ->with('kesimpulan', $kesimpulan);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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




    // public function edit($id)
    // {
    //     $cekKesehatan = CekKesehatan::findOrFail($id);
    //     $lansias = Lansia::all();
    //     $jadwals = Jadwal::all();
    //     return view('cek_kesehatan.edit', compact('cekKesehatan', 'lansias', 'jadwals'));
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'lansia_id' => 'required|exists:lansia,id',
    //         'jadwal_id' => 'required|exists:jadwal,id',
    //         'tanggal' => 'required|date',
    //         'berat_badan' => 'required|numeric',
    //         'tekanan_darah' => 'required|string',
    //         'gula_darah' => 'required|numeric',
    //         'kolestrol' => 'required|numeric',
    //     ]);

    //     $cekKesehatan = CekKesehatan::findOrFail($id);
    //     $cekKesehatan->update($request->all());

    //     return redirect()->route('cek_kesehatan.index')->with('success', 'Data berhasil diperbarui.');
    // }

    // public function destroy($id)
    // {
    //     $cekKesehatan = CekKesehatan::findOrFail($id);
    //     $cekKesehatan->delete();

    //     return redirect()->route('cek_kesehatan.index')->with('success', 'Data berhasil dihapus.');
    // }


}
