<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'site_name',        'value' => 'SMA Al Ghazaly'],
            ['key' => 'site_tagline',     'value' => 'Berakhlak Mulia, Berprestasi Gemilang'],
            ['key' => 'site_description', 'value' => 'SMA Al Ghazaly adalah sekolah menengah atas Islam unggulan yang berkomitmen membentuk generasi berakhlak mulia dan berprestasi.'],
            ['key' => 'site_email',       'value' => 'info@alghazaly.sch.id'],
            ['key' => 'site_phone',       'value' => '(022) 1234-5678'],
            ['key' => 'site_address',     'value' => 'Jl. Raya Al Ghazaly No. 1, Bandung, Jawa Barat'],
            ['key' => 'site_logo',        'value' => ''],
            ['key' => 'site_favicon',     'value' => ''],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/sma_alghazaly'],
            ['key' => 'social_facebook',  'value' => 'https://facebook.com/smaalghazaly'],
            ['key' => 'social_youtube',   'value' => 'https://youtube.com/@smaalghazaly'],
            ['key' => 'social_twitter',   'value' => ''],
            ['key' => 'ppdb_open',        'value' => 'true'],
            ['key' => 'ppdb_year',        'value' => '2025/2026'],
            ['key' => 'ppdb_quota',       'value' => '120'],
            ['key' => 'ppdb_fee',         'value' => '500000'],
            ['key' => 'ppdb_deadline',    'value' => '2025-06-30'],
            ['key' => 'about_vision',     'value' => 'Menjadi sekolah Islam unggulan yang melahirkan generasi berakhlak mulia, cerdas, dan berdaya saing global.'],
            ['key' => 'about_mission',    'value' => "1. Menyelenggarakan pendidikan bermutu berbasis nilai-nilai Islam\n2. Mengembangkan potensi akademik dan non-akademik siswa\n3. Membangun lingkungan belajar yang kondusif dan menyenangkan\n4. Menjalin kerjasama dengan orang tua, masyarakat, dan dunia usaha"],
            ['key' => 'about_history',    'value' => 'SMA Al Ghazaly berdiri sejak tahun 1985, didirikan oleh Yayasan Pendidikan Islam Al Ghazaly sebagai wujud kepedulian terhadap pendidikan Islam yang berkualitas.'],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->insertOrIgnore($setting);
        }
    }
}
