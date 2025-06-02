<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // Menampilkan form login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6',
        ]);

        // Coba login dengan role admin
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password'], 'role' => 'admin'], $request->remember)) {
            $user = Auth::user();

            // Cek apakah email sudah diverifikasi di tabel users
            if (is_null($user->email_verified_at)) {
                Auth::logout();
                return back()->withErrors(['username' => 'Email belum diverifikasi.']);
            }

            // Cek apakah user memiliki relasi admin
            if (!$user->admin) {
                Auth::logout();
                return back()->withErrors(['username' => 'Anda bukan admin.']);
            }

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['username' => 'Username atau password salah, atau Anda bukan admin.'])->withInput($request->only('username'));
    }

    // Menampilkan form registrasi
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Proses registrasi
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|unique:users,username',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email|regex:/@gmail\.com$/i',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.regex' => 'Email harus berupa alamat Gmail yang valid (contoh: nama@gmail.com).',
            'email.unique' => 'Email sudah terdaftar.',
            'username.unique' => 'Username sudah terdaftar.',
        ]);

        // Buat user baru
        $user = User::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => 'admin',
            'email_verified_at' => null,  // pastikan belum diverifikasi
        ]);

        // Buat admin baru
        $admin = Admin::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Kirim email verifikasi
        try {
            Mail::send('emails.verify', [
                'verifyUrl' => route('verify.email', ['id' => $user->id, 'hash' => sha1($user->email)]),
                'name' => $admin->name,
            ], function ($message) use ($admin) {
                $message->to($admin->email)->subject('Verifikasi Email - Sistem Posyandu Lansia');
            });
        } catch (\Exception $e) {
            // Hapus user dan admin jika gagal kirim email
            $admin->delete();
            $user->delete();
            return back()->withErrors(['email' => 'Gagal mengirim email verifikasi. Silakan coba lagi.']);
        }

        return redirect()->route('login')->with('status', 'Registrasi berhasil! Silakan verifikasi email Anda.');
    }

    // Verifikasi email
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (sha1($user->email) !== $hash) {
            return redirect()->route('login')->withErrors(['email' => 'Link verifikasi tidak valid.']);
        }

        if (is_null($user->email_verified_at)) {
            $user->email_verified_at = now();
            $user->save();
            return redirect()->route('login')->with('status', 'Email berhasil diverifikasi! Silakan login.');
        }

        return redirect()->route('login')->with('status', 'Email sudah diverifikasi.');
    }

    // Menampilkan form lupa password
    public function showLinkRequestForm()
    {
        return view('auth.forgot_password');
    }

    // Kirim email reset password
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins,email|regex:/@gmail\.com$/i',
        ], [
            'email.regex' => 'Email harus berupa alamat Gmail yang valid (contoh: nama@gmail.com).',
            'email.exists' => 'Email tidak terdaftar di sistem.',
        ]);

        // Cari admin berdasarkan email
        $admin = Admin::where('email', $request->email)->first();

        // Cari user terkait
        $user = $admin->user;

        if (!$user || $user->role !== 'admin') {
            return back()->withErrors(['email' => 'Akun terkait bukan admin.']);
        }

        // Pastikan email sudah diverifikasi di tabel users
        if (is_null($user->email_verified_at)) {
            return back()->withErrors(['email' => 'Email belum diverifikasi.']);
        }

        // Generate password acak
        $newPassword = Str::random(12);
        $user->password = Hash::make($newPassword);
        $user->save();

        // Kirim email dengan username dan password baru
        try {
            Mail::send('emails.reset_password', [
                'username' => $user->username,
                'newPassword' => $newPassword,
            ], function ($message) use ($admin) {
                $message->to($admin->email)->subject('Reset Password - Sistem Posyandu Lansia');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim email. Silakan coba lagi nanti.']);
        }

        return redirect()->route('login')->with('status', 'Username dan password baru telah dikirim ke email Anda.');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
