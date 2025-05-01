<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $table = 'jadwal'; // Menentukan nama tabel yang benar
    protected $fillable = ['tanggal', 'waktu', 'lokasi', 'keterangan'];

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'jadwal_id');
    }

    public function cekKesehatan()
    {
        return $this->hasMany(CekKesehatan::class, 'jadwal_id');
    }
}
