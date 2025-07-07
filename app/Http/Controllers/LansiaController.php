<?php

namespace App\Http\Controllers;

use App\Models\Lansia;
use App\Models\User;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Factory;

class LansiaController extends Controller
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
     * Display a listing of the resource.
     */
    public function index()
    {
        $lansias = Lansia::all();
        return view('pages.lansia.index', compact('lansias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $desas = Desa::all();
        return view('pages.lansia.create', compact('desas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data input dengan pesan kustom
        $validated = $request->validate([
            // Data user
            'username'     => 'required|unique:users,username',
            'password'     => 'required|string|min:6',
            // Data biodata lansia
            'nama'         => 'required|string|max:255',
            'jenis_kelamin'=> 'required|in:L,P',
            'ttl'          => 'required|string|max:255',
            'umur'         => 'required|integer|min:1|max:150',
            'alamat'       => 'required|string',
            'no_hp'        => 'required|string',
            'desa_id'      => 'required|exists:desas,id',
        ], [
            // Pesan kesalahan kustom
            'username.required'    => 'Username wajib diisi.',
            'username.unique'      => 'Username sudah digunakan, silakan pilih yang lain.',
            'password.required'    => 'Password wajib diisi.',
            'password.min'         => 'Password minimal harus 6 karakter.',
            'nama.required'        => 'Nama wajib diisi.',
            'jenis_kelamin.required'         => 'Jenis Kelamin wajib diisi.',
            'ttl.required'         => 'Tempat, Tanggal Lahir wajib diisi.',
            'umur.required'        => 'Umur wajib diisi.',
            'umur.integer'         => 'Umur harus berupa angka.',
            'umur.min'             => 'Umur minimal adalah 1 tahun.',
            'umur.max'             => 'Umur maksimal adalah 150 tahun.',
            'alamat.required'      => 'Alamat wajib diisi.',
            'no_hp.required'       => 'Nomor HP wajib diisi.',
            'desa_id.required'     => 'Desa wajib dipilih.',
            'desa_id.exists'       => 'Desa tidak valid.',
        ]);


        // Gunakan transaksi untuk memastikan kedua data tersimpan dengan konsisten
        DB::beginTransaction();
        try {
            // Simpan data user
            $user = User::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role'     => "lansia"
            ]);

            // Simpan data biodata lansia
            Lansia::create([
                'user_id'      => $user->id,
                'desa_id'      => $validated['desa_id'],
                'nama'         => $validated['nama'],
                'nik'          => $validated['username'],
                'jenis_kelamin'=> $validated['jenis_kelamin'],
                'ttl'          => $validated['ttl'],
                'umur'         => $validated['umur'],
                'alamat'       => $validated['alamat'],
                'no_hp'        => $validated['no_hp'],
            ]);

            DB::commit();

            // Buat pengguna Firebase
            $this->firebaseAuth->createUser([
                'email' => 'qilaynin+' . $validated['username'] . '@gmail.com', // Atur email untuk pengguna
                'password' => $validated['password'],
                'displayName' => $validated['nama'],
                'disabled' => false,
            ]);


            return redirect()->route('lansia.index')->with('success', 'User dan biodata lansia berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $lansia = Lansia::findOrFail($id);

        return view('pages.lansia.show', compact('lansia'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $lansia = Lansia::findOrFail($id);
        $user = User::findOrFail($lansia->user_id); 
        $desas = Desa::all();
        return view('pages.lansia.edit', compact('lansia', 'user', 'desas'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'username'     => 'required|unique:users,username,' . $id, 
            'password'     => 'nullable|string|min:6', 
            'nama'         => 'required|string|max:255', 
            'jenis_kelamin'=> 'required|in:L,P',
            'ttl'          => 'required|string|max:255',
            'umur'         => 'required|integer|min:1|max:150',
            'alamat'       => 'required|string',
            'no_hp'        => 'required|string',
            'desa_id'      => 'required|exists:desas,id',
        ], [
            'username.required'    => 'Username wajib diisi.',
            'username.unique'      => 'Username sudah digunakan, silakan pilih yang lain.',
            'password.min'         => 'Password minimal harus 6 karakter.',
            'nama.required'        => 'Nama wajib diisi.', 
            'jenis_kelamin.required'         => 'Jenis Kelamin wajib diisi.',
            'ttl.required'         => 'Tempat, Tanggal Lahir wajib diisi.',
            'umur.required'        => 'Umur wajib diisi.',
            'umur.integer'         => 'Umur harus berupa angka.',
            'umur.min'             => 'Umur minimal adalah 1 tahun.',
            'umur.max'             => 'Umur maksimal adalah 150 tahun.',
            'alamat.required'      => 'Alamat wajib diisi.',
            'no_hp.required'       => 'Nomor HP wajib diisi.',
            'desa_id.required'     => 'Desa wajib dipilih.',
            'desa_id.exists'       => 'Desa tidak valid.',
        ]);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->username = $validated['username'];

            if ($validated['password']) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            $lansia = Lansia::findOrFail($id);
            $lansia->nama = $validated['nama'];
            $lansia->nik = $validated['username'];
            $lansia->jenis_kelamin = $validated['jenis_kelamin'];
            $lansia->ttl = $validated['ttl'];
            $lansia->umur = $validated['umur'];
            $lansia->alamat = $validated['alamat'];
            $lansia->no_hp = $validated['no_hp'];
            $lansia->desa_id = $validated['desa_id'];
            $lansia->save();

            DB::commit();

            // if ($validated['username'] != $user->username || (isset($validated['password']) && $validated['password'])) {
            //     $this->firebaseAuth->updateUser($user->firebase_uid, [
            //         'email' => 'qilaynin+' . $validated['username'] . '@gmail.com',
            //         'password' => $validated['password'] ?? $user->password,
            //         'displayName' => $validated['nama'],
            //         'disabled' => false,
            //     ]);
            // }

            return redirect()->route('lansia.index')->with('success', 'Data lansia berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $lansia = Lansia::findOrFail($id);

        $user = User::findOrFail($lansia->user_id);
        $user->delete();

        return redirect()->back();
    }
}
