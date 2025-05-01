<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Lansia extends Model
{
    use HasFactory;
    protected $table = 'lansia'; // Menentukan nama tabel yang benar
    protected $fillable = ['user_id', 'nama', 'nik', 'ttl', 'umur', 'alamat', 'no_hp'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
