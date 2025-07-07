<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use HasFactory;
    protected $table = 'notifikasi';
    protected $fillable = ['pesan', 'tanggal_kirim', 'desa_id'];

    public function desa()
    {
        return $this->belongsTo(\App\Models\Desa::class, 'desa_id');
    }
}
