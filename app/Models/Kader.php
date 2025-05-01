<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class Kader extends Model
{
    use HasFactory;
    protected $table = 'kader'; // Menentukan nama tabel yang benar
    protected $fillable = ['user_id', 'nama', 'nik', 'ttl', 'umur', 'alamat', 'no_hp'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
