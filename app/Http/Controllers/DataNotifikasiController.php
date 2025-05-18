<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataNotifikasiController extends Controller
{
    public function showDataNotifikasi(Request $request) {
        $filter = $request->query('filter');

        $query = Notifikasi::with(['user:id_user,nama,slug'])
            ->when($filter === 'terhapus', fn($q) => $q->onlyTrashed())
            ->when($filter === 'semua', fn($q) => $q->withTrashed())
            ->when(!in_array($filter, ['terhapus', 'semua']), fn($q) => $q->whereNull('deleted_at'));
        $notifikasi = $query->latest()->paginate(10)->appends(['filter' => $filter]);

        return view('auth.admin.master-data.notifikasi.index', compact('notifikasi'));
    }
    
    public function showCreateDataNotifikasi() {
        $users = User::select('id_user', 'nama')->pluck('nama', 'id_user');

        return view('auth.admin.master-data.notifikasi.create', compact('users'));
    }

    public function storeDataNotifikasi(Request $request) {
        $validated = $request->validate([
            'penerima' => 'required|exists:user,id_user',
            'judul' => 'required|string',
            'pesan' => 'required|string',
        ], [
            'penerima.required' => 'Penerima tidak boleh kosong.',
            'penerima.exists' => 'Penerima yang dipilih tidak valid.',
            'judul.required' => 'Judul tidak boleh kosong.',
            'judul.string' => 'Judul harus berupa teks.',
            'pesan.required' => 'Pesan tidak boleh kosong.',
            'pesan.string' => 'Pesan harus berupa teks.',
        ]);

        DB::beginTransaction();

        try {
            Notifikasi::create([
                'user_id' => $validated['penerima'],
                'judul' => $validated['judul'],
                'isi_pesan' => $validated['pesan'],
            ]);

            DB::commit();

            return redirect()->route('show.data-notification')->with('success', 'Notifikasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('show.data-notification')->with('error', 'Terjadi kesalahan saat menambah notifikasi.');
        }
    }

    public function showUpdateDataNotifikasi($userSlug, $notifikasiSlug) {
        $user = User::where('slug', $userSlug)->firstOrFail();

        $notifikasi = Notifikasi::where('slug', $notifikasiSlug)->where('user_id', $user->id_user)->firstOrFail();

        $users = User::select('id_user', 'nama')->pluck('nama', 'id_user');

        return view('auth.admin.master-data.notifikasi.update', compact('notifikasi', 'users'));
    }

    public function updateDataNotifikasi(Request $request, $userSlug, $notifikasiSlug) {
        $validated = $request->validate([
            'penerima' => 'required|exists:user,id_user',
            'judul'    => 'required|string',
            'pesan'    => 'required|string',
        ], [
            'penerima.required' => 'Penerima wajib dipilih.',
            'penerima.exists'   => 'Penerima yang dipilih tidak valid.',
            'judul.required'    => 'Judul wajib diisi.',
            'judul.string'      => 'Judul harus berupa teks.',
            'pesan.required'    => 'Pesan wajib diisi.',
            'pesan.string'      => 'Pesan harus berupa teks.',
        ]);

        DB::beginTransaction();
        
        try {

            $user = User::where('slug', $userSlug)->firstOrFail();

            $notifikasi = Notifikasi::where('slug', $notifikasiSlug)->where('user_id', $user->id_user)->firstOrFail();

            $notifikasi->update([
                'id_user'   => $validated['penerima'],
                'judul'     => $validated['judul'],
                'isi_pesan' => $validated['pesan'],
            ]);

            DB::commit();

            return redirect()->route('show.data-notification')->with('success', 'Notifikasi berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Terjadi kesalahan saat memperbarui notifikasi.')->withInput();
        }
    }


    public function restoreDataNotifikasi($userSlug, $notifikasiSlug) {
        DB::beginTransaction();

        try {

            $user = User::where('slug', $userSlug)->firstOrFail();

            $notifikasi = Notifikasi::onlyTrashed()->where('slug', $notifikasiSlug)->where('user_id', $user->id_user)->firstOrFail();

            $notifikasi->restore();

            DB::commit();

            return redirect()->route('show.data-notification')->with('success', 'Notifikasi berhasil dipulihkan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Terjadi kesalahan saat memulihkan notifikasi.');
        }
    }


    public function destroyDataNotifikasi($userSlug, $notifikasiSlug) {
        DB::beginTransaction();

        try {

            $user = User::where('slug', $userSlug)->firstOrFail();

            $notifikasi = Notifikasi::where('slug', $notifikasiSlug)->where('user_id', $user->id_user)->firstOrFail();

            $notifikasi->delete();

            DB::commit();

            return redirect()->route('show.data-notification')->with('success', 'Notifikasi berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->withErrors('Terjadi kesalahan saat menghapus notifikasi.');
        }
    }
}