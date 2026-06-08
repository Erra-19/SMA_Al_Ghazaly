<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Berita Sekolah',
            'Pengumuman',
            'Prestasi',
            'Kegiatan',
            'PPDB',
            'Artikel',
        ];

        foreach ($categories as $categoryName) {
            DB::table('categories')->updateOrInsert(
                [
                    'slug' => Str::slug($categoryName),
                ],
                [
                    'category_name' => $categoryName,
                    'parent_id' => null,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
