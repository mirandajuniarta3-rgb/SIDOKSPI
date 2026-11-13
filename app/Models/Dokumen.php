<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use HasFactory;

    protected $table = 'dokumen';

    protected $fillable = [
        'kategori_id',
        'uploader_id',
        'bidang',
        'nomor_dokumen',
        'judul',
        'deskripsi',
        'tanggal_dokumen',
        'tahun',
        'tingkat_kerahasiaan',
        'nama_file',
        'path_file',
        'ukuran_kb',
        'archived_at',
        'retensi_tahun',
        'status',
    ];

    protected $casts = [
        'tanggal_dokumen' => 'date',
        'archived_at' => 'datetime',
        'tahun' => 'integer',
        'retensi_tahun' => 'integer',
        'ukuran_kb' => 'integer',
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
