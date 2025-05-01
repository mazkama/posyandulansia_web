<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $table = 'laporan'; // Menentukan nama tabel yang benar
    protected $fillable = ['cek_kesehatan_id', 'tanggal_cek', 'file_path'];

    public function cekKesehatan()
    {
        return $this->belongsTo(CekKesehatan::class, 'cek_kesehatan_id');
    }
}
