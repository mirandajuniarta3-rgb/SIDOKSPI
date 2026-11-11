<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'uploader_id');
    }

    public function suratKeputusan()
    {
        return $this->hasMany(SuratKeputusan::class, 'uploader_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'user_id');
    }
}
