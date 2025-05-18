<?php

namespace Database\Seeders;

use App\Models\Dokumen;
use App\Models\Kelas;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'Admin',
        ]);

        Dokumen::create([
            'nama_dokumen' => 'Logo Sekolah',
            'path_dokumen' => 'logo.png',
        ]);
        Dokumen::create([
            'nama_dokumen' => 'Formulir Pendaftaran',
            'path_dokumen' => 'formulir_pendaftaran_pg.pdf',
        ]);
        Dokumen::create([
            'nama_dokumen' => 'Detail Pendaftaran',
            'path_dokumen' => 'detail_pendaftaran_pg.pdf',
        ]);

        Kelas::create([
            'nama_kelas' => 'PG',
            'jumlah_siswa' => 10,
        ]);
        Kelas::create([
            'nama_kelas' => 'A1',
            'jumlah_siswa' => 20,
        ]);
        Kelas::create([
            'nama_kelas' => 'B1',
            'jumlah_siswa' => 15,
        ]);
        Kelas::create([
            'nama_kelas' => 'B2',
            'jumlah_siswa' => 11,
        ]);
        
        // User::create([
        //     'nama' => 'Abid Abiyyu Imani',
        //     'email' => 'abidabiyyuimani@gmail.com',
        //     'password' => Hash::make('12345678'),
        // ]);
    }
}