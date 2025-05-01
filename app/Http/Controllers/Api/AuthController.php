<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // Cari pengguna berdasarkan username
        $user = User::where('username', $request->username)->first();

        // Cek apakah user ditemukan dan password cocok
        if ($user && Hash::check($request->password, $user->password)) {
            // Buat token untuk pengguna
            $token = $user->createToken('MyApp')->plainTextToken;
            
            $role = $user->role;
            
            if ($role == 'kader'){
                $dataUser = User::with('kader')->where('username', $request->username)->first();
            }elseif ($role == 'lansia') {
                $dataUser = User::with('lansia')->where('username', $request->username)->first();
            }

            // Kembalikan response dengan token dan data pengguna
            return response()->json([
                'access_token' => $token,
                'user' => $dataUser
            ], 200);
        }

        // Jika login gagal
        return response()->json(['message' => 'Unauthorized'], 401);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user(); // Ambil user yang sedang login

        // Cek apakah password lama sesuai
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'Password lama salah'], 401);
        }

        // Update password dengan bcrypt
        $user->password = bcrypt($request->new_password);
        $user->save(); // Menyimpan perubahan password

        // Hapus semua token aktif (logout semua sesi)
        $user->tokens->each(function ($token) {
            $token->delete();
        });

        return response()->json(['message' => 'Password berhasil diperbarui, silakan login ulang'], 200);
    }


    public function checkToken(Request $request)
    {
        return response()->json([
            'message' => 'Token valid',
            'user' => $request->user()
        ], 200);
    }
}
