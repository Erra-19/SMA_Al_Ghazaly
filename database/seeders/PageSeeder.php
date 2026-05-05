<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title'        => 'Tentang Kami',
                'slug'         => 'tentang-kami',
                'content'      => '<h2>Sejarah SMA Al Ghazaly</h2><p>SMA Al Ghazaly berdiri sejak tahun 1985, didirikan oleh Yayasan Pendidikan Islam Al Ghazaly sebagai wujud kepedulian terhadap pendidikan Islam yang berkualitas di Kota Bandung.</p><h2>Visi</h2><p>Menjadi sekolah Islam unggulan yang melahirkan generasi berakhlak mulia, cerdas, dan berdaya saing global.</p><h2>Misi</h2><ul><li>Menyelenggarakan pendidikan bermutu berbasis nilai-nilai Islam</li><li>Mengembangkan potensi akademik dan non-akademik siswa</li><li>Membangun lingkungan belajar yang kondusif dan menyenangkan</li><li>Menjalin kerjasama dengan orang tua, masyarakat, dan dunia usaha</li></ul>',
                'is_published' => 1,
                'order'        => 1,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'title'        => 'Fasilitas',
                'slug'         => 'fasilitas',
                'content'      => '<h2>Fasilitas Unggulan</h2><p>SMA Al Ghazaly dilengkapi dengan berbagai fasilitas modern untuk mendukung proses pembelajaran yang optimal.</p><ul><li>Ruang kelas ber-AC dengan LCD proyektor</li><li>Laboratorium IPA terpadu</li><li>Laboratorium Komputer dengan akses internet</li><li>Perpustakaan digital</li><li>Masjid sekolah kapasitas 500 orang</li><li>Lapangan olahraga</li><li>Kantin sehat</li><li>Klinik kesehatan sekolah</li></ul>',
                'is_published' => 1,
                'order'        => 2,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'title'        => 'Ekstrakurikuler',
                'slug'         => 'ekstrakurikuler',
                'content'      => '<h2>Program Ekstrakurikuler</h2><p>Kami menyediakan berbagai program ekstrakurikuler untuk mengembangkan bakat dan minat siswa.</p><h3>Bidang Akademik</h3><ul><li>Olimpiade Matematika</li><li>Olimpiade Fisika</li><li>Debat Bahasa Inggris</li></ul><h3>Bidang Seni & Budaya</h3><ul><li>Nasyid</li><li>Kaligrafi Islam</li><li>Teater</li></ul><h3>Bidang Olahraga</h3><ul><li>Basket</li><li>Futsal</li><li>Pencak Silat</li></ul><h3>Bidang Keislaman</h3><ul><li>Rohani Islam (Rohis)</li><li>Tahfidz Quran</li><li>Arabic Club</li></ul>',
                'is_published' => 1,
                'order'        => 3,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
            [
                'title'        => 'Kontak',
                'slug'         => 'kontak',
                'content'      => '<h2>Hubungi Kami</h2><p><strong>Alamat:</strong> Jl. Raya Al Ghazaly No. 1, Bandung, Jawa Barat 40000</p><p><strong>Telepon:</strong> (022) 1234-5678</p><p><strong>Email:</strong> info@alghazaly.sch.id</p><p><strong>Jam Operasional:</strong> Senin - Jumat, 07.00 - 15.00 WIB</p>',
                'is_published' => 1,
                'order'        => 4,
                'created_at'   => now(),
                'updated_at'   => now(),
            ],
        ];

        foreach ($pages as $page) {
            DB::table('pages')->insertOrIgnore($page);
        }
    }
}
