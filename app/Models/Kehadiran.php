<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kehadiran extends Model
{
    use HasFactory;
    protected $table = 'kehadiran'; // Menentukan nama tabel yang benar
    protected $fillable = ['lansia_id', 'jadwal_id', 'status'];

    public function lansia()
    {
        return $this->belongsTo(Lansia::class, 'lansia_id');
    }

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_id');
    }
}
