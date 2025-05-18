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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->string('slug')->unique();

            $table->foreignId('user_id')->constrained('user', 'id_user');
            $table->text('judul');
            $table->text('isi_pesan');
            $table->enum('status_baca', ['Dibaca', 'Belum Dibaca'])->default('Belum Dibaca');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};