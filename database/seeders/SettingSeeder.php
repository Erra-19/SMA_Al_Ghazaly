<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'school_name',
                'value' => 'SMA Al Ghazaly',
                'type' => 'text',
                'group' => 'general',
                'is_public' => true,
            ],
            [
                'key' => 'school_logo',
                'value' => null,
                'type' => 'image',
                'group' => 'general',
                'is_public' => true,
            ],
            [
                'key' => 'school_address',
                'value' => 'Alamat sekolah belum diatur.',
                'type' => 'textarea',
                'group' => 'contact',
                'is_public' => true,
            ],
            [
                'key' => 'school_phone',
                'value' => null,
                'type' => 'phone',
                'group' => 'contact',
                'is_public' => true,
            ],
            [
                'key' => 'school_email',
                'value' => 'info@alghazaly.sch.id',
                'type' => 'email',
                'group' => 'contact',
                'is_public' => true,
            ],
            [
                'key' => 'school_whatsapp',
                'value' => null,
                'type' => 'phone',
                'group' => 'contact',
                'is_public' => true,
            ],
            [
                'key' => 'google_maps_url',
                'value' => null,
                'type' => 'url',
                'group' => 'contact',
                'is_public' => true,
            ],
            [
                'key' => 'homepage_hero_title',
                'value' => 'Selamat Datang di SMA Al Ghazaly',
                'type' => 'text',
                'group' => 'homepage',
                'is_public' => true,
            ],
            [
                'key' => 'homepage_hero_subtitle',
                'value' => 'Membangun generasi berilmu, berakhlak, dan berprestasi.',
                'type' => 'textarea',
                'group' => 'homepage',
                'is_public' => true,
            ],
            [
                'key' => 'ppdb_status',
                'value' => 'closed',
                'type' => 'boolean',
                'group' => 'ppdb',
                'is_public' => true,
            ],
            [
                'key' => 'ppdb_academic_year',
                'value' => '2026/2027',
                'type' => 'text',
                'group' => 'ppdb',
                'is_public' => true,
            ],
            [
                'key' => 'ppdb_registration_fee',
                'value' => '0',
                'type' => 'number',
                'group' => 'ppdb',
                'is_public' => true,
            ],
            [
                'key' => 'bank_name',
                'value' => null,
                'type' => 'text',
                'group' => 'payment',
                'is_public' => false,
            ],
            [
                'key' => 'bank_account_number',
                'value' => null,
                'type' => 'text',
                'group' => 'payment',
                'is_public' => false,
            ],
            [
                'key' => 'bank_account_name',
                'value' => null,
                'type' => 'text',
                'group' => 'payment',
                'is_public' => false,
            ],
            [
                'key' => 'seo_title',
                'value' => 'SMA Al Ghazaly',
                'type' => 'text',
                'group' => 'seo',
                'is_public' => true,
            ],
            [
                'key' => 'seo_description',
                'value' => 'Website resmi SMA Al Ghazaly.',
                'type' => 'textarea',
                'group' => 'seo',
                'is_public' => true,
            ],
            [
                'key' => 'footer_text',
                'value' => '© SMA Al Ghazaly. All rights reserved.',
                'type' => 'text',
                'group' => 'general',
                'is_public' => true,
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'group' => $setting['group'],
                    'is_public' => $setting['is_public'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
