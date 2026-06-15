<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'name'            => 'Ahmad Fauzan',
                'role'            => 'Alumni 2022 — Mahasiswa Teknik Informatika ITB',
                'university'      => 'Institut Teknologi Bandung',
                'major'           => 'Teknik Informatika',
                'graduation_year' => 2022,
                'content'         => 'SMA Al-Ghazaly membentuk saya menjadi pribadi yang tidak hanya cerdas secara akademik, tapi juga kuat secara spiritual. Program tahfidz dan lab komputer yang lengkap sangat membantu saya lolos seleksi ITB.',
                'rating'          => 5,
                'is_published'    => 1,
                'order'           => 1,
            ],
            [
                'name'            => 'Siti Rahmawati',
                'role'            => 'Alumni 2021 — Dokter Muda FK UI',
                'university'      => 'Universitas Indonesia',
                'major'           => 'Kedokteran',
                'graduation_year' => 2021,
                'content'         => 'Fondasi ilmu sains yang kuat dan akhlak yang ditanamkan di Al-Ghazaly sangat terasa ketika saya kuliah di FK UI. Guru-guru di sini benar-benar mendedikasikan diri untuk masa depan kami.',
                'rating'          => 5,
                'is_published'    => 1,
                'order'           => 2,
            ],
            [
                'name'            => 'Muhammad Rizky Pratama',
                'role'            => 'Alumni 2023 — Mahasiswa Hukum UNPAD',
                'university'      => 'Universitas Padjadjaran',
                'major'           => 'Ilmu Hukum',
                'graduation_year' => 2023,
                'content'         => 'Lingkungan islami di Al-Ghazaly membuat saya tumbuh dengan nilai-nilai integritas yang kuat. Organisasi OSIS melatih jiwa kepemimpinan saya sejak dini. Sangat bangga menjadi alumni sekolah ini.',
                'rating'          => 5,
                'is_published'    => 1,
                'order'           => 3,
            ],
            [
                'name'            => 'Nurul Hidayah',
                'role'            => 'Alumni 2020 — Guru Bahasa Arab MAN 1 Bogor',
                'university'      => 'UIN Syarif Hidayatullah Jakarta',
                'major'           => 'Pendidikan Bahasa Arab',
                'graduation_year' => 2020,
                'content'         => 'Program bahasa Arab intensif di Al-Ghazaly menjadi bekal terbaik saya. Kini saya bisa mengabdikan ilmu tersebut sebagai guru. Terima kasih Al-Ghazaly atas segala ilmu dan didikan yang diberikan.',
                'rating'          => 5,
                'is_published'    => 1,
                'order'           => 4,
            ],
            [
                'name'            => 'Bagas Aditya Nugraha',
                'role'            => 'Alumni 2024 — Mahasiswa Teknik Sipil UGM',
                'university'      => 'Universitas Gadjah Mada',
                'major'           => 'Teknik Sipil',
                'graduation_year' => 2024,
                'content'         => 'Bimbingan belajar intensif menjelang SNBT benar-benar membantu saya lolos ke UGM. Fasilitas dan guru-guru di Al-Ghazaly adalah yang terbaik. Saya sangat merekomendasikan sekolah ini untuk adik-adik SMP.',
                'rating'          => 5,
                'is_published'    => 1,
                'order'           => 5,
            ],
        ];

        foreach ($testimonials as $data) {
            $existing = DB::table('testimonials')->where('name', $data['name'])->first();
            if ($existing) {
                $this->command->line("  Skip: {$data['name']}");
                continue;
            }
            DB::table('testimonials')->insert(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✓ Testimonials di-seed (' . count($testimonials) . ' item).');
    }
}
