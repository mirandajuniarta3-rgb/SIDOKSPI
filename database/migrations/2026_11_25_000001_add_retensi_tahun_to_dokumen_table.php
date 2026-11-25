<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('dokumen', 'retensi_tahun')) {
            Schema::table('dokumen', function (Blueprint $table) {
                $table->unsignedSmallInteger('retensi_tahun')
                    ->nullable()
                    ->after('archived_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('dokumen', 'retensi_tahun')) {
            Schema::table('dokumen', function (Blueprint $table) {
                $table->dropColumn('retensi_tahun');
            });
        }
    }
};
