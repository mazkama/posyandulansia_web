<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;
    protected $table = 'notifikasi'; // Menentukan nama tabel yang benar
    protected $fillable = ['pesan', 'tanggal_kirim'];

    // protected $fillable = ['lansia_id', 'pesan', 'tanggal_kirim'];

    // public function lansia()
    // {
    //     return $this->belongsTo(Lansia::class, 'lansia_id');
    // }
}
