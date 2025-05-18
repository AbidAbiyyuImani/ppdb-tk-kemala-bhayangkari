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

class DataPendaftaranController extends Controller
{
    public function showDataPendaftaran(Request $request) {
        $filter = $request->query('filter');

        $query = Pendaftaran::with(['user:id_user,nama,slug'])
            ->when($filter === 'terhapus', fn ($q) => $q->onlyTrashed())
            ->when($filter === 'semua', fn ($q) => $q->withTrashed())
            ->when(!in_array($filter, ['terhapus', 'semua']), fn ($q) => $q->whereNull('deleted_at'));
        $pendaftaran = $query->latest()->paginate(10)->appends(['filter' => $filter]);

        return view('auth.admin.master-data.pendaftaran.index', compact('pendaftaran'));
    }
    
    public function storeDataPendaftaran(PendaftaranRequest $request) {
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

            return redirect()->route('show.data-registration')->with('success', 'Pendaftaran berhasil diajukan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan pendaftaran.');
        }
    }

    public function showUpdateDataPendaftaran($userSlug, $pendaftaranSlug) {
        try {
            $pendaftaran = Pendaftaran::with(['user:id_user,nama,slug'])->where('slug', $pendaftaranSlug)
                ->whereHas('user', function ($query) use ($userSlug) {
                    $query->where('slug', $userSlug);
                })->whereNull('deleted_at')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-registration')->with('error', 'Data pendaftaran tidak ditemukan.');
        }
        
        return view('auth.admin.master-data.pendaftaran.update', compact('pendaftaran'));
    }

    public function updateDataPendaftaran(UpdatePendaftaranRequest $request, $userSlug, $pendaftaranSlug) {
        try {
            $pendaftaran = Pendaftaran::with(['user:id_user,nama,slug'])->where('slug', $pendaftaranSlug)
                ->whereHas('user', function ($query) use ($userSlug) {
                    $query->where('slug', $userSlug);
                })->withTrashed()->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-registration')->with('error', 'Data pendaftaran tidak ditemukan.');
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

            return redirect()->route('show.data-registration')->with('success', 'Pendaftaran berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui pendaftaran.');
        }
    }

    public function restoreDataPendaftaran($userSlug, $pendaftaranSlug) {
        try {
            $pendaftaran = Pendaftaran::with(['user:id_user,nama,slug', 'dokumen' => fn ($q) => $q->onlyTrashed()])
                ->where('slug', $pendaftaranSlug)
                ->whereHas('user', function ($query) use ($userSlug) {
                    $query->where('slug', $userSlug);
                })->onlyTrashed()->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-registration')->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $pendaftaran->restore();
            $pendaftaran->forceFill([
                'status_pendaftaran' => 'Diajukan',
            ])->save();

            $pendaftaran->user->notifikasi()->create([
                'judul' => 'Pendaftaran Dipulihkan',
                'isi_pesan' => 'Data pendaftaran Anda telah dipulihkan.',
            ]);

            foreach ($pendaftaran->dokumen as $doc) {
                $namaDokumen = basename($doc->path_dokumen);
                $folder = Str::beforeLast($doc->slug, '-');
                $newPath = 'pendaftaran/' . $folder . '/' . $namaDokumen;

                if (Storage::disk('public')->exists($doc->path_dokumen)) {
                    Storage::disk('public')->move($doc->path_dokumen, $newPath);
                    $doc->update(['path_dokumen' => $newPath]);
                }

                $doc->restore();
            }
            
            DB::commit();

            return redirect()->route('show.data-registration')->with('success', 'Pendaftaran berhasil dipulihkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memulihkan pendaftaran.');
        }
    }

    public function destroyDataPendaftaran($userSlug, $pendaftaranSlug) {
        try {
            $pendaftaran = Pendaftaran::with(['user:id_user,nama,slug', 'dokumen' => fn ($q) => $q->whereNull('deleted_at')])
                ->where('slug', $pendaftaranSlug)
                ->whereHas('user', function ($query) use ($userSlug) {
                    $query->where('slug', $userSlug);
                })->whereNull('deleted_at')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-registration')->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $pendaftaran->delete();

            $pendaftaran->user->notifikasi()->create([
                'judul' => 'Pendaftaran Dihapus',
                'isi_pesan' => 'Data pendaftaran Anda telah dihapus.',
            ]);

            foreach ($pendaftaran->dokumen as $doc) {
                $namaDokumen = basename($doc->path_dokumen);
                $folder = Str::beforeLast($doc->slug, '-');
                $newPath = 'trashed/' . $folder . '/' . $namaDokumen;

                if (Storage::disk('public')->exists($doc->path_dokumen)) {
                    Storage::disk('public')->move($doc->path_dokumen, $newPath);
                    $doc->update(['path_dokumen' => $newPath]);
                }

                $doc->delete();
            }

            DB::commit();

            return redirect()->route('show.data-registration')->with('success', 'Pendaftaran berhasil dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus pendaftaran.');
        }
    }
}