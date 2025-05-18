<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class BeritaSeeder extends Seeder
{
    public function run()
    {
        $data = [];

        for ($i = 1; $i <= 10; $i++) {
            $data[] = [
                'judul' => 'Judul Berita ' . $i,
                'konten' => 'Ini adalah isi konten berita nomor ' . $i . '. ' . Str::random(100),
                'tanggal_publish' => Carbon::now()->subDays(rand(0, 30))->toDateString(),
                'foto' => 'foto' . $i . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('berita')->insert($data);
    }
}
