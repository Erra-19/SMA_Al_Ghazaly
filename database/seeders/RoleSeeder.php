<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'superadmin', 'description' => 'Super Administrator dengan akses penuh'],
            ['name' => 'admin', 'description' => 'Administrator website sekolah'],
            ['name' => 'editor', 'description' => 'Editor konten berita dan halaman'],
            ['name' => 'staff', 'description' => 'Staff tata usaha'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore($role);
        }
    }
}
