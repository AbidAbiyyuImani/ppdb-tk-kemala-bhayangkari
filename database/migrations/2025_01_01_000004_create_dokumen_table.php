<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id('id_dokumen');
            $table->string('slug')->unique();

            $table->foreignId('pendaftaran_id')->nullable()->constrained('pendaftaran', 'id_pendaftaran');
            $table->enum('nama_dokumen', [
                'Logo Sekolah',
                'Formulir Pendaftaran',
                'Detail Pendaftaran', 
                'Akta Kelahiran',
                'Kartu Keluarga',
                'KTP Orang Tua',
                'Pas Foto Peserta Didik',
            ]);
            $table->text('path_dokumen');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};