<?php

namespace Database\Seeders;

use App\Models\Kader;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Kreait\Firebase\Factory;

class KaderSeeder extends Seeder
{
    protected $firebaseAuth;

    public function __construct()
    {
        $credentialsPath = storage_path('app/firebase_credentials.json');

        // Inisialisasi Firebase Auth
        $this->firebaseAuth = (new Factory)
            ->withServiceAccount($credentialsPath)
            ->createAuth();
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create('id_ID'); // Menggunakan locale Indonesia

        for ($i = 1; $i <= 5; $i++) {
            // Pastikan username unik, misalnya kader1, kader2, dst.
            $username = 'kader' . $i;

            // Buat data user di Laravel untuk kader
            $user = User::create([
                'username' => $username,
                'password' => Hash::make('password123'),
                'role' => 'kader', // Set role sebagai 'kader'
            ]);

            // Buat data biodata kader (sesuaikan dengan model Kader)
            Kader::create([
                'user_id' => $user->id,
                'nama'    => $faker->name,
                'nik'     => $faker->unique()->numerify(str_repeat('#', 16)),
                'ttl'     => $faker->city . ', ' . $faker->date('d-m-Y'),
                'umur'    => $faker->numberBetween(20, 60), // Usia untuk kader lebih muda
                'alamat'  => $faker->address,
                'no_hp'   => $faker->phoneNumber,
            ]);

            // // Buat pengguna Firebase untuk kader
            // $firebaseUser = $this->firebaseAuth->createUser([
            //     'email' => 'qilaynin+' . $username . '@gmail.com', // Atur email untuk pengguna
            //     'password' => 'password123',
            //     'displayName' => $faker->name,
            //     'disabled' => false,
            // ]);

            // Anda bisa menyimpan UID Firebase ke dalam tabel `users` di Laravel, jika perlu
            // $user->firebase_uid = $firebaseUser->uid;
            // $user->save();
        }
    }
}
