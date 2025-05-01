<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;
    protected $table = 'berita'; // Menentukan nama tabel yang benar

    protected $fillable = ['judul', 'konten', 'tanggal_publish'];
}

