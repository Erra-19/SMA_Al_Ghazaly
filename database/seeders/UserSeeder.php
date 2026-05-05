<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superadminRole = DB::table('roles')->where('name', 'superadmin')->value('role_id');
        $adminRole      = DB::table('roles')->where('name', 'admin')->value('role_id');
        $editorRole     = DB::table('roles')->where('name', 'editor')->value('role_id');

        $users = [
            [
                'name'       => 'Super Admin',
                'email'      => 'superadmin@alghazaly.sch.id',
                'password'   => Hash::make('password'),
                'role_id'    => $superadminRole,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Admin Website',
                'email'      => 'admin@alghazaly.sch.id',
                'password'   => Hash::make('password'),
                'role_id'    => $adminRole,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Budi Santoso',
                'email'      => 'budi.editor@alghazaly.sch.id',
                'password'   => Hash::make('password'),
                'role_id'    => $editorRole,
                'is_active'  => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insertOrIgnore($user);
        }
    }
}
