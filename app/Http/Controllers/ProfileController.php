<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function showProfile() {
        $user = auth()->user()->only(['id_user', 'nama', 'email']);
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request) {
        $user = auth()->user();
        $request->validate([
            'nama' => 'required|min:3|string',
            'email' => 'required|email',
        ], [
            'required' => ':attribute tidak boleh kosong.',
            'min' => ':attribute minimal :min karakter.',
            'email' => 'Format :attribute tidak valid.',
            'string' => ':attribute harus berupa teks.',
        ], [
            'nama' => 'Nama',
            'email' => 'Email',
        ]);
        
        $user->update([
            'nama' => $request->nama,
            'email' => $request->email,
        ]);
        
        return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
    }
}
