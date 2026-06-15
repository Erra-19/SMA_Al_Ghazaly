<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $hasPivot = Schema::hasTable('post_categories');

        $posts = [
            [
                'title'       => 'SMA Al-Ghazaly Raih Juara 1 Olimpiade Sains Nasional Tingkat Provinsi',
                'type'        => 'news',
                'category'    => 'Prestasi',
                'summary'     => 'Tiga siswa SMA Al-Ghazaly berhasil meraih medali emas pada ajang OSN tingkat Provinsi Jawa Barat tahun ini.',
                'content'     => '<p>SMA Al-Ghazaly Bogor kembali menorehkan prestasi gemilang pada ajang Olimpiade Sains Nasional (OSN) tingkat Provinsi Jawa Barat. Tiga siswa terbaik berhasil membawa pulang medali emas sekaligus mewakili provinsi ke tingkat nasional.</p><p>Keberhasilan ini merupakan buah dari program pembinaan intensif yang telah berjalan selama dua semester penuh.</p>',
                'post_status' => 'Penting',
                'is_published'=> 1,
            ],
            [
                'title'       => 'Penerimaan Peserta Didik Baru (PPDB) 2026/2027 Resmi Dibuka',
                'type'        => 'news',
                'category'    => 'PPDB',
                'summary'     => 'PPDB tahun ajaran 2026/2027 resmi dibuka. Pendaftaran dapat dilakukan secara online melalui website sekolah.',
                'content'     => '<p>SMA Al-Ghazaly Bogor mengumumkan pembukaan resmi PPDB untuk tahun ajaran 2026/2027. Kuota yang tersedia adalah 160 siswa yang akan dibagi ke dalam 4 rombongan belajar.</p>',
                'post_status' => 'Penting',
                'is_published'=> 1,
            ],
            [
                'title'       => 'Kegiatan Pesantren Kilat Ramadhan 1447H',
                'type'        => 'news',
                'category'    => 'Kegiatan',
                'summary'     => 'Pesantren kilat Ramadhan tahun ini diikuti oleh seluruh siswa SMA Al-Ghazaly dengan berbagai program keislaman.',
                'content'     => '<p>Memasuki bulan suci Ramadhan 1447H, SMA Al-Ghazaly menggelar kegiatan pesantren kilat yang diikuti oleh seluruh siswa dari kelas X hingga XII. Program ini mencakup kajian Al-Qur\'an, fiqih ibadah, dan penguatan akhlak islami.</p>',
                'post_status' => 'Acara akan Datang',
                'is_published'=> 1,
            ],
            [
                'title'       => 'Kurikulum Berbasis Al-Qur\'an: Pendekatan Holistik di Era Digital',
                'type'        => 'article',
                'category'    => 'Artikel',
                'summary'     => 'Bagaimana SMA Al-Ghazaly mengintegrasikan nilai-nilai Al-Qur\'an dalam setiap mata pelajaran untuk mencetak generasi unggul.',
                'content'     => '<p>Dalam era digitalisasi pendidikan, SMA Al-Ghazaly Bogor tetap berkomitmen mengintegrasikan nilai-nilai Al-Qur\'an sebagai pondasi dalam setiap aspek pembelajaran. Pendekatan ini terbukti menghasilkan lulusan yang tidak hanya cerdas secara akademik, tetapi juga berakhlak mulia.</p>',
                'post_status' => 'Acara akan Datang',
                'is_published'=> 1,
            ],
            [
                'title'          => 'Upacara Wisuda & Pelepasan Siswa Kelas XII Angkatan 2026',
                'type'           => 'event',
                'category'       => 'Kegiatan',
                'summary'        => 'Upacara wisuda dan pelepasan siswa kelas XII akan digelar pada akhir Juni 2026 di Gedung Serbaguna Sekolah.',
                'content'        => '<p>SMA Al-Ghazaly akan menggelar upacara wisuda dan pelepasan siswa kelas XII angkatan 2026 pada akhir bulan Juni.</p>',
                'post_status'    => 'Acara akan Datang',
                'is_published'   => 1,
                'event_start_at' => now()->addDays(20),
                'event_location' => 'Gedung Serbaguna SMA Al-Ghazaly',
            ],
            [
                'title'       => 'Pengumuman Jadwal Ujian Akhir Semester Genap 2025/2026',
                'type'        => 'news',
                'category'    => 'Pengumuman',
                'summary'     => 'Jadwal Ujian Akhir Semester Genap telah ditetapkan. Siswa diminta mempersiapkan diri dengan baik.',
                'content'     => '<p>Sesuai dengan kalender akademik, Ujian Akhir Semester (UAS) Genap tahun pelajaran 2025/2026 akan dilaksanakan mulai tanggal 10 Juni 2026.</p>',
                'post_status' => 'Penting',
                'is_published'=> 1,
            ],
        ];

        foreach ($posts as $data) {
            $categoryName = $data['category'];

            $slug = Str::slug($data['title']);
            if (DB::table('posts')->where('slug', $slug)->exists()) {
                $this->command->line("  Skip: {$data['title']}");
                continue;
            }

            $postId = DB::table('posts')->insertGetId(array_merge($data, [
                'slug'       => $slug,
                'created_at' => now(),
                'updated_at' => now(),
            ]));

            if ($hasPivot && $postId) {
                $categoryId = DB::table('categories')->where('category_name', $categoryName)->value('category_id');
                if ($categoryId) {
                    DB::table('post_categories')->insertOrIgnore([
                        'post_id'     => $postId,
                        'category_id' => $categoryId,
                    ]);
                }
            }
        }

        $this->command->info('✓ Posts di-seed (' . count($posts) . ' item).');
    }
}
