<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Carbon;

class DokumenExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Builder $dokumenQuery;

    public function __construct(Builder $query)
    {
        $this->dokumenQuery = $query;
    }

    public function query(): Builder
    {
        return clone $this->dokumenQuery;
    }

    public function headings(): array
    {
        return [
            'Nomor Dokumen',
            'Judul Dokumen',
            'Kategori',
            'Tahun',
            'Tanggal Dokumen',
            'Pengunggah',
            'Nama File',
            'Ukuran (KB)',
            'Status',
            'Deskripsi',
        ];
    }

    public function map($dokumen): array
    {
        return [
            $dokumen->nomor_dokumen,
            $dokumen->judul,
            $dokumen->kategori?->nama_kategori ?? '-',
            $dokumen->tahun,
            filled($dokumen->tanggal_dokumen)
                ? Carbon::parse($dokumen->tanggal_dokumen)->format('d-m-Y')
                : '-',
            $dokumen->uploader?->nama_lengkap ?? '-',
            $dokumen->nama_file ?? '-',
            $dokumen->ukuran_kb ?? 0,
            ucfirst($dokumen->status ?? '-'),
            $dokumen->deskripsi ?? '-',
        ];
    }
}
