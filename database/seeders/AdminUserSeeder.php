<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdminRoleId = DB::table('roles')
            ->where('name', 'super_admin')
            ->value('role_id');

        User::updateOrCreate(
            [
                'email' => 'admin@alghazaly.test',
            ],
            [
                'name' => 'Super Admin',
                'nisn' => null,
                'nip' => null,
                'password' => Hash::make('Erri1904!!'),
                'role_id' => $superAdminRoleId,
                'is_active' => true,
            ]
        );
    }
}
