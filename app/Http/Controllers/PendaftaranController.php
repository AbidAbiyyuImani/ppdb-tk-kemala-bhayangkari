<?php

namespace App\Http\Controllers;

use App\Models\Pendaftaran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PendaftaranRequest;
use App\Http\Requests\UpdatePendaftaranRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PendaftaranController extends Controller
{
    public function storePendaftaran(PendaftaranRequest $request) {
        $user = auth()->user();
        $validated = $request->validated();

        DB::beginTransaction();
        
        try {
            $pendaftaran = Pendaftaran::create([
                'user_id' => $user->id_user,

                'nama_anak' => $validated['nama-anak'],
                'nama_panggilan' => $validated['nama-panggilan'],
                'jenis_kelamin' => $validated['jenis-kelamin'],
                'tempat_lahir' => $validated['tempat-lahir'],
                'tanggal_lahir' => $validated['tanggal-lahir'],
                'agama' => $validated['agama'],
                'anak_ke' => $validated['anak-ke'],
                'status_anak' => $validated['status-anak'],

                'nama_ayah' => $validated['nama-ayah'] ?? null,
                'pekerjaan_ayah' => $validated['pekerjaan-ayah'] ?? null,
                'nama_ibu' => $validated['nama-ibu'] ?? null,
                'pekerjaan_ibu' => $validated['pekerjaan-ibu'] ?? null,
                'nama_wali' => $validated['nama-wali'] ?? null,
                'pekerjaan_wali' => $validated['pekerjaan-wali'] ?? null,

                'alamat' => $validated['alamat'],
                'kelurahan' => $validated['kelurahan'],
                'no_telp' => $validated['no-telp'],
                'email' => $validated['email'] ?? null,
                'no_wa' => $validated['no-wa'] ?? $validated['no-telp'],

                'imunisasi_vaksin_yang_pernah_diterima' => $validated['imunisasi-vaksin-yang-pernah-diterima'],
                'penyakit_berat_yang_diderita' => $validated['penyakit-berat-yang-diderita'],
                'jarak_dari_rumah' => $validated['jarak-dari-rumah'],
                'golongan_darah' => $validated['golongan-darah'],
            ]);
            
            foreach (Pendaftaran::DOKUMEN_LIST as $doc) {
                if ($request->hasFile($doc)) {
                    $file = $request->file($doc);
                    $path = $file->store('pendaftaran/' . $doc, 'public');

                    $pendaftaran->dokumen()->create([
                        'nama_dokumen' => Str::title(Str::replace('-', ' ', $doc)),
                        'path_dokumen' => $path,
                    ]);
                }
            }

            $pendaftaran->user->notifikasi()->create([
                'judul' => 'Pendaftaran ' . $validated['nama-anak'] . ' Berhasil',
                'isi_pesan' => 'Pendaftaran atas nama ' . $validated['nama-anak'] . ' telah berhasil diajukan. Silakan menunggu proses verifikasi.',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pendaftaran berhasil diajukan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pendaftaran.');
        }
    }

    public function showListPendaftaran() {
        $user = auth()->user();
        $pendaftaran = Pendaftaran::with(['user:id_user,slug'])->select([
            'user_id',
            'slug',
            'nama_anak',
            'tempat_lahir',
            'tanggal_lahir',
            'nama_ayah',
            'nama_ibu',
            'nama_wali',
            'status_pendaftaran',
        ])->where('user_id', $user->id_user)->get();    
        
        return view('auth.user.list-pendaftaran', compact('pendaftaran'));
    }
    
    public function showDetailPendaftaran($userSlug, $pendaftaranSlug) {
        $loggedInUser = auth()->user();
        if ($loggedInUser->slug != $userSlug) return redirect()->route('show.list-pendaftaran')->with('error', 'Anda tidak memiliki akses ke halaman ini.');

        try {
            $pendaftaran = Pendaftaran::where('slug', $pendaftaranSlug)
                ->whereHas('user', function ($query) use ($userSlug) {
                    $query->where('slug', $userSlug);
                })->with(['user:id_user,slug,nama', 'dokumen:id_dokumen,pendaftaran_id,slug,nama_dokumen,path_dokumen', 'kelas:id_kelas,nama_kelas'])->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.list-pendaftaran')->with('error', 'Data pendaftaran tidak ditemukan.');
        }
        
        return view('auth.user.detail-pendaftaran', compact('pendaftaran'));
    }

    public function updateDetailPendaftaran(UpdatePendaftaranRequest $request, $userSlug, $pendaftaranSlug) {
        $loggedInUser = auth()->user();
        if ($loggedInUser->slug != $userSlug) return redirect()->route('show.list-pendaftaran')->with('error', 'Anda tidak memiliki akses ke halaman ini.');

        try {
            $pendaftaran = Pendaftaran::where('slug', $pendaftaranSlug)
                ->whereHas('user', function ($query) use ($userSlug) {
                    $query->where('slug', $userSlug);
                })->whereNull('deleted_at')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.list-pendaftaran')->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $inputStatus = $validated['status-penanggung-jawab'];
            $currentStatus = $pendaftaran->nama_wali ? 'wali' : 'orang-tua';
            if ($inputStatus !== $currentStatus) {
                if ($validated['status-penanggung-jawab'] === 'wali') {
                    $validated['nama-ayah'] = null;
                    $validated['pekerjaan-ayah'] = null;
                    $validated['nama-ibu'] = null;
                    $validated['pekerjaan-ibu'] = null;
                } else {
                    $validated['nama-wali'] = null;
                    $validated['pekerjaan-wali'] = null;
                }
            }

            $pendaftaran->update([
                'nama_anak' => $validated['nama-anak'],
                'nama_panggilan' => $validated['nama-panggilan'],
                'jenis_kelamin' => $validated['jenis-kelamin'],
                'tempat_lahir' => $validated['tempat-lahir'],
                'tanggal_lahir' => $validated['tanggal-lahir'],
                'agama' => $validated['agama'],
                'anak_ke' => $validated['anak-ke'],
                'status_anak' => $validated['status-anak'],

                'nama_ayah' => $validated['nama-ayah'] ?? null,
                'pekerjaan_ayah' => $validated['pekerjaan-ayah'] ?? null,
                'nama_ibu' => $validated['nama-ibu'] ?? null,
                'pekerjaan_ibu' => $validated['pekerjaan-ibu'] ?? null,
                'nama_wali' => $validated['nama-wali'] ?? null,
                'pekerjaan_wali' => $validated['pekerjaan-wali'] ?? null,

                'alamat' => $validated['alamat'],
                'kelurahan' => $validated['kelurahan'],
                'no_telp' => $validated['no-telp'],
                'email' => $validated['email'] ?? null,
                'no_wa' => $validated['no-wa'] ?? $validated['no-telp'],

                'imunisasi_vaksin_yang_pernah_diterima' => $validated['imunisasi-vaksin-yang-pernah-diterima'],
                'penyakit_berat_yang_diderita' => $validated['penyakit-berat-yang-diderita'],
                'jarak_dari_rumah' => $validated['jarak-dari-rumah'],
                'golongan_darah' => $validated['golongan-darah'],

                'status_pendaftaran' => 'Diajukan',
            ]);
            
            foreach (Pendaftaran::DOKUMEN_LIST as $doc) {
                if ($request->hasFile($doc)) {
                    $file = $request->file($doc);
                    $path = $file->store('pendaftaran/' . $doc, 'public');
                    $docName = Str::title(Str::replace('-', ' ', $doc));
                    $existingDoc = $pendaftaran->dokumen()->where('nama_dokumen', $docName)->first();

                    if ($existingDoc) {
                        if (Storage::disk('public')->exists($existingDoc->path_dokumen)) {
                            Storage::disk('public')->delete($existingDoc->path_dokumen);
                            $existingDoc->update(['path_dokumen' => $path]);
                        }
                    } else {
                        $pendaftaran->dokumen()->create([
                            'nama_dokumen' => $docName,
                            'path_dokumen' => $path,
                        ]);
                    }
                }
            }

            $pendaftaran->user->notifikasi()->create([
                'judul' => 'Pendaftaran ' . $validated['nama-anak'] . ' Berhasil Diperbarui',
                'isi_pesan' => 'Pendaftaran atas nama ' . $validated['nama-anak'] . ' telah berhasil diperbarui. Silakan menunggu proses verifikasi.',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Pendaftaran berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui pendaftaran.');
        }
    }
}