<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VerificationController extends Controller
{
    public function showVerification() {
        $query = Pendaftaran::with(['user:id_user,nama,slug'])->whereNull('deleted_at');
        $pendaftaran = $query->latest()->paginate(10);

        return view('auth.admin.verification.index', compact('pendaftaran'));
    }

    public function showDetailVerification($pendaftaranSlug) {
        $kelasList = Kelas::pluck('nama_kelas', 'slug');

        try {
            $pendaftaran = Pendaftaran::where('slug', $pendaftaranSlug)->with(['user:id_user,slug,nama', 'dokumen:id_dokumen,pendaftaran_id,slug,nama_dokumen,path_dokumen'])->whereNull('deleted_at')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.list-pendaftaran')->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        if ($pendaftaran->status_pendaftaran === 'Diterima') return redirect()->route('show.verification')->with('error', 'Pendaftaran sudah diverifikasi.');

        return view('auth.admin.verification.detail', compact('pendaftaran', 'kelasList'));
    }

    public function storeVerification(Request $request, $pendaftaranSlug) {
        $admin = auth()->user();
    
        if ($admin->role !== 'Admin') return redirect()->route('show.verification')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    
        $validated = $request->validate([
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
    
        try {
            $pendaftaran = Pendaftaran::where('slug', $pendaftaranSlug)->whereNull('deleted_at')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.list-pendaftaran')->with('error', 'Data pendaftaran tidak ditemukan.');
        }
    
        DB::beginTransaction();
    
        try {
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
                'admin_id' => $admin->id_user,
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
    
            return redirect()->route('show.verification')->with('success', 'Verifikasi berhasil dilakukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->route('show.verification')->with('error', 'Verifikasi gagal dilakukan. Silakan coba lagi.');
        }
    }
}