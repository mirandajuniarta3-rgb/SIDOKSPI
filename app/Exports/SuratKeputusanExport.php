<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SuratKeputusanExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    protected Builder $suratKeputusanQuery;

    public function __construct(Builder $query)
    {
        $this->suratKeputusanQuery = $query;
    }

    public function query(): Builder
    {
        return clone $this->suratKeputusanQuery;
    }

    public function headings(): array
    {
        return [
            'Nomor SK',
            'Judul Keputusan',
            'Tentang / Perihal',
            'Tanggal Penetapan',
            'Tahun',
            'Pengunggah',
            'Nama File',
            'Ukuran (KB)',
            'Status',
        ];
    }

    public function map($sk): array
    {
        return [
            $sk->nomor_sk,
            $sk->judul,
            $sk->tentang ?? '-',
            filled($sk->tanggal_penetapan)
                ? Carbon::parse($sk->tanggal_penetapan)->format('d-m-Y')
                : '-',
            $sk->tahun,
            $sk->uploader?->nama_lengkap ?? '-',
            $sk->nama_file ?? '-',
            $sk->ukuran_kb ?? 0,
            ucfirst($sk->status ?? '-'),
        ];
    }
}
