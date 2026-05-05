<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['name' => 'Aditya Prasetyo',      'role' => 'Alumni 2023 — Mahasiswa Teknik Informatika ITB',       'content' => 'SMA Al Ghazaly membentuk saya menjadi pribadi yang tidak hanya cerdas secara akademik, tapi juga kuat secara karakter dan iman. Alhamdulillah bisa tembus ITB berkat bimbingan guru-guru terbaik di sini.',          'rating' => 5, 'is_published' => 1, 'order' => 1, 'created_at' => now()],
            ['name' => 'Nabila Zahra Putri',   'role' => 'Alumni 2022 — Mahasiswi Kedokteran UNPAD',             'content' => 'Lingkungan islami di Al Ghazaly sangat mendukung perkembangan diri. Saya belajar untuk disiplin, bertanggung jawab, dan selalu bersemangat. Terima kasih Al Ghazaly!',                                                   'rating' => 5, 'is_published' => 1, 'order' => 2, 'created_at' => now()],
            ['name' => 'Muhammad Faiz Ramadhan','role' => 'Alumni 2024 — Mahasiswa Hukum UI',                    'content' => 'PPDB di Al Ghazaly adalah keputusan terbaik orang tua saya. Proses pembelajaran yang menyenangkan dan guru-guru yang peduli membuat saya semangat belajar setiap harinya.',                                           'rating' => 5, 'is_published' => 1, 'order' => 3, 'created_at' => now()],
            ['name' => 'Ibu Kartini Wulandari', 'role' => 'Orang Tua Siswa Kelas XI',                            'content' => 'Sebagai orang tua, saya sangat puas dengan perkembangan anak saya selama di SMA Al Ghazaly. Tidak hanya prestasi akademiknya yang meningkat, akhlaknya pun semakin baik.',                                            'rating' => 5, 'is_published' => 1, 'order' => 4, 'created_at' => now()],
            ['name' => 'Rizka Amelia Sari',    'role' => 'Alumni 2023 — Mahasiswi Arsitektur UNDIP',             'content' => 'Ekstrakulikuler di Al Ghazaly sangat beragam dan berkualitas. Saya mengembangkan bakat desain saya di sini, yang akhirnya membawa saya masuk ke jurusan arsitektur impian.',                                          'rating' => 4, 'is_published' => 1, 'order' => 5, 'created_at' => now()],
        ];

        foreach ($testimonials as $t) {
            DB::table('testimonials')->insertOrIgnore($t);
        }
    }
}
