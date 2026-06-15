<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        if (DB::table('teachers')->count() > 0) {
            $this->command->info('Data guru sudah ada, skip.');
            return;
        }

        $now = now();

        $teachers = [
            // ── Pimpinan ──────────────────────────────────────────────────────
            [
                'name'          => 'Dr. H. Ahmad Fauzi, M.Pd.',
                'position'      => 'Kepala Sekolah',
                'subject'       => 'Manajemen Pendidikan',
                'category'      => 'bk-staf',
                'education'     => 'S3 Manajemen Pendidikan, UPI Bandung',
                'experience'    => '24 Tahun Mengabdi',
                'philosophy'    => 'Pendidikan yang baik adalah yang membentuk karakter, bukan sekadar transfer ilmu.',
                'bio'           => 'Memimpin SMA Al-Ghazaly dengan visi integrasi imtaq dan ipteks sejak 2010.',
                'is_leadership' => 1,
                'is_active'     => 1,
                'order'         => 1,
            ],
            [
                'name'          => 'Hj. Siti Rahayu, S.Pd., M.M.',
                'position'      => 'Wakil Kepala Sekolah Bidang Kurikulum',
                'subject'       => 'Bahasa Indonesia',
                'category'      => 'social-english',
                'education'     => 'S2 Manajemen, Universitas Pakuan Bogor',
                'experience'    => '19 Tahun Mengabdi',
                'philosophy'    => 'Kurikulum yang baik lahir dari guru yang ikhlas dan siswa yang bersemangat.',
                'bio'           => 'Mengawal pengembangan kurikulum berbasis akhlak dan kompetensi.',
                'is_leadership' => 1,
                'is_active'     => 1,
                'order'         => 2,
            ],
            [
                'name'          => 'Ustadz Mujahid Fikri, S.Ag.',
                'position'      => 'Wakil Kepala Sekolah Bidang Kesiswaan',
                'subject'       => 'Pendidikan Agama Islam',
                'category'      => 'imtak',
                'education'     => 'S1 Pendidikan Agama Islam, UIN Jakarta',
                'experience'    => '16 Tahun Mengabdi',
                'philosophy'    => 'Akhlak mulia adalah fondasi dari segala prestasi.',
                'bio'           => 'Pembina rohani dan karakter siswa. Koordinator program tahfidz Quran.',
                'is_leadership' => 1,
                'is_active'     => 1,
                'order'         => 3,
            ],

            // ── Guru MIPA & Teknologi ─────────────────────────────────────────
            [
                'name'          => 'Rizky Pratama, S.Si., M.Sc.',
                'position'      => 'Guru Matematika',
                'subject'       => 'Matematika Wajib & Peminatan',
                'category'      => 'mipa',
                'education'     => 'S2 Matematika, IPB University',
                'experience'    => '11 Tahun Mengabdi',
                'philosophy'    => 'Matematika bukan tentang angka, tapi tentang cara berpikir.',
                'tags'          => json_encode(['Matematika', 'Logika', 'OSN Matematika']),
                'is_leadership' => 0,
                'is_active'     => 1,
                'order'         => 4,
            ],
            [
                'name'          => 'Dewi Anggraeni, S.Pd.',
                'position'      => 'Guru Fisika',
                'subject'       => 'Fisika',
                'category'      => 'mipa',
                'education'     => 'S1 Pendidikan Fisika, UNPAK Bogor',
                'experience'    => '8 Tahun Mengabdi',
                'philosophy'    => 'Setiap fenomena alam adalah tanda kebesaran Allah yang wajib kita pelajari.',
                'tags'          => json_encode(['Fisika', 'Robotika', 'OSN Fisika']),
                'is_leadership' => 0,
                'is_active'     => 1,
                'order'         => 5,
            ],
            [
                'name'          => 'Muhammad Ilham, S.Kom., M.T.',
                'position'      => 'Guru Informatika',
                'subject'       => 'Informatika & Pemrograman',
                'category'      => 'mipa',
                'education'     => 'S2 Teknik Informatika, Institut Teknologi Bogor',
                'experience'    => '7 Tahun Mengabdi',
                'philosophy'    => 'Teknologi adalah alat; kebijaksanaan adalah pengendalinya.',
                'tags'          => json_encode(['Pemrograman', 'Web Development', 'AI & Robotika']),
                'is_leadership' => 0,
                'is_active'     => 1,
                'order'         => 6,
            ],
            [
                'name'          => 'Nur Fadilah, S.Pd.',
                'position'      => 'Guru Kimia & Biologi',
                'subject'       => 'Kimia',
                'category'      => 'mipa',
                'education'     => 'S1 Pendidikan Kimia, Universitas Negeri Jakarta',
                'experience'    => '9 Tahun Mengabdi',
                'philosophy'    => 'Ilmu tanpa amal adalah pohon tanpa buah.',
                'tags'          => json_encode(['Kimia', 'Biologi', 'KIR']),
                'is_leadership' => 0,
                'is_active'     => 1,
                'order'         => 7,
            ],

            // ── Guru IPS & Bahasa ─────────────────────────────────────────────
            [
                'name'          => 'Endang Susilawati, S.Pd., M.Hum.',
                'position'      => 'Guru Bahasa Indonesia',
                'subject'       => 'Bahasa & Sastra Indonesia',
                'category'      => 'social-english',
                'education'     => 'S2 Ilmu Humaniora, Universitas Indonesia',
                'experience'    => '15 Tahun Mengabdi',
                'philosophy'    => 'Membaca adalah jendela dunia; menulis adalah jejakmu di semesta.',
                'tags'          => json_encode(['Bahasa Indonesia', 'Sastra', 'Jurnalistik']),
                'is_leadership' => 0,
                'is_active'     => 1,
                'order'         => 8,
            ],
            [
                'name'          => 'David Setiawan, S.Pd.',
                'position'      => 'Guru Bahasa Inggris',
                'subject'       => 'Bahasa Inggris',
                'category'      => 'social-english',
                'education'     => 'S1 Pendidikan Bahasa Inggris, UIKA Bogor',
                'experience'    => '10 Tahun Mengabdi',
                'philosophy'    => 'Language is the bridge that connects civilizations.',
                'tags'          => json_encode(['Bahasa Inggris', 'English Club', 'TOEFL Preparation']),
                'is_leadership' => 0,
                'is_active'     => 1,
                'order'         => 9,
            ],
            [
                'name'          => 'Yudi Hadianto, S.Pd.',
                'position'      => 'Guru Ekonomi & Akuntansi',
                'subject'       => 'Ekonomi',
                'category'      => 'social-english',
                'education'     => 'S1 Pendidikan Ekonomi, Universitas Pakuan Bogor',
                'experience'    => '12 Tahun Mengabdi',
                'philosophy'    => 'Didiklah generasi yang tidak hanya cerdas, tapi juga jujur dan amanah.',
                'tags'          => json_encode(['Ekonomi', 'Akuntansi', 'Kewirausahaan']),
                'is_leadership' => 0,
                'is_active'     => 1,
                'order'         => 10,
            ],

            // ── Imtak & Keagamaan ─────────────────────────────────────────────
            [
                'name'          => 'Ustadz Hasan Basri, Lc.',
                'position'      => 'Guru Al-Qur\'an & Tahfidz',
                'subject'       => 'Al-Qur\'an & Tahfidz',
                'category'      => 'imtak',
                'education'     => 'Lc. (Lisensi) Al-Azhar University, Kairo',
                'experience'    => '13 Tahun Mengabdi',
                'philosophy'    => 'Sebaik-baik kalian adalah yang mempelajari Al-Quran dan mengajarkannya.',
                'tags'          => json_encode(['Tahfidz', 'Tafsir', 'Bahasa Arab']),
                'is_leadership' => 0,
                'is_active'     => 1,
                'order'         => 11,
            ],

            // ── BK & Staf ─────────────────────────────────────────────────────
            [
                'name'          => 'Ratna Dewi, S.Psi.',
                'position'      => 'Guru Bimbingan Konseling',
                'subject'       => 'Bimbingan Konseling',
                'category'      => 'bk-staf',
                'education'     => 'S1 Psikologi, Universitas Tarumanagara Jakarta',
                'experience'    => '6 Tahun Mengabdi',
                'philosophy'    => 'Setiap siswa adalah bintang; tugas kita membantu mereka bersinar.',
                'tags'          => json_encode(['Konseling', 'Psikologi Remaja', 'Karir']),
                'is_leadership' => 0,
                'is_active'     => 1,
                'order'         => 12,
            ],
        ];

        $rows = array_map(function ($t) use ($now) {
            return array_merge([
                'nip'           => null,
                'phone'         => null,
                'email'         => null,
                'bio'           => null,
                'photo'         => null,
                'tags'          => null,
                'is_leadership' => 0,
            ], $t, ['created_at' => $now, 'updated_at' => $now]);
        }, $teachers);

        DB::table('teachers')->insert($rows);

        $this->command->info('✓ ' . count($rows) . ' data guru di-seed.');
    }
}
