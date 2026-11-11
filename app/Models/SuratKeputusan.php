<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratKeputusan extends Model
{
    use HasFactory;

    protected $table = 'surat_keputusan';

    protected $fillable = [
        'uploader_id',
        'nomor_sk',
        'judul',
        'tentang',
        'tanggal_penetapan',
        'tahun',
        'nama_file',
        'path_file',
        'ukuran_kb',
        'status',
    ];

    protected $casts = [
        'tanggal_penetapan' => 'date',
        'tahun' => 'integer',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function logAktivitas()
    {
        return $this->hasMany(LogAktivitas::class, 'sk_id');
    }
}