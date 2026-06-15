<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $programs = [
            [
                'title'       => 'Tahfidz Al-Qur\'an',
                'subtitle'    => 'Program Hafalan Intensif',
                'description' => 'Program hafalan Al-Qur\'an terstruktur dengan target minimal 2 juz per tahun, dibimbing oleh hafidz/hafidzah berpengalaman.',
                'type'        => 'unggulan',
                'icon'        => 'BookOpen',
                'badge'       => 'Unggulan',
                'stats'       => '2 Juz/Tahun',
                'features'    => json_encode(['Setoran hafalan harian', 'Ujian tahfidz berkala', 'Wisuda Al-Qur\'an tahunan', 'Pembimbing hafidz bersertifikat']),
                'order'       => 1,
                'is_published' => 1,
            ],
            [
                'title'       => 'Sains & Teknologi',
                'subtitle'    => 'Laboratorium Modern',
                'description' => 'Pembelajaran sains berbasis eksperimen dengan laboratorium lengkap untuk Fisika, Kimia, Biologi, dan Komputer.',
                'type'        => 'unggulan',
                'icon'        => 'Flask',
                'badge'       => 'IPTEK',
                'stats'       => '4 Lab Aktif',
                'features'    => json_encode(['Lab Fisika & Kimia', 'Lab Biologi', 'Lab Komputer & IT', 'Olimpiade Sains rutin']),
                'order'       => 2,
                'is_published' => 1,
            ],
            [
                'title'       => 'Bahasa Internasional',
                'subtitle'    => 'Bahasa Arab & Inggris',
                'description' => 'Program intensif Bahasa Arab dan Bahasa Inggris untuk mempersiapkan siswa bersaing di kancah global.',
                'type'        => 'unggulan',
                'icon'        => 'Globe',
                'badge'       => 'Internasional',
                'stats'       => '2 Bahasa',
                'features'    => json_encode(['Kelas percakapan harian', 'Ujian TOEFL/IELTS preparation', 'Pertukaran budaya', 'Native speaker session']),
                'order'       => 3,
                'is_published' => 1,
            ],
            [
                'title'       => 'Kepemimpinan & Organisasi',
                'subtitle'    => 'OSIS & MPK Aktif',
                'description' => 'Pembentukan jiwa kepemimpinan melalui organisasi OSIS, MPK, dan berbagai kegiatan ekstrakurikuler.',
                'type'        => 'ekskul',
                'icon'        => 'Users',
                'badge'       => 'Karakter',
                'stats'       => '20+ Ekskul',
                'features'    => json_encode(['OSIS & MPK aktif', 'Pramuka wajib', 'PMR & Pecinta Alam', 'Seni & Olahraga']),
                'order'       => 4,
                'is_published' => 1,
            ],
            [
                'title'       => 'Boarding School',
                'subtitle'    => 'Asrama Putra & Putri',
                'description' => 'Program asrama dengan pembinaan 24 jam untuk membentuk karakter islami dan kemandirian siswa.',
                'type'        => 'boarding',
                'icon'        => 'Home',
                'badge'       => 'Boarding',
                'stats'       => '24 Jam/Hari',
                'features'    => json_encode(['Pembinaan 24 jam', 'Sholat berjamaah wajib', 'Makan bergizi 3x sehari', 'Pengawasan terpadu']),
                'order'       => 5,
                'is_published' => 1,
            ],
            [
                'title'       => 'Persiapan PTN',
                'subtitle'    => 'Bimbingan Masuk Universitas',
                'description' => 'Program khusus kelas XII untuk mempersiapkan siswa menghadapi SNBT, SNBP, dan ujian masuk PTN lainnya.',
                'type'        => 'akademik',
                'icon'        => 'GraduationCap',
                'badge'       => 'Akademik',
                'stats'       => '80% Lolos PTN',
                'features'    => json_encode(['Tryout rutin mingguan', 'Bimbingan intensif guru senior', 'Analisis soal SNBT', 'Konseling jurusan']),
                'order'       => 6,
                'is_published' => 1,
            ],
        ];

        foreach ($programs as $program) {
            $existing = DB::table('programs')->where('title', $program['title'])->first();
            if ($existing) {
                $this->command->line("  Skip: {$program['title']}");
                continue;
            }
            DB::table('programs')->insert(array_merge($program, [
                'slug'       => Str::slug($program['title']),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✓ Programs di-seed (' . count($programs) . ' item).');
    }
}
