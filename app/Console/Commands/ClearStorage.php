<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ClearStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menghapus semua isi folder dokumen di storage/app/public/';

    protected $folders = [
        'pendaftaran/akta-kelahiran/',
        'pendaftaran/kartu-keluarga/',
        'pendaftaran/ktp-orang-tua/',
        'pendaftaran/pas-foto-peserta-didik/',
        'trashed/akta-kelahiran/',
        'trashed/kartu-keluarga/',
        'trashed/ktp-orang-tua/',
        'trashed/pas-foto-peserta-didik/',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        foreach ($this->folders as $folder) {
            if (Storage::disk('public')->exists($folder)) {
                Storage::disk('public')->deleteDirectory($folder);
                Storage::disk('public')->makeDirectory($folder);
                $this->info("Folder 'public/{$folder}' berhasil dikosongkan.");
            } else {
                $this->warn("Folder 'public/{$folder}' tidak ditemukan.");
            }
        }

        $this->info("Semua folder dokumen selesai dibersihkan.");
    }
}
