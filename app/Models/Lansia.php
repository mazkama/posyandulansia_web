<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User; 
use App\Models\Desa;
use App\Models\Kehadiran;
use App\Models\CekKesehatan;

class Lansia extends Model
{
    use HasFactory;
    protected $table = 'lansia';
    protected $fillable = ['user_id', 'desa_id', 'nama', 'nik','jenis_kelamin', 'ttl', 'umur', 'alamat', 'no_hp'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function desa()
    {
        return $this->belongsTo(Desa::class, 'desa_id');
    }

    public function kehadiran()
    {
        return $this->hasMany(Kehadiran::class, 'lansia_id');
    }

    public function cekKesehatan()
    {
        return $this->hasMany(CekKesehatan::class, 'lansia_id');
    }
}
