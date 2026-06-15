<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AlbumSeeder extends Seeder
{
    public function run(): void
    {
        $albums = [
            [
                'title'        => 'Wisuda Angkatan 2025',
                'description'  => 'Momen bahagia upacara wisuda dan pelepasan siswa kelas XII angkatan 2025.',
                'is_published' => 1,
                'order'        => 1,
            ],
            [
                'title'        => 'Kegiatan OSIS & MPK 2024/2025',
                'description'  => 'Dokumentasi berbagai kegiatan organisasi OSIS dan MPK sepanjang tahun ajaran 2024/2025.',
                'is_published' => 1,
                'order'        => 2,
            ],
            [
                'title'        => 'Pesantren Kilat Ramadhan 1446H',
                'description'  => 'Kegiatan pesantren kilat dan kajian Ramadhan yang diikuti seluruh siswa.',
                'is_published' => 1,
                'order'        => 3,
            ],
            [
                'title'        => 'Olimpiade Sains Nasional 2025',
                'description'  => 'Perjalanan siswa-siswi Al-Ghazaly meraih medali di OSN tingkat Provinsi Jawa Barat.',
                'is_published' => 1,
                'order'        => 4,
            ],
            [
                'title'        => 'Masa Orientasi Siswa Baru 2025',
                'description'  => 'Kegiatan MOS untuk menyambut dan mengenalkan lingkungan sekolah kepada siswa baru.',
                'is_published' => 1,
                'order'        => 5,
            ],
            [
                'title'        => 'Pertandingan Olahraga Antar Kelas',
                'description'  => 'Kompetisi olahraga tahunan antar kelas yang mempererat persaudaraan dan sportivitas.',
                'is_published' => 1,
                'order'        => 6,
            ],
        ];

        foreach ($albums as $album) {
            $slug = Str::slug($album['title']);
            $existing = DB::table('albums')->where('slug', $slug)->first();
            if ($existing) {
                $this->command->line("  Skip: {$album['title']}");
                continue;
            }

            DB::table('albums')->insert(array_merge($album, [
                'slug'       => $slug,
                'cover'      => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        $this->command->info('✓ Albums di-seed (' . count($albums) . ' item). Tambahkan foto via Admin → Album → Kelola Foto.');
    }
}
