<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class JadwalSeeder extends Seeder
{
    public function run()
    {
        $jadwals = [
            [
                'tanggal' => Carbon::parse('2024-01-01')->toDateString(),
                'waktu' => '08:00:00',
                'lokasi' => 'Posyandu RW 01',
                'status' => 'selesai',
            ],
            [
                'tanggal' => Carbon::parse('2024-02-03')->toDateString(),
                'waktu' => '08:30:00',
                'lokasi' => 'Posyandu RW 02',
                'status' => 'selesai',
            ],
            [
                'tanggal' => Carbon::parse('2024-03-05')->toDateString(),
                'waktu' => '09:00:00',
                'lokasi' => 'Posyandu RW 03',
                'status' => 'selesai',
            ],
            [
                'tanggal' => Carbon::parse('2024-04-07')->toDateString(),
                'waktu' => '07:30:00',
                'lokasi' => 'Posyandu RW 04',
                'status' => 'selesai',
            ],
            [
                'tanggal' => Carbon::parse('2024-05-09')->toDateString(),
                'waktu' => '08:15:00',
                'lokasi' => 'Posyandu RW 05',
                'status' => 'selesai',
            ],
            [
                'tanggal' => Carbon::parse('2024-06-11')->toDateString(),
                'waktu' => '08:00:00',
                'lokasi' => 'Posyandu RW 06',
                'status' => 'selesai',
            ],
            [
                'tanggal' => Carbon::parse('2024-07-13')->toDateString(),
                'waktu' => '09:30:00',
                'lokasi' => 'Posyandu RW 07',
                'status' => 'selesai',
            ],
            [
                'tanggal' => Carbon::parse('2024-08-15')->toDateString(),
                'waktu' => '08:45:00',
                'lokasi' => 'Posyandu RW 08',
                'status' => 'selesai',
            ],
            [
                'tanggal' => Carbon::parse('2024-09-17')->toDateString(),
                'waktu' => '07:45:00',
                'lokasi' => 'Posyandu RW 09',
                'status' => 'selesai',
            ],
            [
                'tanggal' => Carbon::parse('2024-10-19')->toDateString(),
                'waktu' => '08:30:00',
                'lokasi' => 'Posyandu RW 10',
                'status' => 'selesai',
            ],
        ];

        foreach ($jadwals as $jadwal) {
            DB::table('jadwal')->insert([
                'tanggal' => $jadwal['tanggal'],
                'waktu' => $jadwal['waktu'],
                'lokasi' => $jadwal['lokasi'],
                'status' => $jadwal['status'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
