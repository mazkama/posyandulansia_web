<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CekKesehatanSeeder extends Seeder
{
    public function run()
    {
        $lansiaIds = range(1, 20);
        $jadwals = DB::table('jadwal')->select('id', 'tanggal')->get();
        $data = [];

        foreach ($lansiaIds as $lansiaId) {
            foreach ($jadwals as $jadwal) {
                $data[] = [
                    'lansia_id' => $lansiaId,
                    'jadwal_id' => $jadwal->id,
                    'tanggal' => $jadwal->tanggal, // gunakan tanggal dari jadwal
                    'berat_badan' => rand(4000, 8000) / 100, // 40.00 - 80.00 kg
                    'tekanan_darah_sistolik' => rand(90, 140),
                    'tekanan_darah_diastolik' => rand(60, 90),
                    'gula_darah' => rand(700, 2000) / 100, // 7.00 - 20.00
                    'kolestrol' => rand(1500, 2500) / 100, // 15.00 - 25.00
                    'asam_urat' => rand(30, 80) / 10, // 3.0 - 8.0
                    'diagnosa' => json_encode([]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('cek_kesehatan')->insert($data);
    }
}
