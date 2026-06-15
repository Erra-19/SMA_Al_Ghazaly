<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('students')->count() > 0) {
            $this->command->info('Data murid sudah ada, skip.');
            return;
        }

        $now = now();

        // Helper
        $s = fn(string $name, string $nis, string $nisn, string $gender,
                 string $bPlace, string $bDate, string $kelas, string $jurusan,
                 string $year, string $status, string $parent, string $parentPhone,
                 string $phone = '', string $address = 'Kota Bogor')
            => compact('name','nis','nisn','gender','bPlace','bDate','kelas','jurusan','year','status','parent','parentPhone','phone','address');

        $raw = [
            // ── Kelas X IPA ──────────────────────────────────────────────────
            $s('Ahmad Zulfikar Ramadhan',   '2024001', '0123456701', 'Laki-laki',  'Bogor',    '2009-03-12', 'X',   'IPA', '2024/2025', 'active',   'Budi Ramadhan',     '08121001001', '085601001001'),
            $s('Siti Nurhaliza',            '2024002', '0123456702', 'Perempuan', 'Jakarta',  '2009-07-24', 'X',   'IPA', '2024/2025', 'active',   'Hendra Setiawan',   '08121001002', '085601001002'),
            $s('Muhammad Farhan Al-Rasyid', '2024003', '0123456703', 'Laki-laki',  'Bogor',    '2009-11-05', 'X',   'IPA', '2024/2025', 'active',   'Dudi Santoso',      '08121001003', ''),
            $s('Amira Putri Anjani',        '2024004', '0123456704', 'Perempuan', 'Depok',    '2009-04-18', 'X',   'IPA', '2024/2025', 'active',   'Rudi Anjani',       '08121001004', '085601001004'),
            $s('Ibnu Zaki Habibi',         '2024005', '0123456705', 'Laki-laki',  'Bogor',    '2009-09-30', 'X',   'IPA', '2024/2025', 'active',   'Zainal Abidin',     '08121001005', ''),

            // ── Kelas X IPS ──────────────────────────────────────────────────
            $s('Nadia Azzahra',            '2024006', '0123456706', 'Perempuan', 'Sukabumi',  '2009-01-14', 'X',   'IPS', '2024/2025', 'active',   'Ahmad Taufiq',      '08121001006', '085601001006'),
            $s('Rizky Maulana',            '2024007', '0123456707', 'Laki-laki',  'Bogor',    '2009-06-22', 'X',   'IPS', '2024/2025', 'active',   'Slamet Riyadi',     '08121001007', ''),
            $s('Farah Diba Hasanah',       '2024008', '0123456708', 'Perempuan', 'Bogor',    '2009-12-10', 'X',   'IPS', '2024/2025', 'active',   'Mansur Hasanah',    '08121001008', '085601001008'),

            // ── Kelas XI IPA ─────────────────────────────────────────────────
            $s('Dimas Arya Putra',         '2023001', '0112345601', 'Laki-laki',  'Bogor',    '2008-02-28', 'XI',  'IPA', '2024/2025', 'active',   'Arya Wijaya',       '08121002001', '085601002001'),
            $s('Salma Khaerunnisa',        '2023002', '0112345602', 'Perempuan', 'Bogor',    '2008-05-17', 'XI',  'IPA', '2024/2025', 'active',   'Fahmi Hidayat',     '08121002002', '085601002002'),
            $s('Yusuf Al-Farisi',          '2023003', '0112345603', 'Laki-laki',  'Cianjur',  '2008-08-09', 'XI',  'IPA', '2024/2025', 'active',   'Farisi Maulana',    '08121002003', ''),
            $s('Nabila Ramadhani',         '2023004', '0112345604', 'Perempuan', 'Bogor',    '2008-10-25', 'XI',  'IPA', '2024/2025', 'active',   'Ramdan Hidayat',    '08121002004', '085601002004'),
            $s('Fauzan Hakim',             '2023005', '0112345605', 'Laki-laki',  'Bogor',    '2008-03-03', 'XI',  'IPA', '2024/2025', 'active',   'Hakim Santoso',     '08121002005', ''),

            // ── Kelas XI IPS ─────────────────────────────────────────────────
            $s('Putri Rahayu Ningsih',     '2023006', '0112345606', 'Perempuan', 'Jakarta',  '2008-07-20', 'XI',  'IPS', '2024/2025', 'active',   'Rahayu Wibowo',     '08121002006', '085601002006'),
            $s('Bagas Prasetyo',           '2023007', '0112345607', 'Laki-laki',  'Bogor',    '2008-11-15', 'XI',  'IPS', '2024/2025', 'active',   'Prasetyo Nugroho',  '08121002007', ''),

            // ── Kelas XII IPA ────────────────────────────────────────────────
            $s('Hana Marwa Salsabila',     '2022001', '0101234501', 'Perempuan', 'Bogor',    '2007-04-08', 'XII', 'IPA', '2024/2025', 'active',   'Salsabila Rahman',  '08121003001', '085601003001'),
            $s('Rafa Ilham Akbar',         '2022002', '0101234502', 'Laki-laki',  'Bogor',    '2007-06-14', 'XII', 'IPA', '2024/2025', 'active',   'Ilham Supriatna',   '08121003002', '085601003002'),
            $s('Zahra Fadiyah Putri',      '2022003', '0101234503', 'Perempuan', 'Bekasi',   '2007-09-01', 'XII', 'IPA', '2024/2025', 'active',   'Fadiyah Kusuma',    '08121003003', ''),
            $s('Kevin Ananda Putra',       '2022004', '0101234504', 'Laki-laki',  'Bogor',    '2007-01-27', 'XII', 'IPA', '2024/2025', 'active',   'Ananda Suryana',    '08121003004', '085601003004'),

            // ── Kelas XII IPS ────────────────────────────────────────────────
            $s('Aulia Maharani',           '2022005', '0101234505', 'Perempuan', 'Tangerang', '2007-08-19', 'XII', 'IPS', '2024/2025', 'active',   'Maharani Sutisna',  '08121003005', '085601003005'),
            $s('Bayu Saputra Wijaya',      '2022006', '0101234506', 'Laki-laki',  'Bogor',    '2007-03-30', 'XII', 'IPS', '2024/2025', 'active',   'Saputra Handaya',   '08121003006', ''),

            // ── Lulus (alumni) ───────────────────────────────────────────────
            $s('Firman Hidayatullah',      '2021001', '0090001001', 'Laki-laki',  'Bogor',    '2006-05-10', 'XII', 'IPA', '2023/2024', 'graduated','Hidayat Firmansyah', '08121004001', '085601004001'),
            $s('Laila Nur Azizah',         '2021002', '0090001002', 'Perempuan', 'Bogor',    '2006-10-22', 'XII', 'IPA', '2023/2024', 'graduated','Azizah Mardiyah',    '08121004002', ''),

            // ── Pindah ───────────────────────────────────────────────────────
            $s('Denny Suryanto',           '2024009', '0123456709', 'Laki-laki',  'Bogor',    '2009-02-14', 'X',   'IPS', '2024/2025', 'transferred','Suryanto Basuki',  '08121001009', ''),

            // ── Nonaktif ─────────────────────────────────────────────────────
            $s('Mia Ratnasari',            '2024010', '0123456710', 'Perempuan', 'Ciawi',    '2009-06-05', 'X',   'IPA', '2024/2025', 'inactive', 'Ratnasari Gunawan', '08121001010', ''),
        ];

        $rows = array_map(function ($r, $i) use ($now) {
            return [
                'name'            => $r['name'],
                'nis'             => $r['nis'],
                'nisn'            => $r['nisn'],
                'gender'          => $r['gender'],
                'birth_place'     => $r['bPlace'],
                'birth_date'      => $r['bDate'],
                'phone'           => $r['phone'] ?: null,
                'email'           => null,
                'address'         => $r['address'],
                'photo'           => null,
                'grade_level'     => $r['kelas'],
                'major'           => $r['jurusan'],
                'academic_year'   => $r['year'],
                'status'          => $r['status'],
                'parent_name'     => $r['parent'],
                'parent_phone'    => $r['parentPhone'],
                'previous_school' => null,
                'notes'           => null,
                'order'           => $i + 1,
                'is_active'       => 1,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
        }, $raw, array_keys($raw));

        DB::table('students')->insert($rows);

        $this->command->info('✓ ' . count($rows) . ' data murid di-seed.');
    }
}
