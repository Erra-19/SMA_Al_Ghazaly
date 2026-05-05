<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        $alumni = [
            ['name' => 'Aditya Prasetyo',       'graduation_year' => 2023, 'current_institution' => 'Institut Teknologi Bandung',      'major' => 'Teknik Informatika',      'achievement' => 'Diterima di ITB melalui jalur SNBP, aktif dalam komunitas open source.',                              'is_published' => 1, 'created_at' => now()],
            ['name' => 'Nabila Zahra Putri',    'graduation_year' => 2022, 'current_institution' => 'Universitas Padjadjaran',          'major' => 'Kedokteran',              'achievement' => 'Lulus SNBT dengan nilai tertinggi angkatannya, bercita-cita menjadi dokter spesialis anak.',          'is_published' => 1, 'created_at' => now()],
            ['name' => 'Muhammad Faiz Ramadhan', 'graduation_year' => 2024, 'current_institution' => 'Universitas Indonesia',           'major' => 'Ilmu Hukum',              'achievement' => 'Diterima di Fakultas Hukum UI, aktif dalam organisasi mahasiswa dan kegiatan bantuan hukum.',       'is_published' => 1, 'created_at' => now()],
            ['name' => 'Rizka Amelia Sari',     'graduation_year' => 2023, 'current_institution' => 'Universitas Diponegoro',           'major' => 'Arsitektur',              'achievement' => 'Meraih beasiswa berprestasi dan aktif dalam kompetisi desain arsitektur nasional.',                  'is_published' => 1, 'created_at' => now()],
            ['name' => 'Dimas Kurniawan',       'graduation_year' => 2021, 'current_institution' => 'Gojek Indonesia',                 'major' => 'Software Engineer',       'achievement' => 'Lulus dari Telkom University dan kini bekerja sebagai Software Engineer di Gojek.',                'is_published' => 1, 'created_at' => now()],
            ['name' => 'Annisa Fitriani',       'graduation_year' => 2020, 'current_institution' => 'RSUP Hasan Sadikin Bandung',      'major' => 'Dokter Internship',       'achievement' => 'Lulus cumlaude dari Fakultas Kedokteran UNPAD angkatan 2020.',                                      'is_published' => 1, 'created_at' => now()],
            ['name' => 'Yusuf Hidayatullah',    'graduation_year' => 2022, 'current_institution' => 'Universitas Padjadjaran',          'major' => 'Ilmu Komunikasi',         'achievement' => 'Aktif sebagai jurnalis kampus dan content creator edukasi Islam dengan 50K followers.',             'is_published' => 1, 'created_at' => now()],
            ['name' => 'Salsabila Ramadhani',   'graduation_year' => 2021, 'current_institution' => 'Wirausaha Mandiri',               'major' => 'Fashion Muslim',          'achievement' => 'Mendirikan brand fashion muslim lokal yang kini telah menembus pasar Malaysia dan Brunei.',          'is_published' => 1, 'created_at' => now()],
        ];

        foreach ($alumni as $a) {
            DB::table('alumni')->insertOrIgnore($a);
        }
    }
}
