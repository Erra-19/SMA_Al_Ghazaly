<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $authorId   = DB::table('users')->where('email', 'admin@alghazaly.sch.id')->value('id');
        $beritaId   = DB::table('categories')->where('slug', 'berita')->value('category_id');
        $kegiatanId = DB::table('categories')->where('slug', 'kegiatan')->value('category_id');
        $prestasiId = DB::table('categories')->where('slug', 'prestasi')->value('category_id');
        $ppdbId     = DB::table('categories')->where('slug', 'ppdb')->value('category_id');

        $posts = [
            [
                'title'        => 'Selamat Datang di Website Resmi SMA Al Ghazaly',
                'slug'         => 'selamat-datang-di-website-resmi-sma-al-ghazaly',
                'excerpt'      => 'Alhamdulillah, kami dengan bangga mempersembahkan website resmi SMA Al Ghazaly yang telah hadir dengan tampilan baru.',
                'content'      => '<p>Alhamdulillah, kami dengan bangga mempersembahkan website resmi SMA Al Ghazaly yang telah hadir dengan tampilan baru dan fitur-fitur yang lebih lengkap.</p><p>Website ini hadir sebagai sarana komunikasi antara sekolah dengan orang tua siswa, calon peserta didik baru, dan masyarakat umum.</p>',
                'author_id'    => $authorId,
                'status'       => 'published',
                'published_at' => now()->subDays(30),
                'created_at'   => now()->subDays(30),
                'updated_at'   => now()->subDays(30),
                'cats'         => [$beritaId],
            ],
            [
                'title'        => 'PPDB 2025/2026 Resmi Dibuka',
                'slug'         => 'ppdb-2025-2026-resmi-dibuka',
                'excerpt'      => 'SMA Al Ghazaly membuka PPDB Tahun Ajaran 2025/2026. Daftar sekarang dan raih masa depan gemilang bersama kami.',
                'content'      => '<p>SMA Al Ghazaly dengan bangga mengumumkan pembukaan Penerimaan Peserta Didik Baru (PPDB) Tahun Ajaran 2025/2026.</p><p><strong>Jadwal PPDB:</strong></p><ul><li>Pendaftaran: 1 Maret - 30 Juni 2025</li><li>Seleksi Berkas: 1 - 7 Juli 2025</li><li>Pengumuman: 10 Juli 2025</li><li>Daftar Ulang: 11 - 20 Juli 2025</li></ul><p>Biaya pendaftaran sebesar Rp 500.000. Kuota tersedia untuk 120 siswa baru.</p>',
                'author_id'    => $authorId,
                'status'       => 'published',
                'published_at' => now()->subDays(25),
                'created_at'   => now()->subDays(25),
                'updated_at'   => now()->subDays(25),
                'cats'         => [$ppdbId, $beritaId],
            ],
            [
                'title'        => 'Tim Olimpiade Sains Raih Emas di Tingkat Provinsi',
                'slug'         => 'tim-olimpiade-sains-raih-emas-di-tingkat-provinsi',
                'excerpt'      => 'Tim Olimpiade Sains SMA Al Ghazaly berhasil meraih medali emas pada ajang Olimpiade Sains Provinsi Jawa Barat 2025.',
                'content'      => '<p>Kebanggaan kembali hadir untuk keluarga besar SMA Al Ghazaly. Tim Olimpiade Sains berhasil meraih medali emas pada ajang Olimpiade Sains Provinsi Jawa Barat 2025.</p><p>Tiga siswa terbaik kami, yaitu Farhan Maulana (XII IPA 1), Syifa Aulia (XI IPA 2), dan Rizky Pratama (XI IPA 1) berhasil mengungguli peserta dari 150 sekolah se-Jawa Barat.</p>',
                'author_id'    => $authorId,
                'status'       => 'published',
                'published_at' => now()->subDays(15),
                'created_at'   => now()->subDays(15),
                'updated_at'   => now()->subDays(15),
                'cats'         => [$prestasiId],
            ],
            [
                'title'        => 'Peringatan Maulid Nabi Muhammad SAW 1446 H',
                'slug'         => 'peringatan-maulid-nabi-muhammad-saw-1446-h',
                'excerpt'      => 'SMA Al Ghazaly menyelenggarakan peringatan Maulid Nabi Muhammad SAW 1446 H dengan penuh khidmat dan meriah.',
                'content'      => '<p>SMA Al Ghazaly menyelenggarakan peringatan Maulid Nabi Muhammad SAW 1446 H dengan penuh khidmat dan meriah. Kegiatan ini diikuti oleh seluruh siswa, guru, dan karyawan.</p>',
                'author_id'    => $authorId,
                'status'       => 'published',
                'published_at' => now()->subDays(10),
                'created_at'   => now()->subDays(10),
                'updated_at'   => now()->subDays(10),
                'cats'         => [$kegiatanId],
            ],
            [
                'title'        => 'Kegiatan Pesantren Kilat Ramadhan 2025',
                'slug'         => 'kegiatan-pesantren-kilat-ramadhan-2025',
                'excerpt'      => 'SMA Al Ghazaly mengadakan program Pesantren Kilat Ramadhan 2025 selama 3 hari untuk memperdalam ilmu agama.',
                'content'      => '<p>Dalam rangka menyambut bulan suci Ramadhan, SMA Al Ghazaly kembali mengadakan program Pesantren Kilat yang akan dilaksanakan selama 3 hari penuh dengan berbagai kegiatan keislaman yang bermanfaat.</p>',
                'author_id'    => $authorId,
                'status'       => 'published',
                'published_at' => now()->subDays(5),
                'created_at'   => now()->subDays(5),
                'updated_at'   => now()->subDays(5),
                'cats'         => [$kegiatanId],
            ],
            [
                'title'        => 'Rencana Pembangunan Gedung Baru Laboratorium',
                'slug'         => 'rencana-pembangunan-gedung-baru-laboratorium',
                'excerpt'      => 'Rencana pembangunan gedung laboratorium terpadu SMA Al Ghazaly.',
                'content'      => '<p>Draft artikel mengenai rencana pembangunan gedung laboratorium terpadu yang akan segera diumumkan kepada publik.</p>',
                'author_id'    => $authorId,
                'status'       => 'draft',
                'published_at' => null,
                'created_at'   => now()->subDays(2),
                'updated_at'   => now()->subDays(2),
                'cats'         => [$beritaId],
            ],
        ];

        foreach ($posts as $post) {
            $cats = $post['cats'];
            unset($post['cats']);

            $postId = DB::table('posts')->insertGetId($post);

            foreach ($cats as $catId) {
                DB::table('post_categories')->insertOrIgnore([
                    'post_id'     => $postId,
                    'category_id' => $catId,
                ]);
            }
        }
    }
}
