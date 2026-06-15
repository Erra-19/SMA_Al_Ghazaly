<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            [
                'name'        => 'Masjid Al-Ghazaly',
                'category'    => 'ibadah-sosial',
                'short_desc'  => 'Masjid utama sekolah berkapasitas 500 jamaah untuk kegiatan sholat berjamaah dan kajian keislaman.',
                'long_desc'   => 'Masjid Al-Ghazaly merupakan pusat kegiatan keislaman di lingkungan sekolah. Dengan arsitektur islami yang megah dan kapasitas 500 jamaah, masjid ini menjadi tempat sholat berjamaah lima waktu, kajian rutin, serta berbagai kegiatan keagamaan lainnya.',
                'capacity'    => '500 Jamaah',
                'location'    => 'Gedung Utama, Lantai 1',
                'specs'       => json_encode(['AC sentral', 'Sound system', 'Area wudhu luas', 'Perpustakaan Islam mini']),
                'is_featured' => 1,
                'is_published'=> 1,
                'order'       => 1,
            ],
            [
                'name'        => 'Laboratorium Komputer',
                'category'    => 'akademik',
                'short_desc'  => 'Lab komputer modern dengan 40 unit PC terbaru dan koneksi internet berkecepatan tinggi.',
                'long_desc'   => 'Laboratorium komputer dilengkapi dengan 40 unit komputer spesifikasi terkini yang terhubung dengan jaringan internet berkecepatan tinggi. Digunakan untuk pembelajaran TIK, coding, desain grafis, dan ujian berbasis komputer.',
                'capacity'    => '40 Siswa',
                'location'    => 'Gedung B, Lantai 2',
                'specs'       => json_encode(['40 unit PC Core i5', 'Internet 1 Gbps', 'AC & proyektor', 'Software desain & coding']),
                'is_featured' => 1,
                'is_published'=> 1,
                'order'       => 2,
            ],
            [
                'name'        => 'Laboratorium IPA',
                'category'    => 'akademik',
                'short_desc'  => 'Tiga laboratorium IPA terintegrasi untuk Fisika, Kimia, dan Biologi dengan peralatan lengkap.',
                'long_desc'   => 'Fasilitas laboratorium IPA terdiri dari tiga ruang terpisah untuk Fisika, Kimia, dan Biologi yang dilengkapi peralatan eksperimen standar nasional. Mendukung pembelajaran sains berbasis praktikum dan persiapan olimpiade sains.',
                'capacity'    => '36 Siswa per Lab',
                'location'    => 'Gedung C, Lantai 1',
                'specs'       => json_encode(['Alat eksperimen lengkap', 'Lemari penyimpanan reagen', 'Safety equipment', 'Exhaust fan & ventilasi']),
                'is_featured' => 1,
                'is_published'=> 1,
                'order'       => 3,
            ],
            [
                'name'        => 'Perpustakaan',
                'category'    => 'akademik',
                'short_desc'  => 'Perpustakaan modern dengan koleksi 10.000+ buku, akses e-library, dan ruang baca nyaman.',
                'long_desc'   => 'Perpustakaan SMA Al-Ghazaly memiliki koleksi lebih dari 10.000 judul buku yang mencakup buku pelajaran, buku islami, ensiklopedia, dan karya sastra. Dilengkapi dengan sistem katalog digital dan akses e-library untuk mendukung literasi siswa.',
                'capacity'    => '60 Siswa',
                'location'    => 'Gedung A, Lantai 2',
                'specs'       => json_encode(['10.000+ koleksi buku', 'Area baca ber-AC', 'Komputer katalog digital', 'Ruang diskusi']),
                'is_featured' => 1,
                'is_published'=> 1,
                'order'       => 4,
            ],
            [
                'name'        => 'Lapangan Olahraga',
                'category'    => 'olahraga-seni',
                'short_desc'  => 'Lapangan multifungsi untuk basket, voli, badminton, dan upacara bendera.',
                'long_desc'   => 'Lapangan olahraga terpadu yang dapat digunakan untuk berbagai cabang olahraga termasuk basket, voli, dan badminton. Juga digunakan sebagai area upacara bendera dan kegiatan outdoor sekolah.',
                'capacity'    => '200 Siswa',
                'location'    => 'Area Tengah Sekolah',
                'specs'       => json_encode(['Lapangan basket standar', 'Net voli permanen', 'Tribun penonton', 'Lampu penerangan']),
                'is_featured' => 1,
                'is_published'=> 1,
                'order'       => 5,
            ],
            [
                'name'        => 'Asrama Putra & Putri',
                'category'    => 'kesejahteraan',
                'short_desc'  => 'Asrama boarding school terpisah putra-putri dengan fasilitas lengkap dan pembinaan 24 jam.',
                'long_desc'   => 'Fasilitas asrama tersedia untuk siswa boarding school dengan pemisahan gedung putra dan putri. Dilengkapi kamar tidur ber-AC, ruang belajar bersama, dan fasilitas mandi yang memadai. Dibimbing oleh musyrif/musyrifah yang tinggal di asrama.',
                'capacity'    => '150 Siswa',
                'location'    => 'Gedung Asrama, Area Belakang',
                'specs'       => json_encode(['Kamar ber-AC', 'Ruang belajar bersama', 'Dapur & ruang makan', 'CCTV 24 jam']),
                'is_featured' => 1,
                'is_published'=> 1,
                'order'       => 6,
            ],
            [
                'name'        => 'Aula Serba Guna',
                'category'    => 'kesejahteraan',
                'short_desc'  => 'Aula berkapasitas 800 orang untuk acara wisuda, seminar, pertunjukan seni, dan kegiatan besar.',
                'long_desc'   => 'Aula serbaguna Al-Ghazaly merupakan ruang pertemuan utama dengan kapasitas 800 orang. Dilengkapi panggung, sistem suara profesional, dan pencahayaan artistik. Digunakan untuk wisuda, seminar, pentas seni, dan berbagai kegiatan besar sekolah.',
                'capacity'    => '800 Orang',
                'location'    => 'Gedung Serbaguna',
                'specs'       => json_encode(['Panggung permanen', 'Sound system profesional', 'Lighting artistik', 'AC & kursi lipat']),
                'is_featured' => 1,
                'is_published'=> 1,
                'order'       => 7,
            ],
            [
                'name'        => 'Klinik Kesehatan Sekolah',
                'category'    => 'kesejahteraan',
                'short_desc'  => 'Klinik kesehatan sekolah dengan tenaga medis terlatih yang siap melayani siswa setiap hari.',
                'long_desc'   => 'Klinik kesehatan sekolah beroperasi setiap hari kerja dengan tenaga medis terlatih. Melayani pemeriksaan kesehatan rutin, pertolongan pertama, dan rujukan ke fasilitas kesehatan lebih lanjut jika diperlukan.',
                'capacity'    => 'Tersedia setiap hari',
                'location'    => 'Gedung A, Lantai 1',
                'specs'       => json_encode(['Tenaga medis terlatih', 'Obat-obatan dasar', 'Peralatan P3K', 'Tempat istirahat']),
                'is_featured' => 1,
                'is_published'=> 1,
                'order'       => 8,
            ],
        ];

        foreach ($facilities as $facility) {
            $existing = DB::table('facilities')->where('name', $facility['name'])->first();
            if ($existing) {
                $this->command->line("  Skip: {$facility['name']}");
                continue;
            }
            DB::table('facilities')->insert(array_merge($facility, [
                'slug'       => Str::slug($facility['name']),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✓ Facilities di-seed (' . count($facilities) . ' item).');
    }
}
