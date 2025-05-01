<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CekKesehatan extends Model
{
    use HasFactory;protected $table = 'cek_kesehatan'; // Menentukan nama tabel yang benar
    protected $fillable = ['lansia_id', 'jadwal_id', 'tanggal', 'berat_badan', 'tekanan_darah_sistolik', 'tekanan_darah_diastolik','gula_darah','kolestrol','asam_urat', 'diagnosa'];

    public function lansia()
    {
        return $this->belongsTo(Lansia::class, 'lansia_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }

    public function laporan()
    {
        return $this->hasOne(Laporan::class, 'cek_kesehatan_id');
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     // Buat diagnosa sebelum menyimpan
    //     static::saving(function ($cekKesehatan) {
    //         $cekKesehatan->diagnosa = $cekKesehatan->generateDiagnosa();
    //     });
    // }

    // public function generateDiagnosa()
    // {
    //     $diagnosa = [];

    //     // **Hipertensi**
    //     if ($this->tekanan_darah > 130) {
    //         $diagnosa[] = "Kemungkinan Hipertensi";
    //     }

    //     // **Diabetes atau Prediabetes**
    //     if ($this->gula_darah >= 140 && $this->gula_darah <= 199) {
    //         $diagnosa[] = "Kemungkinan Prediabetes";
    //     } elseif ($this->gula_darah >= 200) {
    //         $diagnosa[] = "Kemungkinan Diabetes Mellitus";
    //     }

    //     // **Kolesterol Tinggi**
    //     if ($this->kolestrol > 240) {
    //         $diagnosa[] = "Kolesterol Tinggi, risiko penyakit jantung meningkat";
    //     }

    //     // **Asam Urat Tinggi**
    //     if ($this->asam_urat > 7.0) {
    //         $diagnosa[] = "Risiko Asam Urat Tinggi (Gout)";
    //     }

    //     // **Obesitas**
    //     if ($this->berat_badan >= 30) {
    //         $diagnosa[] = "Obesitas, risiko komplikasi kesehatan";
    //     }

    //     // **Skenario Kombinasi**
    //     if ($this->tekanan_darah > 130 && $this->kolestrol > 240 && $this->gula_darah > 140) {
    //         $diagnosa[] = "Risiko tinggi penyakit jantung atau stroke";
    //     }

    //     return count($diagnosa) > 0 ? implode(", ", $diagnosa) : "Kondisi kesehatan dalam batas normal";
    // }
}
