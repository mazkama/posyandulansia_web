<?php

namespace App\Http\Controllers;

use App\Models\Kader;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Kreait\Firebase\Factory;

class KaderController extends Controller
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

    public function index()
    {
        $kaders = Kader::with('user')->get();
        return view('pages.kader.index', compact('kaders'));
    }

    public function create()
    {
        return view('pages.kader.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'username'     => 'required|unique:users,username',
            'password'     => 'required|string|min:6',
            'nama'         => 'required|string|max:255',
            'nik'          => 'required|digits:16|unique:kader,nik',
            'jenis_kelamin'=> 'required|in:L,P',
            'ttl'          => 'required|string|max:255',
            'umur'         => 'required|integer|min:1|max:150',
            'alamat'       => 'required|string',
            'no_hp'        => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role'     => "kader"
            ]);

            Kader::create([
                'user_id'      => $user->id,
                'nama'         => $validated['nama'],
                'nik'          => $validated['nik'],
                'jenis_kelamin'=> $validated['jenis_kelamin'],
                'ttl'          => $validated['ttl'],
                'umur'         => $validated['umur'],
                'alamat'       => $validated['alamat'],
                'no_hp'        => $validated['no_hp'],
            ]);

            DB::commit();

            $this->firebaseAuth->createUser([
                'email'       => 'qilaynin+' . $validated['username'] . '@gmail.com',
                'password'    => $validated['password'],
                'displayName' => $validated['nama'],
                'disabled'    => false,
            ]);

            return redirect()->route('kader.index')->with('success', 'User dan biodata kader berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        $kader = Kader::with('user')->findOrFail($id);
        return view('pages.kader.show', compact('kader'));
    }

    public function edit(string $id)
    {
        $kader = Kader::findOrFail($id);
        $user = User::findOrFail($kader->user_id);
        return view('pages.kader.edit', compact('kader', 'user'));
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'username'     => 'required|unique:users,username,' . $id,
            'password'     => 'nullable|string|min:6',
            'nama'         => 'required|string|max:255',
            'nik'          => 'required|digits:16|unique:kader,nik,' . $id,
            'jenis_kelamin'=> 'required|in:L,P',
            'ttl'          => 'required|string|max:255',
            'umur'         => 'required|integer|min:1|max:150',
            'alamat'       => 'required|string',
            'no_hp'        => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $kader = Kader::findOrFail($id);
            $user = User::findOrFail($kader->user_id);

            $user->username = $validated['username'];
            if ($validated['password']) {
                $user->password = Hash::make($validated['password']);
            }
            $user->save();

            $kader->update([
                'nama'         => $validated['nama'],
                'nik'          => $validated['nik'],
                'jenis_kelamin'=> $validated['jenis_kelamin'],
                'ttl'          => $validated['ttl'],
                'umur'         => $validated['umur'],
                'alamat'       => $validated['alamat'],
                'no_hp'        => $validated['no_hp'],
            ]);

            DB::commit();

            // update Firebase jika diperlukan
            if ($validated['username'] != $user->username || (isset($validated['password']) && $validated['password'])) {
                $this->firebaseAuth->updateUser($user->firebase_uid, [
                    'email'       => 'qilaynin+' . $validated['username'] . '@gmail.com',
                    'password'    => $validated['password'] ?? $user->password,
                    'displayName' => $validated['nama'],
                    'disabled'    => false,
                ]);
            }

            return redirect()->route('kader.index')->with('success', 'Data kader berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors('Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $kader = Kader::findOrFail($id);
        $user = User::findOrFail($kader->user_id);
        $user->delete();
        return redirect()->back()->with('success', 'Data kader berhasil dihapus.');
    }
}
