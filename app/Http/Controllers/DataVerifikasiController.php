<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Verifikasi;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DataVerifikasiController extends Controller
{
    public function showDataVerifikasi(Request $request) {
        $filter = $request->query('filter', 'aktif');
        
        $query = Verifikasi::with(['pendaftaran:id_pendaftaran,nama_anak,slug,status_pendaftaran', 'admin:id_user,nama,slug'])
            ->when($filter === 'terhapus', fn($query) => $query->onlyTrashed())
            ->when($filter === 'semua', fn($query) => $query->withTrashed())
            ->when(!in_array($filter, ['terhapus', 'semua']), fn($query) => $query->whereNull('deleted_at'));
        $verifikasi = $query->latest()->paginate(10)->appends(['filter' => $filter]);

        return view('auth.admin.master-data.verifikasi.index', compact('verifikasi'));
    }

    public function showCreateDataVerifikasi() {
        $pendaftaranList = Pendaftaran::pluck('nama_anak', 'slug');
        $kelasList = Kelas::pluck('nama_kelas', 'slug');

        return view('auth.admin.master-data.verifikasi.create', compact('pendaftaranList', 'kelasList'));
    }

    public function storeDataVerifikasi(Request $request) {
        $validated = $request->validate([
            'pendaftar' => 'required|exists:pendaftaran,slug',
            'kelas' => 'sometimes|required_if:hasil_verifikasi,Diterima|exists:kelas,slug',
            'catatan' => 'required|string',
            'hasil_verifikasi' => 'required|in:Diterima,Ditolak',
        ], [
            'required' => ':attribute tidak boleh kosong.',
            'required_if' => ':attribute wajib diisi jika hasil verifikasi Diterima.',
            'exists' => ':attribute tidak valid.',
            'string' => ':attribute harus berupa teks.',
            'in' => ':attribute harus salah satu dari :values.',
        ], [
            'kelas' => 'Kelas',
            'catatan' => 'Catatan',
            'hasil_verifikasi' => 'Hasil Verifikasi',
        ]);

        $exist = Verifikasi::where('slug', $validated['pendaftar'])->whereNull('deleted_at')->first();
        if ($exist) return redirect()->route('show.data-verification')->with('error', 'Data verifikasi sudah ada untuk pendaftaran ini.');

        DB::beginTransaction();

        try {
            try {
                $pendaftaran = Pendaftaran::where('slug', $validated['pendaftar'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect()->route('show.data-verification')->with('error', 'Data pendaftaran tidak ditemukan.');
            }

            $kelas = null;
            if ($validated['hasil_verifikasi'] === 'Diterima' && isset($validated['kelas'])) {
                try {
                    $kelas = Kelas::where('slug', $validated['kelas'])->firstOrFail();
                } catch (ModelNotFoundException $e) {
                    return redirect()->route('show.verification')->with('error', 'Kelas tidak ditemukan.');
                }
            }

            $pendaftaran->update([
                'kelas_id' => $validated['hasil_verifikasi'] === 'Diterima' && $kelas ? $kelas->id_kelas : null,
                'status_pendaftaran' => $validated['hasil_verifikasi'],
            ]);

            $pendaftaran->verifikasi()->create([
                'admin_id' => auth()->user()->id_user,
                'catatan' => $validated['catatan'],
                'hasil_verifikasi' => $validated['hasil_verifikasi'],
            ]);

            $isiPesan = $validated['hasil_verifikasi'] === 'Diterima'
                ? 'Pendaftaran Anda telah diverifikasi dan diterima. Anda ditempatkan di kelas: ' . ($kelas ? $kelas->nama_kelas : '-') . '. Silakan lakukan daftar ulang ke sekolah.'
                : 'Pendaftaran Anda ditolak. Silakan periksa kembali data atau dokumen yang Anda kirimkan.';
    
            $pendaftaran->user->notifikasi()->create([
                'judul' => 'Verifikasi ' . $validated['hasil_verifikasi'],
                'isi_pesan' => $isiPesan,
            ]);
            
            DB::commit();

            return redirect()->route('show.data-verification')->with('success', 'Data verifikasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('show.data-verification')->with('error', 'Data verifikasi gagal ditambahkan');
        }
    }

    public function showUpdateDataVerifikasi($pendaftaranSlug, $verifikasiSlug) {
        try {
            $pendaftaran = Pendaftaran::whereNull('deleted_at')->where('slug', $pendaftaranSlug)->firstOrFail();
            $verifikasi = $pendaftaran->verifikasi()->whereNull('deleted_at')->where('slug', $verifikasiSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-verification')->with('error', 'Data verifikasi tidak ditemukan.');
        }
        $kelasList = Kelas::pluck('nama_kelas', 'slug');
        $pendaftaranList = Pendaftaran::pluck('nama_anak', 'slug');

        return view('auth.admin.master-data.verifikasi.update', compact('pendaftaran', 'verifikasi', 'pendaftaranList', 'kelasList'));
    }

    public function updateDataVerifikasi(Request $request, $pendaftaranSlug, $verifikasiSlug) {
        $validated = $request->validate([
            'pendaftar' => 'required|exists:pendaftaran,slug',
            'kelas' => 'sometimes|required_if:hasil_verifikasi,Diterima|exists:kelas,slug',
            'catatan' => 'required|string',
            'hasil_verifikasi' => 'required|in:Diterima,Ditolak',
        ], [
            'required' => ':attribute tidak boleh kosong.',
            'required_if' => ':attribute wajib diisi jika hasil verifikasi Diterima.',
            'exists' => ':attribute tidak valid.',
            'string' => ':attribute harus berupa teks.',
            'in' => ':attribute harus salah satu dari :values.',
        ], [
            'kelas' => 'Kelas',
            'catatan' => 'Catatan',
            'hasil_verifikasi' => 'Hasil Verifikasi',
        ]);
    
        DB::beginTransaction();
    
        try {
            try {
                $pendaftaran = Pendaftaran::whereNull('deleted_at')->where('slug', $pendaftaranSlug)->firstOrFail();
                $verifikasi = $pendaftaran->verifikasi()->whereNull('deleted_at')->where('slug', $verifikasiSlug)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect()->route('show.data-verification')->with('error', 'Data verifikasi tidak ditemukan.');
            }
    
            $kelas = null;
            if ($validated['hasil_verifikasi'] === 'Diterima' && isset($validated['kelas'])) {
                $kelas = Kelas::where('slug', $validated['kelas'])->firstOrFail();
            }

            $pendaftaran->update([
                'kelas_id' => $validated['hasil_verifikasi'] === 'Diterima' && $kelas ? $kelas->id_kelas : null,
                'status_pendaftaran' => $validated['hasil_verifikasi'],
            ]);

            $verifikasi->update([
                'admin_id' => auth()->user()->id_user,
                'catatan' => $validated['catatan'],
                'hasil_verifikasi' => $validated['hasil_verifikasi'],
            ]);
            
            $isiPesan = $validated['hasil_verifikasi'] === 'Diterima'
                ? 'Pendaftaran Anda telah diverifikasi dan diterima. Anda ditempatkan di kelas: ' . ($kelas ? $kelas->nama_kelas : '-') . '. Silakan lakukan daftar ulang ke sekolah.'
                : 'Pendaftaran Anda ditolak. Silakan periksa kembali data atau dokumen yang Anda kirimkan.';
    
            $pendaftaran->user->notifikasi()->create([
                'judul' => 'Verifikasi ' . $validated['hasil_verifikasi'],
                'isi_pesan' => $isiPesan,
            ]);
    
            DB::commit();
    
            return redirect()->route('show.data-verification')->with('success', 'Data verifikasi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Data verifikasi gagal diperbarui.');
        }
    }    

    public function restoreDataVerifikasi($pendaftaranSlug, $verifikasiSlug) {
        try {
            $pendaftaran = Pendaftaran::whereNull('deleted_at')->where('slug', $pendaftaranSlug)->firstOrFail();
            $verifikasi = $pendaftaran->verifikasi()->onlyTrashed()->where('slug', $verifikasiSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-verification')->with('error', 'Data verifikasi tidak ditemukan.');
        }

        $exist = $pendaftaran->verifikasi()->whereNull('deleted_at')->first();
        if ($exist) return redirect()->back()->with('error', 'Data verifikasi sudah ada untuk pendaftaran ini.');

        try {
            $verifikasi->restore();
            $pendaftaran->update([
                'kelas_id' => null,
                'status_pendaftaran' => $verifikasi->hasil_verifikasi,
            ]);

            return redirect()->route('show.data-verification')->with('success', 'Data verifikasi berhasil dipulihkan.');
        } catch (\Exception $e) {
            return redirect()->route('show.data-verification')->with('error', 'Data verifikasi gagal dipulihkan');
        }
    }

    public function destroyDataVerifikasi($pendaftaranSlug, $verifikasiSlug) {
        try {
            $pendaftaran = Pendaftaran::whereNull('deleted_at')->where('slug', $pendaftaranSlug)->firstOrFail();
            $verifikasi = $pendaftaran->verifikasi()->where('slug', $verifikasiSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-verification')->with('error', 'Data verifikasi tidak ditemukan.');
        }

        try {
            $verifikasi->delete();
            $pendaftaran->update([
                'kelas_id' => null,
                'status_pendaftaran' => 'Diajukan',
            ]);

            return redirect()->route('show.data-verification')->with('success', 'Data verifikasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('show.data-verification')->with('error', 'Data verifikasi gagal dihapus');
        }
    }
}