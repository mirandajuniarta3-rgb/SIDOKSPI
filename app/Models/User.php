<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nama_lengkap',
        'nip',
        'email',
        'password_hash',
        'role',
        'bidang',
        'status',
    ];

    public const UPDATED_AT = null;

    protected $hidden = [
        'password_hash',
    ];

    /**
     * Menggunakan password_hash sebagai password autentikasi.
     */
    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    /**
     * Relasi dokumen yang diunggah pengguna.
     */
    public function dokumen(): HasMany
    {
        return $this->hasMany(Dokumen::class, 'uploader_id');
    }

    /**
     * Relasi surat keputusan yang diunggah pengguna.
     */
    public function suratKeputusan(): HasMany
    {
        return $this->hasMany(SuratKeputusan::class, 'uploader_id');
    }

    /**
     * Relasi log aktivitas pengguna.
     */
    public function logAktivitas(): HasMany
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }
}
