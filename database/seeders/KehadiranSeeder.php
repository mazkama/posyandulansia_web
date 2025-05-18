<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KehadiranSeeder extends Seeder
{
    public function run()
    {
        // Asumsi lansia_id dari 1 sampai 20
        $lansiaIds = range(1, 20);

        // Ambil semua jadwal dari tabel jadwal
        $jadwals = DB::table('jadwal')->pluck('id')->toArray();

        $data = [];

        foreach ($lansiaIds as $lansiaId) {
            foreach ($jadwals as $jadwalId) {
                $data[] = [
                    'lansia_id' => $lansiaId,
                    'jadwal_id' => $jadwalId,
                    'status' => 'hadir',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Masukkan data ke tabel kehadiran
        DB::table('kehadiran')->insert($data);
    }
}
