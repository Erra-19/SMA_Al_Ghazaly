<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $teachers = [
            ['name' => 'Drs. H. Ahmad Fauzi, M.Pd',   'position' => 'Kepala Sekolah',   'subject' => null,                     'bio' => 'Kepala SMA Al Ghazaly dengan pengalaman lebih dari 20 tahun di dunia pendidikan Islam.', 'order' => 1, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hj. Siti Rahmawati, S.Pd',    'position' => 'Guru',             'subject' => 'Matematika',             'bio' => 'Guru Matematika berpengalaman dengan metode pembelajaran kreatif dan inovatif.', 'order' => 2, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ir. Bambang Wibowo, M.Si',    'position' => 'Guru',             'subject' => 'Fisika',                 'bio' => 'Lulusan ITB yang mendedikasikan diri untuk pendidikan sains di sekolah Islam.', 'order' => 3, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dr. Nur Hidayah, M.Ag',       'position' => 'Guru',             'subject' => 'Pendidikan Agama Islam', 'bio' => 'Doktor dalam bidang Ilmu Agama Islam, aktif dalam kegiatan dakwah dan pendidikan.', 'order' => 4, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rini Kusumawati, S.S, M.Pd',  'position' => 'Guru',             'subject' => 'Bahasa Indonesia',       'bio' => 'Pecinta sastra yang membimbing siswa untuk mencintai bahasa dan budaya Indonesia.', 'order' => 5, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Michael Tanaka, S.Pd',        'position' => 'Guru',             'subject' => 'Bahasa Inggris',         'bio' => 'TESOL certified teacher dengan pengalaman mengajar di luar negeri selama 5 tahun.', 'order' => 6, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dra. Fatimah Azzahra',        'position' => 'Guru',             'subject' => 'Kimia',                  'bio' => 'Guru Kimia yang dikenal dengan metode praktikum yang menyenangkan dan mudah dipahami.', 'order' => 7, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'H. Ridwan Effendi, S.E, M.M', 'position' => 'Guru',             'subject' => 'Ekonomi',                'bio' => 'Praktisi bisnis yang berbagi pengalaman nyata dalam pembelajaran ekonomi.', 'order' => 8, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Drs. Agus Hermanto',          'position' => 'Wakil Kepala Bid. Kurikulum', 'subject' => 'Biologi',    'bio' => 'Berpengalaman dalam pengembangan kurikulum sekolah berbasis karakter islami.', 'order' => 9, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hj. Nurul Khasanah, S.Pd',   'position' => 'Wakil Kepala Bid. Kesiswaan', 'subject' => 'PKn',        'bio' => 'Pembimbing OSIS dan kegiatan kesiswaan dengan dedikasi tinggi.', 'order' => 10, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now()],
        ];

        foreach ($teachers as $teacher) {
            DB::table('teachers')->insertOrIgnore($teacher);
        }
    }
}
