<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            SettingSeeder::class,
            CategorySeeder::class,
            TeacherSeeder::class,
            PostSeeder::class,
            PageSeeder::class,
            TestimonialSeeder::class,
            AlumniSeeder::class,
            RegistrationSeeder::class,
        ]);
    }
}
