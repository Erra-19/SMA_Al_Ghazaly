<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'description' => 'Akses penuh ke seluruh sistem.',
            ],
            [
                'name' => 'admin',
                'description' => 'Admin website sekolah.',
            ],
            [
                'name' => 'operator_ppdb',
                'description' => 'Operator untuk mengelola data PPDB.',
            ],
            [
                'name' => 'teacher',
                'description' => 'Akun guru/staff sekolah.',
            ],
            [
                'name' => 'student',
                'description' => 'Akun siswa atau calon siswa.',
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                [
                    'description' => $role['description'],
                ]
            );
        }
    }
}
