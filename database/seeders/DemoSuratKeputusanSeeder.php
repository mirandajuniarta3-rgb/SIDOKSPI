<?php

namespace Database\Seeders;

use App\Models\SuratKeputusan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSuratKeputusanSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            $this->command?->warn('Demo SK data is only seeded in local or testing environments.');

            return;
        }

        $uploaderNames = [
            'budi.prasetyo@example.test' => 'Budi Prasetyo',
            'siti.aminah@example.test' => 'Siti Aminah',
            'hendra.setiawan@example.test' => 'Hendra Setiawan',
            'rian.hidayat@example.test' => 'Rian Hidayat',
            'dewi.kartika@example.test' => 'Dewi Kartika',
        ];
        $uploaderIds = [];

        foreach ($uploaderNames as $email => $name) {
            $uploaderIds[$email] = User::firstOrCreate(
                ['email' => $email],
                [
                    'nama_lengkap' => $name,
                    'password_hash' => Hash::make(Str::random(40)),
                    'role' => 'auditee',
                    'bidang' => 'SPI',
                    'status' => 'aktif',
                ]
            )->id;
        }

        $activeExamples = [
            ['188.4/045/SK-DIR/2024', 'Pembentukan Tim Audit Kepatuhan Pengadaan Barang & Jasa TA 2025', 'Tata Kelola & Kepatuhan PBJ', '2024-11-04', 'budi.prasetyo@example.test', 840],
            ['188.4/041/SK-DIR/2024', 'Penyesuaian Standar Biaya Operasional dan Perjalanan Dinas Auditor SPI', 'Standar Remunerasi & Biaya', '2024-10-29', 'siti.aminah@example.test', 720],
            ['188.4/038/SK-DIR/2024', 'Pengangkatan Anggota Satuan Pengawas Internal Periode 2024-2027', 'Struktur Organisasi SPI', '2024-10-15', 'hendra.setiawan@example.test', 650],
            ['188.4/035/SK-DIR/2024', 'Pedoman Pelaksanaan Reviu Laporan Keuangan Berkala PDAM', 'Reviu Kinerja Finansial', '2024-10-01', 'rian.hidayat@example.test', 960],
            ['188.4/029/SK-DIR/2024', 'Penetapan Batas Ambang Risiko Operasional Pengolahan Air Bersih IPA', 'Manajemen Risiko Mutu Air', '2024-09-12', 'dewi.kartika@example.test', 780],
            ['188.4/015/SK-DIR/2023', 'Pemberlakuan Standar Operasional Prosedur Pengendalian Internal Terpadu', 'SOP Kepatuhan ISO 27001', '2024-08-18', 'budi.prasetyo@example.test', 1050],
        ];

        foreach ($activeExamples as [$number, $title, $about, $date, $uploader, $size]) {
            $this->createDecision(
                $number,
                $title,
                $about,
                $date,
                $uploader,
                'final',
                $size,
                $uploaderIds
            );
        }

        $archivedExamples = [
            ['188.4/088/SK-DIR/2022', 'Ketentuan Retensi Arsip dan Tata Kelola Dokumen SPI', 'Kearsipan & Regulasi', '2022-10-10', 'hendra.setiawan@example.test', 620],
            ['188.4/004/SK-DIR/2021', 'Pendelegasian Wewenang Audit Khusus', 'Pengendalian Internal', '2021-01-14', 'siti.aminah@example.test', 580],
        ];

        foreach ($archivedExamples as [$number, $title, $about, $date, $uploader, $size]) {
            $this->createDecision(
                $number,
                $title,
                $about,
                $date,
                $uploader,
                'arsip',
                $size,
                $uploaderIds
            );
        }

        $topics = [
            'Tata Kelola & Kepatuhan',
            'Pengendalian Internal',
            'Manajemen Risiko Operasional',
            'Struktur Organisasi SPI',
            'Standar Prosedur Audit',
            'Kearsipan & Regulasi',
        ];
        $titles = [
            'Penetapan Kebijakan Pengawasan Internal',
            'Pembentukan Tim Pemeriksaan Kepatuhan',
            'Pedoman Pengendalian dan Evaluasi Operasional',
            'Perubahan Susunan Tim Audit SPI',
            'Pemberlakuan Standar Tata Kelola Perusahaan',
            'Penetapan Prosedur Tindak Lanjut Temuan',
        ];
        $uploaderEmails = array_keys($uploaderIds);

        for ($sequence = 7; $sequence <= 398; $sequence++) {
            $date = Carbon::parse('2019-12-31')->subDays(($sequence * 11) % 700);
            $uploader = $uploaderEmails[($sequence - 7) % count($uploaderEmails)];

            $this->createDecision(
                sprintf('188.4/%04d/SK-DIR/%d', 1000 + $sequence, $date->year),
                $titles[($sequence - 7) % count($titles)].' No. '.($sequence - 6),
                $topics[($sequence - 7) % count($topics)],
                $date,
                $uploader,
                'final',
                500 + (($sequence * 43) % 4000),
                $uploaderIds
            );
        }

        for ($sequence = 9; $sequence <= 40; $sequence++) {
            $date = Carbon::parse('2020-12-31')->subDays(($sequence - 9) * 10);
            $uploader = $uploaderEmails[($sequence - 9) % count($uploaderEmails)];

            $this->createDecision(
                sprintf('188.4/%03d/SK-DIR/%d', 400 + $sequence, $date->year),
                $titles[($sequence - 9) % count($titles)].' (Arsip) No. '.($sequence - 8),
                $topics[($sequence - 9) % count($topics)],
                $date,
                $uploader,
                'arsip',
                500 + (($sequence * 31) % 2500),
                $uploaderIds
            );
        }
    }

    private function createDecision(
        string $number,
        string $title,
        string $about,
        Carbon|string $date,
        string $uploader,
        string $status,
        int $size,
        array $uploaderIds
    ): void {
        $date = $date instanceof Carbon ? $date : Carbon::parse($date);
        $sequence = abs(crc32($number)) % 100000;
        $filename = sprintf('SK-%05d.pdf', $sequence);

        SuratKeputusan::firstOrCreate(
            ['nomor_sk' => $number],
            [
                'uploader_id' => $uploaderIds[$uploader],
                'judul' => $title,
                'tentang' => $about,
                'tanggal_penetapan' => $date->toDateString(),
                'tahun' => $date->year,
                'nama_file' => $filename,
                'path_file' => 'sk/demo/'.$filename,
                'ukuran_kb' => $size,
                'status' => $status,
                'created_at' => $date,
                'updated_at' => $date,
            ]
        );
    }
}
