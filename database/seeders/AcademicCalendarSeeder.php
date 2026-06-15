<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcademicCalendarSeeder extends Seeder
{
    public function run(): void
    {
        $year = '2025/2026';

        $events = [
            ['title' => 'Libur Idul Fitri 1446H',              'start' => '2025-03-28', 'end' => '2025-04-06', 'category' => 'Libur',    'color' => 'green'],
            ['title' => 'Masuk Sekolah Setelah Lebaran',        'start' => '2025-04-07', 'end' => null,         'category' => 'Akademik', 'color' => 'blue'],
            ['title' => 'Ujian Tengah Semester Genap',          'start' => '2025-04-14', 'end' => '2025-04-19', 'category' => 'Ujian',    'color' => 'red'],
            ['title' => 'Hardiknas & Upacara Bendera',          'start' => '2025-05-02', 'end' => null,         'category' => 'Akademik', 'color' => 'blue'],
            ['title' => 'Hari Buruh Nasional',                  'start' => '2025-05-01', 'end' => null,         'category' => 'Libur',    'color' => 'green'],
            ['title' => 'Kenaikan Isa Al-Masih',                'start' => '2025-05-29', 'end' => null,         'category' => 'Libur',    'color' => 'green'],
            ['title' => 'Ujian Akhir Semester Genap',           'start' => '2025-06-02', 'end' => '2025-06-13', 'category' => 'Ujian',    'color' => 'red'],
            ['title' => 'Wisuda & Pelepasan Kelas XII',         'start' => '2025-06-21', 'end' => null,         'category' => 'Kegiatan', 'color' => 'purple'],
            ['title' => 'Penerimaan Rapor Kenaikan Kelas',      'start' => '2025-06-27', 'end' => null,         'category' => 'Akademik', 'color' => 'blue'],
            ['title' => 'Libur Kenaikan Kelas',                 'start' => '2025-06-28', 'end' => '2025-07-13', 'category' => 'Libur',    'color' => 'green'],
            ['title' => 'Masa Orientasi Siswa Baru (MOS)',      'start' => '2025-07-14', 'end' => '2025-07-18', 'category' => 'Kegiatan', 'color' => 'orange'],
            ['title' => 'Awal Tahun Pelajaran 2025/2026',       'start' => '2025-07-14', 'end' => null,         'category' => 'Akademik', 'color' => 'blue'],
            ['title' => 'Pesantren Kilat Ramadhan',             'start' => '2026-02-23', 'end' => '2026-02-27', 'category' => 'Kegiatan', 'color' => 'orange'],
            ['title' => 'Ujian Tengah Semester Ganjil',         'start' => '2025-10-06', 'end' => '2025-10-11', 'category' => 'Ujian',    'color' => 'red'],
            ['title' => 'Ujian Akhir Semester Ganjil',          'start' => '2025-11-24', 'end' => '2025-12-05', 'category' => 'Ujian',    'color' => 'red'],
            ['title' => 'Penerimaan Rapor Semester Ganjil',     'start' => '2025-12-19', 'end' => null,         'category' => 'Akademik', 'color' => 'blue'],
            ['title' => 'Libur Semester Ganjil',                'start' => '2025-12-20', 'end' => '2026-01-04', 'category' => 'Libur',    'color' => 'green'],
            ['title' => 'Awal Semester Genap 2025/2026',        'start' => '2026-01-05', 'end' => null,         'category' => 'Akademik', 'color' => 'blue'],
            ['title' => 'Isra Mi\'raj Nabi Muhammad SAW',       'start' => '2026-01-27', 'end' => null,         'category' => 'Libur',    'color' => 'green'],
            ['title' => 'PPDB 2026/2027 Dibuka',                'start' => '2026-03-01', 'end' => '2026-05-31', 'category' => 'PPDB',     'color' => 'teal'],
        ];

        foreach ($events as $event) {
            $existing = DB::table('academic_calendars')
                ->where('title', $event['title'])
                ->where('academic_year', $year)
                ->first();

            if ($existing) {
                $this->command->line("  Skip: {$event['title']}");
                continue;
            }

            DB::table('academic_calendars')->insert([
                'title'         => $event['title'],
                'start_date'    => $event['start'],
                'end_date'      => $event['end'],
                'category'      => $event['category'],
                'color'         => $event['color'],
                'academic_year' => $year,
                'description'   => null,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        $this->command->info('✓ Academic Calendar di-seed (' . count($events) . ' item, TA ' . $year . ').');
    }
}
