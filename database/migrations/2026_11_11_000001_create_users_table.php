<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap', 100);
            $table->string('nip', 30)->nullable()->unique();
            $table->string('email', 100)->unique();
            $table->string('password_hash');
            $table->string('role', 50)->default('auditee');
            $table->string('bidang', 100)->nullable();
            $table->string('status', 10)->default('aktif');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
