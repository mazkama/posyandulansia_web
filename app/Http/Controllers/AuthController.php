<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6'
        ]);

        // Coba login
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard'); // Sesuaikan dengan halaman tujuan setelah login
        }

        return back()->withErrors(['username' => 'Invalid credentials'])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
