<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('kategori_dokumen');
            $table->foreignId('uploader_id')->constrained('users');
            $table->string('bidang', 100)->nullable();
            $table->string('nomor_dokumen', 100)->nullable()->unique();
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_dokumen')->nullable();
            $table->integer('tahun')->nullable()->index();
            $table->string('tingkat_kerahasiaan', 15)->default('umum');
            $table->string('status', 15)->default('draft');
            $table->string('nama_file', 255)->nullable();
            $table->string('path_file', 500)->nullable();
            $table->integer('ukuran_kb')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
