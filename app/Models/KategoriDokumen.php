<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriDokumen extends Model
{
    use HasFactory;

    protected $table = 'kategori_dokumen';

    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
        'keterangan',
    ];

    public function dokumen()
    {
        return $this->hasMany(Dokumen::class, 'kategori_id');
    }
}