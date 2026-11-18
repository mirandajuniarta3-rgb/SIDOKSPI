<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan archived_at jika belum tersedia
        if (!Schema::hasColumn('dokumen', 'archived_at')) {
            Schema::table('dokumen', function (Blueprint $table) {
                $table->timestamp('archived_at')
                      ->nullable()
                      ->after('status');
            });
        }

        // retensi_tahun tidak ditambahkan lagi
        // karena kolom tersebut sudah tersedia.
    }

    public function down(): void
    {
        // Hanya hapus kolom yang dikelola migration ini.
        if (Schema::hasColumn('dokumen', 'archived_at')) {
            Schema::table('dokumen', function (Blueprint $table) {
                $table->dropColumn('archived_at');
            });
        }
    }
};