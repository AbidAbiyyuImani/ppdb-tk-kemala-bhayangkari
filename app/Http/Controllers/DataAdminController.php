<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DataAdminController extends Controller
{
    public function showDataAdmin(Request $request) {
        $filter = $request->get('filter', 'aktif');

        $query = User::where('role', 'Admin')
            ->when($filter === 'terhapus', fn($q) => $q->onlyTrashed())
            ->when($filter === 'semua', fn($q) => $q->withTrashed())
            ->when(!in_array($filter, ['terhapus', 'semua']), fn($q) => $q->whereNull('deleted_at'));

        $admin = $query->latest()->paginate(10)->appends(['filter' => $filter]);

        return view('auth.admin.master-data.admin.index', compact('admin'));
    }

    public function storeDataAdmin(Request $request) {
        $validated = $request->validate([
            'nama' => 'required|string|min:3',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|string|min:8',
        ], [
            'required' => 'Kolom :attribute wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.min' => 'Nama harus memiliki minimal 3 karakter.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal terdiri dari 8 karakter.',
        ]);

        DB::beginTransaction();

        try {

            $admin = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'Admin',
            ]);

            $admin->notifikasi()->create([
                'judul' => 'Akun Admin Baru Dibuat',
                'isi_pesan' => "Selamat! Akun admin dengan nama \"{$admin->nama}\" telah berhasil dibuat.\n\nAnda sekarang memiliki akses sebagai administrator sistem. Silakan login menggunakan email yang telah terdaftar dan jangan lupa untuk menjaga kerahasiaan informasi akun Anda.\n\nJika Anda mengalami kendala, segera hubungi pengelola sistem.",
            ]);

            DB::commit();

            return redirect()->route('show.data-admin')->with('success', 'Data admin berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Terjadi kesalahan, silakan coba lagi.');
        }
    }

    public function showUpdateDataAdmin($adminSlug) {
        try {
            $admin = User::whereNull('deleted_at')->where('slug', $adminSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-admin')->with('error', 'Data admin tidak ditemukan.');
        }

        return view('auth.admin.master-data.admin.update', compact('admin'));
    }


public function updateDataAdmin(Request $request, $adminSlug) {
    try {
        $admin = User::whereNull('deleted_at')->where('slug', $adminSlug)->firstOrFail();
    } catch (ModelNotFoundException $e) {
        return redirect()->route('show.data-admin')->with('error', 'Data admin tidak ditemukan.');
    }

    $validated = $request->validate([
        'nama' => 'required|string|min:3',
        'email' => ['required', 'email', Rule::unique('user', 'email')->ignore($admin->getKey(), $admin->getKeyName())],
        'password' => 'nullable|string|min:8',
    ], [
        'required' => 'Kolom :attribute wajib diisi.',
        'nama.string' => 'Nama harus berupa teks.',
        'nama.min' => 'Nama harus memiliki minimal 3 karakter.',
        'email.email' => 'Format email tidak valid.',
        'email.unique' => 'Email ini sudah terdaftar.',
        'password.string' => 'Password harus berupa teks.',
        'password.min' => 'Password minimal terdiri dari 8 karakter.',
    ]);

    DB::beginTransaction();

    try {
        
        $admin->nama = $validated['nama'];
        $admin->email = $validated['email'];

        if (!empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        $admin->notifikasi()->create([
            'judul' => 'Perubahan Data Akun Admin',
            'isi_pesan' => 'Data akun admin dengan nama "' . $admin->nama . '" telah berhasil diperbarui. '
                . 'Pastikan informasi akun selalu diperiksa dan diperbarui secara berkala demi menjaga keamanan. '
                . 'Jika Anda merasa perubahan ini tidak dilakukan oleh Anda, segera hubungi tim teknis.',
        ]);

        DB::commit();

        return redirect()->route('show.data-admin')->with('success', 'Data admin berhasil diperbarui.');
    } catch (\Exception $e) {
        DB::rollBack();
        report($e);
        return back()->with('error', 'Terjadi kesalahan saat memperbarui data.')->withInput();
    }
}

    public function restoreDataAdmin($adminSlug) {
        try {
            $admin = User::onlyTrashed()->where('slug', $adminSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-admin')->with('error', 'Data admin tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $admin->restore();

            $admin->notifikasi()->create([
                'judul' => 'Akun Admin Telah Dipulihkan',
                'isi_pesan' => 'Akun admin dengan nama "' . $admin->nama . '" telah berhasil dipulihkan. Anda kini kembali memiliki akses ke sistem. Pastikan untuk menggunakan akun dengan bijak dan segera perbarui informasi penting jika diperlukan.',
            ]);

            DB::commit();

            return redirect()->route('show.data-admin')->with('success', 'Data admin berhasil dipulihkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Gagal memulihkan data admin');
        }
    }

    public function destroyDataAdmin($adminSlug) {
        try {
            $admin = User::whereNull('deleted_at')->where('slug', $adminSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-admin')->with('error', 'Data admin tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $admin->delete();

            $admin->notifikasi()->create([
                'judul' => 'Akun Admin Dihapus',
                'isi_pesan' => 'Akun admin dengan nama "' . $admin->nama . '" telah dinonaktifkan dari sistem. Jika ini merupakan kesalahan atau Anda memerlukan akses kembali, silakan hubungi pengelola sistem untuk permintaan pemulihan.',
            ]);

            DB::commit();

            return redirect()->route('show.data-admin')->with('success', 'Data admin berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->with('error', 'Gagal menghapus data admin');
        }
    }
}