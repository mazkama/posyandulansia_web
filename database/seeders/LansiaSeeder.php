<?php

namespace Database\Seeders;

use App\Models\Lansia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
use Kreait\Firebase\Factory;

class LansiaSeeder extends Seeder
{
    // protected $firebaseAuth;

    // public function __construct(Auth $firebaseAuth)
    // {
    //     $this->firebaseAuth = $firebaseAuth;
    // }

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

        for ($i = 1; $i <= 50; $i++) {
            // Pastikan username unik, misalnya user1, user2, dst.
            $username = 'Lansia' . $i;

            // Buat data user di Laravel
            $user = User::create([
                'username' => $username,
                'password' => Hash::make('password123'),
                'role' => 'lansia',
            ]);

            // Buat data biodata lansia
            Lansia::create([
                'user_id' => $user->id,
                'nama'    => $faker->name,
                'nik'     => $faker->unique()->numerify(str_repeat('#', 16)),
                'ttl'     => $faker->city . ', ' . $faker->date('d-m-Y'),
                'umur'    => $faker->numberBetween(60, 100),
                'alamat'  => $faker->address,
                'no_hp'   => $faker->phoneNumber,
            ]);

            // // Buat pengguna Firebase
            // $firebaseUser = $this->firebaseAuth->createUser([
            //     'email' => 'qilaynin+' . $username . '@gmail.com', // Atur email untuk pengguna
            //     'password' => 'password123',
            //     'displayName' => $faker->name,
            //     'disabled' => false,
            // ]);

            // // Anda bisa menyimpan UID Firebase ke dalam tabel `users` di Laravel, jika perlu
            // $user->firebase_uid = $firebaseUser->uid;
            // $user->save();
        }
    }
}
