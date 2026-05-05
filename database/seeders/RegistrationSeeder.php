<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $registrations = [
            [
                'registration_number' => 'PPDB-2025-0001',
                'full_name'           => 'Ahmad Rizqi Maulana',
                'birth_date'          => '2009-03-15',
                'birth_place'         => 'Bandung',
                'gender'              => 'L',
                'address'             => 'Jl. Merdeka No. 12, Bandung',
                'phone'               => '081234567001',
                'parent_name'         => 'Hendra Maulana',
                'parent_phone'        => '081234560001',
                'previous_school'     => 'SMP Negeri 1 Bandung',
                'academic_year'       => '2025/2026',
                'status'              => 'verified',
                'notes'               => null,
                'created_at'          => now()->subDays(20),
                'updated_at'          => now()->subDays(18),
            ],
            [
                'registration_number' => 'PPDB-2025-0002',
                'full_name'           => 'Putri Aulia Rahma',
                'birth_date'          => '2009-07-22',
                'birth_place'         => 'Cimahi',
                'gender'              => 'P',
                'address'             => 'Jl. Cihanjuang No. 45, Cimahi',
                'phone'               => '081234567002',
                'parent_name'         => 'Rudi Rahma',
                'parent_phone'        => '081234560002',
                'previous_school'     => 'SMP Islam Terpadu Al Huda',
                'academic_year'       => '2025/2026',
                'status'              => 'verified',
                'notes'               => null,
                'created_at'          => now()->subDays(18),
                'updated_at'          => now()->subDays(15),
            ],
            [
                'registration_number' => 'PPDB-2025-0003',
                'full_name'           => 'Farhan Hakim Santoso',
                'birth_date'          => '2009-11-08',
                'birth_place'         => 'Sumedang',
                'gender'              => 'L',
                'address'             => 'Jl. Raya Sumedang No. 78',
                'phone'               => '081234567003',
                'parent_name'         => 'Budi Santoso',
                'parent_phone'        => '081234560003',
                'previous_school'     => 'SMP Negeri 2 Sumedang',
                'academic_year'       => '2025/2026',
                'status'              => 'pending',
                'notes'               => null,
                'created_at'          => now()->subDays(10),
                'updated_at'          => now()->subDays(10),
            ],
            [
                'registration_number' => 'PPDB-2025-0004',
                'full_name'           => 'Siti Nur Aisyah',
                'birth_date'          => '2009-05-30',
                'birth_place'         => 'Garut',
                'gender'              => 'P',
                'address'             => 'Jl. Veteran No. 23, Garut',
                'phone'               => '081234567004',
                'parent_name'         => 'Agus Hermawan',
                'parent_phone'        => '081234560004',
                'previous_school'     => 'MTs Negeri 1 Garut',
                'academic_year'       => '2025/2026',
                'status'              => 'accepted',
                'notes'               => 'Nilai rapor sangat baik, diterima jalur prestasi',
                'created_at'          => now()->subDays(15),
                'updated_at'          => now()->subDays(5),
            ],
            [
                'registration_number' => 'PPDB-2025-0005',
                'full_name'           => 'Daffa Arya Wicaksono',
                'birth_date'          => '2009-01-17',
                'birth_place'         => 'Bandung',
                'gender'              => 'L',
                'address'             => 'Komplek Antapani Blok C No. 5, Bandung',
                'phone'               => '081234567005',
                'parent_name'         => 'Sigit Wicaksono',
                'parent_phone'        => '081234560005',
                'previous_school'     => 'SMP Laboratorium Percontohan UPI',
                'academic_year'       => '2025/2026',
                'status'              => 'rejected',
                'notes'               => 'Kuota jalur reguler sudah penuh',
                'created_at'          => now()->subDays(8),
                'updated_at'          => now()->subDays(3),
            ],
        ];

        foreach ($registrations as $reg) {
            DB::table('registrations')->insertOrIgnore($reg);
        }
    }
}
