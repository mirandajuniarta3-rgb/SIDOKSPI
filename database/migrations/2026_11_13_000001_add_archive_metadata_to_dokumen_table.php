<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable()->after('status');
            $table->unsignedSmallInteger('retensi_tahun')->nullable()->after('archived_at');
        });
    }

    public function down(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropColumn(['archived_at', 'retensi_tahun']);
        });
    }
};
