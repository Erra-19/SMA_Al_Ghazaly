<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentImportController extends Controller
{
    private array $headers = [
        'Nama Lengkap*', 'NIS', 'NISN', 'NIK',
        'Jenis Kelamin (male/female)', 'Tempat Lahir', 'Tanggal Lahir (YYYY-MM-DD)',
        'Alamat', 'No. HP', 'Email', 'Asal Sekolah',
        'Kelas (X IPA 1 / XI IPS 2 / dst)', 'Jurusan', 'Tahun Ajaran (2024/2025)',
        'Status (active/inactive/graduated/transferred/dropped_out)',
        'Nama Orang Tua', 'No. HP Orang Tua', 'Catatan', 'Urutan Tampil',
    ];

    public function template(): StreamedResponse
    {
        $ss    = new Spreadsheet();
        $sheet = $ss->getActiveSheet()->setTitle('Murid');

        foreach ($this->headers as $col => $header) {
            $cell = chr(65 + $col) . '1';
            $sheet->setCellValue($cell, $header);
            $sheet->getStyle($cell)->getFont()->setBold(true);
            $sheet->getStyle($cell)->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('E3F2FD');
        }

        $example = [
            'Ahmad Fauzan', '240001', '0123456789', '3201012345670001',
            'male', 'Bogor', '2008-05-15',
            'Jl. Raya Leuwiliang No. 1', '081234567890', 'ahmad@gmail.com', 'SMP Negeri 1 Cibungbulang',
            'X IPA 1', 'IPA', '2024/2025',
            'active',
            'Budi Santoso', '081298765432', '', 1,
        ];
        foreach ($example as $col => $val) {
            $sheet->setCellValue(chr(65 + $col) . '2', $val);
        }

        foreach (range(0, count($this->headers) - 1) as $i) {
            $sheet->getColumnDimension(chr(65 + $i))->setAutoSize(true);
        }

        return response()->streamDownload(
            fn () => (new Xlsx($ss))->save('php://output'),
            'template-murid.xlsx',
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

        $validStatuses = ['active', 'inactive', 'graduated', 'transferred', 'dropped_out'];

        foreach ($rows as $i => $row) {
            if ($i === 0 || empty(trim((string)($row[0] ?? '')))) continue;

            try {
                [$name, $nis, $nisn, $nik, $gender, $birth_place, $birth_date,
                 $address, $phone, $email, $previous_school,
                 $grade_level, $major, $academic_year,
                 $status, $parent_name, $parent_phone, $notes, $order] = array_pad($row, 19, null);

                $statusVal = in_array(trim((string)$status), $validStatuses) ? trim($status) : 'active';

                $data = [
                    'name'            => trim($name),
                    'nis'             => $nis ? trim($nis) : null,
                    'nisn'            => $nisn ? trim($nisn) : null,
                    'nik'             => $nik ? trim($nik) : null,
                    'gender'          => in_array(strtolower(trim((string)$gender)), ['male','female'])
                                          ? strtolower(trim($gender)) : null,
                    'birth_place'     => $birth_place ? trim($birth_place) : null,
                    'birth_date'      => $birth_date ? trim($birth_date) : null,
                    'address'         => $address ? trim($address) : null,
                    'phone'           => $phone ? trim($phone) : null,
                    'email'           => $email ? trim($email) : null,
                    'previous_school' => $previous_school ? trim($previous_school) : null,
                    'grade_level'     => $grade_level ? trim($grade_level) : null,
                    'major'           => $major ? trim($major) : null,
                    'academic_year'   => $academic_year ? trim($academic_year) : null,
                    'status'          => $statusVal,
                    'parent_name'     => $parent_name ? trim($parent_name) : null,
                    'parent_phone'    => $parent_phone ? trim($parent_phone) : null,
                    'notes'           => $notes ? trim($notes) : null,
                    'order'           => $order !== null ? (int) $order : 0,
                    'is_active'       => true,
                ];

                $existing = null;
                if ($nisn) $existing = Student::where('nisn', trim($nisn))->first();
                if (!$existing && $nis) $existing = Student::where('nis', trim($nis))->first();

                if ($existing) {
                    $existing->update($data);
                    $updated++;
                } else {
                    Student::create($data);
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
