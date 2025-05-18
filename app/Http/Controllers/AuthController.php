<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;

class AuthController extends Controller
{
    public function showRegister() {
        if (auth()->check()) return redirect()->route('home')->with('error', 'Logout untuk mendaftar.');
        return view('register');
    }
    
    public function showLogin() {
        if (auth()->check()) return redirect()->route('home')->with('error', 'Anda sudah masuk.');
        return view('login');
    }

    public function register(RegisterRequest $request) {
        $validated = $request->validated();
        $validated['password'] = Hash::make($request->password);
        $user = User::create($validated);

        if ($user) {
            $user->notifikasi()->create([
                'user_id' => $user->id_user,
                'judul' => 'Pendaftaran Akun Berhasil',
                'isi_pesan' => 'Selamat datang di aplikasi PPDB, Akun anda telah berhasil terdaftar.',
            ]);
            return redirect()->route('show.login')->with('success', 'Akun berhasil dibuat, masuk untuk melanjutkan.');
        }
        return redirect()->back()->with('error', 'Pendaftaran gagal, silakan coba lagi.');
    }

    public function login(LoginRequest $request) {
        $credentials = $request->validated();
        $remember = $request->boolean('remember');
        if (!auth()->attempt($credentials, $remember)) {
            return redirect()->back()->with('error', 'Email atau password salah. Silakan coba lagi.');
        }
        $request->session()->regenerate();
        return redirect()->route('home')->with('success', 'Selamat datang, ' . auth()->user()->nama . '!');
    }

    public function logout(Request $request) {
        auth()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'Anda telah keluar.');
    }
}
