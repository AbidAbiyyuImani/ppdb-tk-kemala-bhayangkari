<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Pendaftaran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DataDokumenController extends Controller
{
    public function showDataDokumen(Request $request) {
        $filter = $request->query('filter', 'aktif');
        
        $query = Dokumen::with(['pendaftaran:id_pendaftaran,nama_anak,slug'])
            ->when($filter === 'terhapus', fn($q) => $q->onlyTrashed())
            ->when($filter === 'semua', fn($q) => $q->withTrashed())
            ->when(!in_array($filter, ['terhapus', 'semua']), fn($q) => $q->whereNull('deleted_at'));
        $dokumen = $query->latest()->paginate(10)->appends(['filter' => $filter]);
        
        return view('auth.admin.master-data.dokumen.index', compact('dokumen'));
    }
    
    public function showCreateDataDokumen() {
        $pendaftaranList = Pendaftaran::pluck('nama_anak', 'slug');

        $typeList = [
            'akta-kelahiran' => 'Akta Kelahiran',
            'kartu-keluarga' => 'Kartu Keluarga',
            'ktp-orang-tua' => 'KTP Orang Tua',
            'pas-foto-peserta-didik' => 'PAS Foto Peserta Didik',
        ];

        return view('auth.admin.master-data.dokumen.create', compact('pendaftaranList', 'typeList'));
    }

    public function storeDataDokumen(Request $request) {
        $validated = $request->validate([
            'nama_pendaftar' => 'required|exists:pendaftaran,slug',
            'nama_dokumen' => 'required|string|in:akta-kelahiran,kartu-keluarga,ktp-orang-tua,pas-foto-peserta-didik',
            'dokumen' => 'required|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'nama_pendaftar.exists' => 'Nama pendaftar tidak ditemukan.',
            'nama_dokumen.in' => 'Nama dokumen tidak valid.',
            'dokumen.required' => 'File dokumen wajib diunggah.',
            'dokumen.file' => 'File dokumen tidak valid.',
            'dokumen.mimes' => 'Jenis file tidak didukung. Hanya jpg, jpeg, png, pdf, dan docx.',
            'dokumen.max' => 'Ukuran file maksimal 2MB.',
        ], [
            'nama_pendaftar' => 'Nama Pendaftar',
            'nama_dokumen' => 'Nama Dokumen',
            'dokumen' => 'File Dokumen',
        ]);

        DB::beginTransaction();

        try {
            try {
                $pendaftaran = Pendaftaran::where('slug', $validated['nama_pendaftar'])->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect()->back()->withErrors(['nama_pendaftar' => 'Pendaftaran tidak ditemukan.'])->withInput();
            }

            $existing = $pendaftaran->dokumen()->whereRaw("LOWER(nama_dokumen) = ?", [Str::slug($validated['nama_dokumen'], ' ')])->whereNull('deleted_at')->exists();
            if ($existing) return redirect()->back()->withErrors(['nama_dokumen' => 'Dokumen dengan nama tersebut sudah ada untuk pendaftar ini.'])->withInput();

            $path = $request->file('dokumen')->store('pendaftaran/' . $validated['nama_dokumen'], 'public');

            $pendaftaran->dokumen()->create([
                'nama_dokumen' => Str::title(str_replace('-', ' ', $validated['nama_dokumen'])),
                'path_dokumen' => $path,
            ]);

            DB::commit();

            return redirect()->route('show.data-document')->with('success', 'Dokumen berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            return redirect()->back()->withErrors(['error' => 'Gagal menambahkan dokumen.'])->withInput();
        }
    }

    public function showUpdateDataDokumen($pendaftaranSlug, $dokumenSlug) {
        try {
            $dokumen = Dokumen::withTrashed()->where('slug', $dokumenSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-document')->with('error', 'Dokumen tidak ditemukan.');
        }

        if ($pendaftaranSlug === 'default') return view('auth.admin.master-data.dokumen.update', [
            'dokumen' => $dokumen,
            'pendaftaran' => null,
            'typeList' => [],
            'pendaftaranList' => []
        ]);

        try {
            $pendaftaran = Pendaftaran::where('slug', $pendaftaranSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-document')->with('error', 'Pendaftaran tidak ditemukan.');
        }

        $typeList = [
            'akta-kelahiran' => 'Akta Kelahiran',
            'kartu-keluarga' => 'Kartu Keluarga',
            'ktp-orang-tua' => 'KTP Orang Tua',
            'pas-foto-peserta-didik' => 'PAS Foto Peserta Didik',
        ];
        
        $pendaftaranList = Pendaftaran::pluck('nama_anak', 'slug');
        
        $dokumen->nama_dokumen = Str::beforeLast($dokumen->slug, '-');

        return view('auth.admin.master-data.dokumen.update', compact(
            'dokumen', 'pendaftaran', 'typeList', 'pendaftaranList'
        ));
    }

    public function updateDataDokumen(Request $request, $pendaftaranSlug, $dokumenSlug) {
        if ($pendaftaranSlug === 'default') {
            try {
                $dokumen = Dokumen::where('slug', $dokumenSlug)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect()->route('show.data-document')->with('error', 'Dokumen tidak ditemukan.');
            }

            $namaDokumen = $dokumen->nama_dokumen;
            $isLogo = $namaDokumen === 'Logo Sekolah';

            $validated = $request->validate([
                'dokumen' => [
                    'nullable',
                    'file',
                    $isLogo ? 'mimes:png' : 'mimes:pdf',
                    'max:2048'
                ],
            ], [
                'dokumen.file' => 'File dokumen tidak valid.',
                'dokumen.mimes' => $isLogo ? 'Logo Sekolah hanya boleh berupa file png.' : 'Jenis file hanya boleh pdf.',
                'dokumen.max' => 'Ukuran file maksimal 2MB.',
            ], [
                'dokumen' => 'File Dokumen',
            ]);

            DB::beginTransaction();

            try {
                if ($request->hasFile('dokumen')) {
                    if (Storage::disk('public')->exists($dokumen->path_dokumen)) {
                        Storage::disk('public')->delete($dokumen->path_dokumen);
                    }

                    switch ($namaDokumen) {
                        case 'Logo Sekolah':
                            $path = $request->file('dokumen')->storeAs('', 'logo.png', 'public');
                            break;
                        case 'Formulir Pendaftaran':
                            $path = $request->file('dokumen')->storeAs('', 'formulir_pendaftaran_pg.pdf', 'public');
                            break;
                        case 'Detail Pendaftaran':
                            $path = $request->file('dokumen')->storeAs('', 'detail_pendaftaran_pg.pdf', 'public');
                            break;
                        default:
                            $path = $request->file('dokumen')->storeAs('', Str::slug($dokumen->nama_dokumen), 'public');
                            break;
                    }

                    $dokumen->path_dokumen = $path;
                    $dokumen->save();

                    DB::commit();

                    return redirect()->route('show.data-document')->with('success', 'Dokumen berhasil diperbarui.');
                } else {
                    return redirect()->back()->withErrors(['dokumen' => 'Dokumen tidak ditemukan.'])->withInput();
                }
            } catch (\Throwable $e) {
                DB::rollBack();
                report($e);
                return redirect()->back()->withErrors(['error' => 'Gagal memperbarui dokumen.'])->withInput();
            }
        }

        $validated = $request->validate([
            'nama_pendaftar' => 'required|exists:pendaftaran,slug',
            'nama_dokumen' => 'required|string|in:akta-kelahiran,kartu-keluarga,ktp-orang-tua,pas-foto-peserta-didik',
            'dokumen' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx|max:2048',
        ], [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'nama_pendaftar.exists' => 'Nama pendaftar tidak ditemukan.',
            'nama_dokumen.in' => 'Nama dokumen tidak valid.',
            'dokumen.required' => 'File dokumen wajib diunggah.',
            'dokumen.file' => 'File dokumen tidak valid.',
            'dokumen.mimes' => 'Jenis file tidak didukung. Hanya jpg, jpeg, png, pdf, dan docx.',
            'dokumen.max' => 'Ukuran file maksimal 2MB.',
        ], [
            'nama_pendaftar' => 'Nama Pendaftar',
            'nama_dokumen' => 'Nama Dokumen',
            'dokumen' => 'File Dokumen',
        ]);

        DB::beginTransaction();

        try {
            try {
                $pendaftaran = Pendaftaran::where('slug', $validated['nama_pendaftar'])->firstOrFail();
                $dokumen = $pendaftaran->dokumen()->withTrashed()->where('slug', $dokumenSlug)->firstOrFail();
            } catch (ModelNotFoundException $e) {
                return redirect()->back()->withErrors(['nama_pendaftar' => 'Pendaftaran atau Dokumen tidak ditemukan.'])->withInput();
            }

            if ($request->hasFile('dokumen')) {
                if (Storage::disk('public')->exists($dokumen->path_dokumen)) {
                    Storage::disk('public')->delete($dokumen->path_dokumen);
                }
                
                $path = $request->file('dokumen')->store('pendaftaran/' . $validated['nama_dokumen'], 'public');
                
                $dokumen->update([
                    'pendaftaran_id' => $pendaftaran->id_pendaftaran,
                    'nama_dokumen' => Str::title(str_replace('-', ' ', $validated['nama_dokumen'])),
                    'path_dokumen' => $path,
                ]);
        
                DB::commit();
        
                return redirect()->route('show.data-document')->with('success', 'Dokumen berhasil diperbarui.');
            } else {
                return redirect()->back()->withErrors(['dokumen' => 'Dokumen tidak ditemukan.'])->withInput();
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->withErrors(['error' => 'Gagal memperbarui dokumen.'])->withInput();
        }
    }

    public function restoreDataDokumen($pendaftaranSlug, $dokumenSlug) {
        if ($pendaftaranSlug === 'default') return redirect()->back()->with('error', 'Dokumen bawaan tidak dapat dihapus.');

        try {
            $pendaftaran = Pendaftaran::withTrashed()->where('slug', $pendaftaranSlug)->firstOrFail();
            if ($pendaftaran->trashed()) return redirect()->back()->with('error', 'Tidak dapat memulihkan dokumen dari pendaftar yang sudah dihapus.');
            $dokumen = $pendaftaran->dokumen()->onlyTrashed()->where('slug', $dokumenSlug)->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-document')->with('error', 'Pendaftaran atau Dokumen tidak ditemukan.');
        }

        $existing = $pendaftaran->dokumen()->where('nama_dokumen', $dokumen->nama_dokumen)->whereNull('deleted_at')->exists();
        if ($existing) return redirect()->back()->with('error', 'Dokumen dengan nama tersebut sudah aktif untuk pendaftar ini.');

        if (Storage::disk('public')->exists($dokumen->path_dokumen)) {
            $namaDokumen = basename($dokumen->path_dokumen);
            $folder = Str::beforeLast($dokumen->slug, '-');
            $newPath = 'pendaftaran/' . $folder . '/' . $namaDokumen;

            try {
                Storage::disk('public')->move($dokumen->path_dokumen, $newPath);

                $dokumen->update(['path_dokumen' => $newPath]);
            } catch (\Exception $e) {
                report($e);
                return redirect()->back()->with('error', 'Gagal memulihkan file dokumen.');
            }
        } else {
            return redirect()->back()->with('error', 'File dokumen tidak ditemukan.');
        }

        $dokumen->restore();

        return redirect()->route('show.data-document')->with('success', 'Dokumen berhasil dipulihkan.');
    }

    public function destroyDataDokumen($pendaftaranSlug, $dokumenSlug) {
        if ($pendaftaranSlug === 'default') return redirect()->back()->with('error', 'Dokumen bawaan tidak dapat dihapus.');

        try {
            $pendaftaran = Pendaftaran::where('slug', $pendaftaranSlug)->firstOrFail();
            $dokumen = $pendaftaran->dokumen()->where('slug', $dokumenSlug)->whereNull('deleted_at')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return redirect()->route('show.data-document')->with('error', 'Pendaftaran atau Dokumen tidak ditemukan.');
        }

        if (Storage::disk('public')->exists($dokumen->path_dokumen)) {
            $namaDokumen = basename($dokumen->path_dokumen);
            $folder = Str::beforeLast($dokumen->slug, '-');
            $newPath = 'trashed/' . $folder . '/' . $namaDokumen;

            try {
                Storage::disk('public')->move($dokumen->path_dokumen, $newPath);

                $dokumen->update(['path_dokumen' => $newPath]);
            } catch (\Exception $e) {
                report($e);
                return redirect()->back()->with('error', 'Gagal memindahkan file dokumen.');
            }
        } else {
            return redirect()->back()->with('error', 'File dokumen tidak ditemukan.');
        }

        $dokumen->delete();

        return redirect()->back()->with('success', 'Data dokumen berhasil dihapus.');
    }
}