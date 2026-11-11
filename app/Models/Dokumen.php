<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'kategori_id',
        'uploader_id',
        'nomor_dokumen',
        'judul',
        'deskripsi',
        'tanggal_dokumen',
        'tahun',
        'nama_file',
        'path_file',
        'ukuran_kb',
        'status',
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
        'tahun' => 'integer',
    ];

    public function kategori()
    {
        return $this->belongsTo(
            KategoriDokumen::class,
            'kategori_id'
        );
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'dokumen_id');
    }
}