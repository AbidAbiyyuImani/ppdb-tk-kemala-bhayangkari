<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DataUserController extends Controller
{
    public function showDataUser(Request $request) {
        $filter = $request->get('filter', 'aktif');

        $query = User::where('role', 'Orang Tua')
            ->when($filter === 'terhapus', fn($q) => $q->onlyTrashed())
            ->when($filter === 'semua', fn($q) => $q->withTrashed())
            ->when(!in_array($filter, ['terhapus', 'semua']), fn($q) => $q->whereNull('deleted_at'));

        $user = $query->latest()->paginate(10)->appends(['filter' => $filter]);
        
        return view('auth.admin.master-data.user.index', compact('user'));
    }

    public function storeDataUser(Request $request) {
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
            $user = User::create([
                'nama' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'Orang Tua',
            ]);

            $user->notifikasi()->create([
                'judul' => 'Akun Orang Tua Baru Dibuat',
                'isi_pesan' => "Selamat! Akun orang tua dengan nama \"{$user->nama}\" telah berhasil dibuat.\n\nAnda sekarang memiliki akses sebagai orang tua di sistem. Silakan login menggunakan email yang telah terdaftar dan jangan lupa untuk menjaga kerahasiaan informasi akun Anda.\n\nJika Anda mengalami kendala, segera hubungi pengelola sistem.",
            ]);

            DB::commit();

            return redirect()->route('show.data-user')->with('success', 'Data orang tua berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan, silakan coba lagi.');
        }
    }

    public function showUpdateDataUser($userSlug) {
        try {
            $user = User::whereNull('deleted_at')->where('slug', $userSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-user')->with('error', 'Data orang tua tidak ditemukan.');
        }

        return view('auth.admin.master-data.user.update', compact('user'));
    }

    public function updateDataUser(Request $request, $userSlug) {
        try {
            $user = User::whereNull('deleted_at')->where('slug', $userSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-user')->with('error', 'Data orang tua tidak ditemukan.');
        }

        $validated = $request->validate([
            'nama' => 'required|string|min:3',
            'email' => ['required', 'email', Rule::unique('user', 'email')->ignore($user->getKey(), $user->getKeyName())],
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

            $user->nama = $validated['nama'];
            $user->email = $validated['email'];

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            $user->notifikasi()->create([
                'judul' => 'Perubahan Data Akun Orang Tua',
                'isi_pesan' => 'Data akun orang tua dengan nama "' . $user->nama . '" telah berhasil diperbarui. Pastikan informasi akun selalu diperiksa dan diperbarui secara berkala demi menjaga keamanan. Jika Anda merasa perubahan ini tidak dilakukan oleh Anda, segera hubungi tim teknis.',
            ]);

            DB::commit();

            return redirect()->route('show.data-user')->with('success', 'Data orang tua berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan, silakan coba lagi.');
        }
    }

    public function restoreDataUser($userSlug) {
        try {
            $user = User::onlyTrashed()->where('slug', $userSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-user')->with('error', 'Data orang tua tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $user->restore();

            $user->notifikasi()->create([
                'judul' => 'Akun Orang Tua Telah Dipulihkan',
                'isi_pesan' => 'Akun orang tua dengan nama "' . $user->nama . '" telah berhasil dipulihkan. Anda kini kembali memiliki akses ke sistem. Pastikan untuk menggunakan akun dengan bijak dan segera perbarui informasi penting jika diperlukan.',
            ]);

            DB::commit();

            return redirect()->route('show.data-user')->with('success', 'Data orang tua berhasil dipulihkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memulihkan data.');
        }
    }

    public function destroyDataUser($userSlug) {
        try {
            $user = User::whereNull('deleted_at')->where('slug', $userSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-user')->with('error', 'Data orang tua tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $user->delete();

            $user->notifikasi()->create([
                'judul' => 'Akun Orang Tua Dihapus',
                'isi_pesan' => 'Akun orang tua dengan nama "' . $user->nama . '" telah dinonaktifkan dari sistem. Jika ini merupakan kesalahan atau Anda memerlukan akses kembali, silakan hubungi pengelola sistem untuk permintaan pemulihan.',
            ]);

            DB::commit();

            return redirect()->route('show.data-user')->with('success', 'Data orang tua berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }
}