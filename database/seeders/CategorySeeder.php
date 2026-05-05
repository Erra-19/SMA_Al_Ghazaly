<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Berita',          'slug' => 'berita',          'parent_id' => null, 'created_at' => now()],
            ['category_name' => 'Pengumuman',      'slug' => 'pengumuman',      'parent_id' => null, 'created_at' => now()],
            ['category_name' => 'Kegiatan',        'slug' => 'kegiatan',        'parent_id' => null, 'created_at' => now()],
            ['category_name' => 'Prestasi',        'slug' => 'prestasi',        'parent_id' => null, 'created_at' => now()],
            ['category_name' => 'Ekstrakurikuler', 'slug' => 'ekstrakurikuler', 'parent_id' => null, 'created_at' => now()],
            ['category_name' => 'PPDB',            'slug' => 'ppdb',            'parent_id' => null, 'created_at' => now()],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insertOrIgnore($cat);
        }

        // Sub-kategori dari Kegiatan
        $kegiatanId = DB::table('categories')->where('slug', 'kegiatan')->value('category_id');
        $subCategories = [
            ['category_name' => 'Kegiatan Akademik',     'slug' => 'kegiatan-akademik',     'parent_id' => $kegiatanId, 'created_at' => now()],
            ['category_name' => 'Kegiatan Non-Akademik', 'slug' => 'kegiatan-non-akademik', 'parent_id' => $kegiatanId, 'created_at' => now()],
            ['category_name' => 'Kegiatan Keagamaan',    'slug' => 'kegiatan-keagamaan',    'parent_id' => $kegiatanId, 'created_at' => now()],
        ];

        foreach ($subCategories as $sub) {
            DB::table('categories')->insertOrIgnore($sub);
        }
    }
}
