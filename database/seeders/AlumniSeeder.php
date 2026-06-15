<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        $alumnisData = [
            ['name' => 'Ahmad Fauzan Hakim',    'graduation_year' => 2022, 'current_institution' => 'Institut Teknologi Bandung', 'major' => 'Teknik Informatika', 'achievement' => 'Lolos SNBP ke Teknik Informatika ITB setelah aktif di olimpiade sains dan program coding sekolah.', 'is_published' => 1],
            ['name' => 'Siti Rahmawati',         'graduation_year' => 2021, 'current_institution' => 'Universitas Indonesia',      'major' => 'Kedokteran',         'achievement' => 'Diterima di Fakultas Kedokteran UI jalur SNBT dengan persiapan intensif bersama guru-guru Al-Ghazaly.', 'is_published' => 1],
            ['name' => 'Rizky Aditya Pratama',   'graduation_year' => 2023, 'current_institution' => 'Universitas Padjadjaran',    'major' => 'Ilmu Hukum',         'achievement' => 'Aktif di OSIS semasa sekolah, kini melanjutkan studi hukum di Universitas Padjadjaran.', 'is_published' => 1],
            ['name' => 'Nurul Hidayah Putri',    'graduation_year' => 2020, 'current_institution' => 'UIN Syarif Hidayatullah',    'major' => 'Pendidikan Bahasa Arab', 'achievement' => 'Berhasil menyelesaikan S1 Pendidikan Bahasa Arab dan kini mengabdi sebagai pendidik.', 'is_published' => 1],
            ['name' => 'Bagas Nugraha',          'graduation_year' => 2024, 'current_institution' => 'Universitas Gadjah Mada',    'major' => 'Teknik Sipil',       'achievement' => 'Meraih juara olimpiade matematika sebelum akhirnya lolos ke Teknik Sipil UGM.', 'is_published' => 1],
            ['name' => 'Fatimah Az-Zahra',       'graduation_year' => 2019, 'current_institution' => 'Kementerian Pendidikan RI',  'major' => 'Administrasi Negara', 'achievement' => 'Lulus dengan predikat cumlaude dan kini berkarir di Kementerian Pendidikan RI.', 'is_published' => 1],
            ['name' => 'Muhammad Irfan',         'graduation_year' => 2018, 'current_institution' => 'Wirausaha Mandiri',          'major' => 'Tahfidz Al-Qur\'an', 'achievement' => 'Menyelesaikan hafalan 30 juz semasa di pondok, kini mengelola bisnis kuliner halal di Bogor.', 'is_published' => 1],
            ['name' => 'Dewi Rahayu',            'graduation_year' => 2022, 'current_institution' => 'Startup Teknologi Jakarta',  'major' => 'Teknik Informatika', 'achievement' => 'Berkat program komputer dan coding di sekolah, berhasil berkarir di perusahaan teknologi terkemuka.', 'is_published' => 1],
        ];

        foreach ($alumnisData as $alumni) {
            $existing = DB::table('alumni')->where('name', $alumni['name'])->first();
            if ($existing) {
                $this->command->line("  Skip: {$alumni['name']}");
                continue;
            }
            DB::table('alumni')->insert(array_merge($alumni, [
                'photo'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✓ Alumni di-seed (' . count($alumnisData) . ' item).');
    }
}
