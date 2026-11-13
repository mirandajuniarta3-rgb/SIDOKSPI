<?php

namespace Database\Seeders;

use App\Models\Dokumen;
use App\Models\KategoriDokumen;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDocumentSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local', 'testing')) {
            $this->command?->warn('Demo documents are only seeded in local or testing environments.');

            return;
        }

        $categoryNames = [
            'LHA' => 'Laporan Hasil Audit',
            'NOTA' => 'Nota Audit',
            'KKP' => 'KKP',
            'TL' => 'TL Temuan',
            'PEM' => 'Pemeriksaan',
        ];
        $categoryIds = [];

        foreach ($categoryNames as $code => $name) {
            $categoryIds[$code] = KategoriDokumen::firstOrCreate(
                ['kode_kategori' => $code],
                ['nama_kategori' => $name]
            )->id;
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

        $examples = [
            ['DOK/SPI/2024/11-042', 'LHA Operasional Izin Perdagangan Air Bersih', 'LHA', '2024-11-10 16:45:00', 'budi.prasetyo@example.test', 'final', 'LHA-Izin-Perdagangan.pdf', 4300],
            ['DOK/SPI/2024/11-039', 'Nota Audit Kepatuhan Pengadaan Barang dan Jasa', 'NOTA', '2024-11-08 10:20:00', 'siti.aminah@example.test', 'final', 'Nota-Audit-Pengadaan.pdf', 1800],
            ['DOK/SPI/2024/10-112', 'KKP Evaluasi Efisiensi Distribusi Air', 'KKP', '2024-11-05 13:15:00', 'hendra.setiawan@example.test', 'final', 'KKP-Efisiensi-Distribusi.xlsx', 3700],
            ['DOK/SPI/2024/10-098', 'Laporan Hasil Tindak Lanjut Temuan Audit', 'TL', '2024-11-01 09:00:00', 'budi.prasetyo@example.test', 'arsip', 'Laporan-Tindak-Lanjut.pdf', 5500],
            ['DOK/SPI/2024/10-076', 'Verifikasi Fisik Meteran Induk Wilayah Utara', 'PEM', '2024-10-28 15:10:00', 'rian.hidayat@example.test', 'final', 'Verifikasi-Meteran.pdf', 3400],
            ['DOK/SPI/2024/10-062', 'Audit Kepatuhan SOP Layanan Pelanggan', 'LHA', '2024-10-24 11:25:00', 'dewi.kartika@example.test', 'final', 'Audit-SOP-Layanan.pdf', 2400],
            ['DOK/SPI/2024/10-045', 'Laporan Audit Khusus Billing dan Penagihan', 'LHA', '2024-10-19 08:40:00', 'budi.prasetyo@example.test', 'final', 'Audit-Billing.pdf', 5900],
            ['DOK/SPI/2024/10-022', 'KKP Review Pajak Air Permukaan', 'KKP', '2024-10-12 14:30:00', 'siti.aminah@example.test', 'arsip', 'KKP-Pajak-Air.xlsx', 3700],
            ['DOK/SPI/2024/09-094', 'Audit Keselamatan Kerja dan K3', 'PEM', '2024-09-29 10:05:00', 'rian.hidayat@example.test', 'final', 'Audit-Keselamatan-Kerja.pdf', 4000],
            ['DOK/SPI/2024/09-081', 'Nota Rekomendasi SPI atas Pengendalian Internal', 'NOTA', '2024-09-15 09:35:00', 'dewi.kartika@example.test', 'final', 'Nota-Rekomendasi-SPI.pdf', 1200],
        ];

        foreach ($examples as [$number, $title, $category, $createdAt, $uploader, $status, $filename, $size]) {
            $this->createDocument(
                $number,
                $title,
                $category,
                $createdAt,
                $uploader,
                $status,
                $filename,
                $size,
                $categoryIds,
                $uploaderIds
            );
        }

        $categories = array_keys($categoryIds);
        $uploaders = array_keys($uploaderIds);
        $generatedTitles = [
            'Rekapitulasi Hasil Audit Unit Pelayanan',
            'Pemeriksaan Kepatuhan Administrasi Keuangan',
            'Evaluasi Pengendalian Operasional Cabang',
            'Tindak Lanjut Rekomendasi Pemeriksaan Internal',
            'Review Prosedur Pengadaan dan Persediaan',
        ];

        for ($sequence = 11; $sequence <= 120; $sequence++) {
            $date = Carbon::parse('2024-09-14 08:00:00')->subDays($sequence - 11);
            $category = $categories[($sequence - 11) % count($categories)];
            $filename = sprintf('Arsip-Pengawasan-%03d.pdf', $sequence);

            $this->createDocument(
                sprintf('DOK/SPI/2024/DEMO-%03d', $sequence),
                $generatedTitles[($sequence - 11) % count($generatedTitles)].' No. '.($sequence - 10),
                $category,
                $date,
                $uploaders[($sequence - 11) % count($uploaders)],
                $sequence <= 30 ? 'arsip' : 'final',
                $filename,
                1000 + ($sequence * 37) % 5000,
                $categoryIds,
                $uploaderIds
            );
        }
    }

    private function createDocument(
        string $number,
        string $title,
        string $category,
        Carbon|string $createdAt,
        string $uploader,
        string $status,
        string $filename,
        int $size,
        array $categoryIds,
        array $uploaderIds
    ): void {
        $timestamp = $createdAt instanceof Carbon ? $createdAt : Carbon::parse($createdAt);

        $document = Dokumen::firstOrCreate(
            ['nomor_dokumen' => $number],
            [
                'kategori_id' => $categoryIds[$category],
                'uploader_id' => $uploaderIds[$uploader],
                'bidang' => 'Pengawasan Internal',
                'judul' => $title,
                'deskripsi' => 'Data contoh untuk tampilan dan pengujian daftar dokumen.',
                'tanggal_dokumen' => $timestamp->toDateString(),
                'tahun' => $timestamp->year,
                'tingkat_kerahasiaan' => 'umum',
                'status' => $status,
                'archived_at' => $status === 'arsip' ? $timestamp : null,
                'retensi_tahun' => $status === 'arsip' ? 5 : null,
                'nama_file' => $filename,
                'path_file' => 'dokumen/demo/'.$filename,
                'ukuran_kb' => $size,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ]
        );

        if ($status === 'arsip' && $document->status === 'arsip' && $document->archived_at === null) {
            $document->forceFill([
                'archived_at' => $timestamp,
                'retensi_tahun' => $document->retensi_tahun ?? 5,
            ])->save();
        }
    }
}
