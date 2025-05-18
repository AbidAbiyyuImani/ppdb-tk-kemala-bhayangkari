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
        Schema::create('pendaftaran', function (Blueprint $table) {
            $table->id('id_pendaftaran');
            $table->string('slug')->unique();
            $table->foreignId('user_id')->constrained('user', 'id_user');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas', 'id_kelas');

            $table->string('nama_anak');
            $table->string('nama_panggilan');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('agama');
            $table->string('anak_ke');
            $table->enum('status_anak', [
                'anak-kandung',
                'anak-tiri',
                'anak-angkat',
                'anak-asuh',
                'anak-angkat-siri',
                'anak-tiri-siri',
                'anak-dalam-perwalian',
                'lainnya'
            ]);

            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('nama_wali')->nullable();
            $table->string('pekerjaan_ayah')->nullable();
            $table->string('pekerjaan_ibu')->nullable();
            $table->string('pekerjaan_wali')->nullable();

            $table->string('alamat');
            $table->string('kelurahan');
            $table->string('no_telp');
            $table->string('email')->nullable();
            $table->string('no_wa')->nullable();

            $table->string('imunisasi_vaksin_yang_pernah_diterima');
            $table->string('penyakit_berat_yang_diderita');
            $table->string('jarak_dari_rumah');
            $table->string('golongan_darah');

            $table->enum('status_pendaftaran', ['Diajukan', 'Diterima', 'Ditolak'])->default('Diajukan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran');
    }
};