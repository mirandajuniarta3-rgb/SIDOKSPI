<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keputusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('uploader_id')->constrained('users');
            $table->string('nomor_sk', 100)->unique();
            $table->string('judul', 255);
            $table->text('tentang')->nullable();
            $table->date('tanggal_penetapan')->nullable();
            $table->integer('tahun')->nullable()->index();
            $table->string('nama_file', 255)->nullable();
            $table->string('path_file', 500)->nullable();
            $table->integer('ukuran_kb')->nullable();
            $table->string('status', 15)->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keputusan');
    }
};
