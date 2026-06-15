<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TeacherImportController extends Controller
{
    private array $headers = [
        'Nama Lengkap*', 'NIP', 'Jabatan/Posisi', 'Mata Pelajaran',
        'Kategori (imtak/mipa/social-english/bk-staf)',
        'Email', 'No. HP', 'Riwayat Pendidikan', 'Lama Pengabdian',
        'Tag Keahlian (pisah koma)', 'Filsafat/Moto Mengajar', 'Biografi',
        'Urutan Tampil', 'Pimpinan (1=ya / 0=tidak)', 'Aktif (1=ya / 0=tidak)',
    ];

    public function template(): StreamedResponse
    {
        $ss    = new Spreadsheet();
        $sheet = $ss->getActiveSheet()->setTitle('Guru');

        foreach ($this->headers as $col => $header) {
            $cell = chr(65 + $col) . '1';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E8F5E9');
        }

        $example = [
            'Dr. H. Ahmad Fauzi M.Pd.', '196802151990031002', 'Kepala Sekolah', 'Manajemen Pendidikan',
            'imtak', 'ahmad@alghazaly.sch.id', '081234567890',
            'S3 Manajemen Pendidikan UPI', '24 Tahun Mengabdi',
            'Kepemimpinan, Pendidikan Islam',
            'Pendidikan adalah kunci masa depan bangsa.',
            'Guru berpengalaman di bidang manajemen pendidikan.',
            1, 1, 1,
        ];
        foreach ($example as $col => $val) {
            $sheet->setCellValue(chr(65 + $col) . '2', $val);
        }

        foreach (range(0, count($this->headers) - 1) as $i) {
            $sheet->getColumnDimension(chr(65 + $i))->setAutoSize(true);
        }

        return response()->streamDownload(
            fn () => (new Xlsx($ss))->save('php://output'),
            'template-guru.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        );
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,xls|max:5120']);

        $rows = IOFactory::load($request->file('file')->getPathname())
            ->getActiveSheet()
            ->toArray(null, true, true, false);

        $inserted = $updated = $failed = 0;
        $errors = [];

        foreach ($rows as $i => $row) {
            if ($i === 0 || empty(trim((string)($row[0] ?? '')))) continue;

            try {
                [$name, $nip, $position, $subject, $category, $email, $phone,
                 $education, $experience, $tags, $philosophy, $bio,
                 $order, $is_leadership, $is_active] = array_pad($row, 15, null);

                $data = [
                    'name'          => trim($name),
                    'nip'           => $nip ? trim($nip) : null,
                    'position'      => $position ? trim($position) : null,
                    'subject'       => $subject ? trim($subject) : null,
                    'category'      => in_array(trim((string)$category), ['imtak','mipa','social-english','bk-staf'])
                                        ? trim($category) : 'mipa',
                    'email'         => $email ? trim($email) : null,
                    'phone'         => $phone ? trim($phone) : null,
                    'education'     => $education ? trim($education) : null,
                    'experience'    => $experience ? trim($experience) : null,
                    'tags'          => $tags ? array_values(array_filter(array_map('trim', explode(',', $tags)))) : [],
                    'philosophy'    => $philosophy ? trim($philosophy) : null,
                    'bio'           => $bio ? trim($bio) : null,
                    'order'         => $order !== null ? (int) $order : 0,
                    'is_leadership' => (bool)(int)($is_leadership ?? 0),
                    'is_active'     => $is_active !== null ? (bool)(int)$is_active : true,
                ];

                $existing = $nip ? Teacher::where('nip', trim($nip))->first() : null;

                if ($existing) {
                    $existing->update($data);
                    $updated++;
                } else {
                    Teacher::create($data);
                    $inserted++;
                }
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = 'Baris ' . ($i + 1) . ': ' . $e->getMessage();
            }
        }

        return response()->json([
            'message'  => "Impor selesai: {$inserted} ditambah, {$updated} diperbarui, {$failed} gagal.",
            'inserted' => $inserted,
            'updated'  => $updated,
            'failed'   => $failed,
            'errors'   => array_slice($errors, 0, 10),
        ]);
    }
}
