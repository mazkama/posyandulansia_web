<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Kader extends Model
{
    use HasFactory;
    protected $table = 'kader';
    protected $fillable = ['user_id', 'desa_id', 'nama', 'nik','jenis_kelamin', 'ttl', 'umur', 'alamat', 'no_hp'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function desa()
    {
        return $this->belongsTo(\App\Models\Desa::class, 'desa_id');
    }
}
