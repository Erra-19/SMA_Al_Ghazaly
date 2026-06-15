<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedPpdbForm();
        $this->seedContactForm();
    }

    // ── PPDB ─────────────────────────────────────────────────────────────────────

    private function seedPpdbForm(): void
    {
        if (DB::table('forms')->where('slug', 'ppdb-registration')->exists()) {
            $this->command->info('Form PPDB sudah ada, skip.');
            return;
        }

        $pendidikan    = ['Tidak Sekolah', 'SD / Sederajat', 'SMP / Sederajat', 'SLTA / Sederajat', 'D1', 'D2', 'D3', 'D4 / S1', 'S2', 'S3'];
        $pekerjaan     = ['Tidak Bekerja', 'PNS / TNI / Polri', 'Karyawan Swasta', 'Wiraswasta', 'Petani', 'Nelayan', 'Buruh', 'Lainnya'];
        $penghasilan   = ['Tidak Berpenghasilan', '< Rp. 500.000', 'Rp. 500.000 - Rp. 999.999', 'Rp. 1.000.000 - Rp. 1.999.999', 'Rp. 2.000.000 - Rp. 2.999.999', 'Rp. 3.000.000 - Rp. 5.000.000', '> Rp. 5.000.000'];
        $kebutuhanSiswa = ['Tidak Ada', 'Netra', 'Rungu', 'Grahita Ringan', 'Grahita Sedang', 'Daksa Ringan', 'Laras', 'Wicara', 'Tuna Ganda', 'Hiperaktif', 'Cerdas Istimewa', 'Bakat Istimewa', 'Kesulitan Belajar', 'Autis', 'Lainnya'];
        $kebutuhanOrtu  = ['Tidak Ada', 'Netra', 'Rungu', 'Grahita Ringan', 'Daksa', 'Lainnya'];

        $steps = [
            [
                'label' => 'Registrasi Peserta Didik', 'short_label' => 'Registrasi',
                'fields' => [
                    ['key' => 'jenis_pendaftaran', 'label' => 'Jenis Pendaftaran',            'type' => 'select', 'required' => true,  'options' => ['Siswa Baru (Lulusan SMP/MTs)', 'Pindahan dari SMA/SMK/MA Lain']],
                    ['key' => 'no_peserta_un',     'label' => 'Nomor Peserta UN Sebelumnya',  'type' => 'text',   'required' => true,  'placeholder' => 'Contoh: 2-19-02-05-001-102-3'],
                    ['key' => 'no_skhun',          'label' => 'Nomor Seri SKHUN Sebelumnya', 'type' => 'text',   'required' => true,  'placeholder' => 'Contoh: DN-01 DI 1029302'],
                    ['key' => 'no_ijazah',         'label' => 'Nomor Seri Ijazah Sebelumnya','type' => 'text',   'required' => true,  'placeholder' => 'Contoh: DN-01/D-SMP/06/10294'],
                ],
            ],
            [
                'label' => 'Data Pribadi', 'short_label' => 'Pribadi',
                'fields' => [
                    ['key' => 'student_name',     'label' => 'Nama Lengkap',              'type' => 'text',     'required' => true,  'placeholder' => 'Sesuai akta lahir'],
                    ['key' => 'gender',           'label' => 'Jenis Kelamin',             'type' => 'radio',    'required' => true,  'options' => ['Laki-laki', 'Perempuan']],
                    ['key' => 'nisn',             'label' => 'NISN (10 digit)',           'type' => 'text',     'required' => true,  'placeholder' => 'Nomor Induk Siswa Nasional'],
                    ['key' => 'nik',              'label' => 'NIK (16 digit)',            'type' => 'text',     'required' => true,  'placeholder' => 'Sesuai Kartu Keluarga'],
                    ['key' => 'birth_place',      'label' => 'Tempat Lahir',              'type' => 'text',     'required' => true,  'placeholder' => 'Kota tempat lahir'],
                    ['key' => 'birth_date',       'label' => 'Tanggal Lahir',             'type' => 'date',     'required' => true],
                    ['key' => 'agama',            'label' => 'Agama',                     'type' => 'select',   'required' => false, 'options' => ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu']],
                    ['key' => 'kebutuhan_khusus', 'label' => 'Kebutuhan Khusus',          'type' => 'select',   'required' => false, 'options' => $kebutuhanSiswa],
                    ['key' => 'address',          'label' => 'Alamat Jalan',              'type' => 'textarea', 'required' => true,  'placeholder' => 'Nama jalan, nomor rumah...'],
                    ['key' => 'rt',               'label' => 'RT',                        'type' => 'text',     'required' => false, 'placeholder' => '001'],
                    ['key' => 'rw',               'label' => 'RW',                        'type' => 'text',     'required' => false, 'placeholder' => '002'],
                    ['key' => 'nama_dusun',       'label' => 'Dusun / Lingkungan',        'type' => 'text',     'required' => false],
                    ['key' => 'nama_kelurahan',   'label' => 'Kelurahan / Desa',          'type' => 'text',     'required' => false],
                    ['key' => 'kecamatan',        'label' => 'Kecamatan',                 'type' => 'text',     'required' => false, 'placeholder' => 'Bogor Tengah'],
                    ['key' => 'kode_pos',         'label' => 'Kode Pos',                  'type' => 'text',     'required' => false, 'placeholder' => '16111'],
                    ['key' => 'tinggal_bersama',  'label' => 'Tinggal Bersama',           'type' => 'select',   'required' => false, 'options' => ['Orang Tua', 'Wali', 'Kos / Kontrakan', 'Panti Asuhan', 'Lainnya']],
                    ['key' => 'transportasi',     'label' => 'Transportasi ke Sekolah',   'type' => 'select',   'required' => false, 'options' => ['Jalan Kaki', 'Sepeda', 'Sepeda Motor', 'Kendaraan Pribadi', 'Angkutan Umum', 'Antar Jemput Sekolah', 'Lainnya']],
                    ['key' => 'phone',            'label' => 'No. HP / WhatsApp',         'type' => 'tel',      'required' => true,  'placeholder' => '0812xxxxxxxx'],
                    ['key' => 'email',            'label' => 'Alamat Email',              'type' => 'email',    'required' => false, 'placeholder' => 'nama@email.com'],
                ],
            ],
            [
                'label' => 'Data Ayah Kandung', 'short_label' => 'Ayah',
                'fields' => [
                    ['key' => 'nama_ayah',               'label' => 'Nama Ayah Kandung',    'type' => 'text',   'required' => true,  'placeholder' => 'Nama lengkap'],
                    ['key' => 'tahun_lahir_ayah',        'label' => 'Tahun Lahir',          'type' => 'number', 'required' => false, 'placeholder' => '1975'],
                    ['key' => 'pendidikan_ayah',         'label' => 'Pendidikan Terakhir',  'type' => 'select', 'required' => false, 'options' => $pendidikan],
                    ['key' => 'pekerjaan_ayah',          'label' => 'Pekerjaan',            'type' => 'select', 'required' => false, 'options' => $pekerjaan],
                    ['key' => 'penghasilan_ayah',        'label' => 'Penghasilan per Bulan','type' => 'select', 'required' => false, 'options' => $penghasilan],
                    ['key' => 'berkebutuhan_khusus_ayah','label' => 'Berkebutuhan Khusus',  'type' => 'select', 'required' => false, 'options' => $kebutuhanOrtu],
                ],
            ],
            [
                'label' => 'Data Ibu Kandung', 'short_label' => 'Ibu',
                'fields' => [
                    ['key' => 'nama_ibu',                'label' => 'Nama Ibu Kandung',     'type' => 'text',   'required' => true,  'placeholder' => 'Nama lengkap'],
                    ['key' => 'tahun_lahir_ibu',         'label' => 'Tahun Lahir',          'type' => 'number', 'required' => false, 'placeholder' => '1978'],
                    ['key' => 'pendidikan_ibu',          'label' => 'Pendidikan Terakhir',  'type' => 'select', 'required' => false, 'options' => $pendidikan],
                    ['key' => 'pekerjaan_ibu',           'label' => 'Pekerjaan',            'type' => 'select', 'required' => false, 'options' => array_merge(['Ibu Rumah Tangga'], $pekerjaan)],
                    ['key' => 'penghasilan_ibu',         'label' => 'Penghasilan per Bulan','type' => 'select', 'required' => false, 'options' => $penghasilan],
                    ['key' => 'berkebutuhan_khusus_ibu', 'label' => 'Berkebutuhan Khusus',  'type' => 'select', 'required' => false, 'options' => $kebutuhanOrtu],
                ],
            ],
            [
                'label' => 'Data Wali', 'short_label' => 'Wali',
                'fields' => [
                    ['key' => 'mempunyai_wali',   'label' => 'Apakah Mempunyai Wali?',   'type' => 'radio',  'required' => false, 'options' => ['Ya', 'Tidak']],
                    ['key' => 'nama_wali',         'label' => 'Nama Wali',                'type' => 'text',   'required' => false, 'placeholder' => 'Kosongkan jika tidak ada'],
                    ['key' => 'tahun_lahir_wali',  'label' => 'Tahun Lahir Wali',         'type' => 'number', 'required' => false, 'placeholder' => '1970'],
                    ['key' => 'pendidikan_wali',   'label' => 'Pendidikan Terakhir Wali', 'type' => 'select', 'required' => false, 'options' => $pendidikan],
                    ['key' => 'pekerjaan_wali',    'label' => 'Pekerjaan Wali',           'type' => 'select', 'required' => false, 'options' => $pekerjaan],
                    ['key' => 'penghasilan_wali',  'label' => 'Penghasilan per Bulan',    'type' => 'select', 'required' => false, 'options' => $penghasilan],
                ],
            ],
            [
                'label' => 'Data Periodik', 'short_label' => 'Periodik',
                'fields' => [
                    ['key' => 'tinggi_badan',           'label' => 'Tinggi Badan (cm)',       'type' => 'number', 'required' => true,  'placeholder' => '160'],
                    ['key' => 'berat_badan',            'label' => 'Berat Badan (kg)',        'type' => 'number', 'required' => true,  'placeholder' => '55'],
                    ['key' => 'jarak_sekolah',          'label' => 'Jarak Rumah ke Sekolah', 'type' => 'radio',  'required' => false, 'options' => ['<= 1 KM', '> 1 KM']],
                    ['key' => 'jarak_sekolah_km',       'label' => 'Jarak (km)',             'type' => 'number', 'required' => false, 'placeholder' => '1'],
                    ['key' => 'waktu_tempuh',           'label' => 'Waktu Tempuh (menit)',   'type' => 'number', 'required' => false, 'placeholder' => '15'],
                    ['key' => 'jumlah_saudara_kandung', 'label' => 'Jumlah Saudara Kandung', 'type' => 'number', 'required' => false, 'placeholder' => '2'],
                ],
            ],
        ];

        DB::table('forms')->insert([
            'name'        => 'Formulir PPDB SMA Al-Ghazaly',
            'type'        => 'ppdb',
            'slug'        => 'ppdb-registration',
            'fields'      => json_encode([]),
            'steps'       => json_encode($steps),
            'description' => 'Formulir Penerimaan Peserta Didik Baru (PPDB) SMA Al-Ghazaly Bogor. Isi semua data dengan benar dan lengkap sesuai dokumen resmi.',
            'is_active'   => 1,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $this->command->info('✓ Form PPDB di-seed.');
    }

    // ── Kontak ────────────────────────────────────────────────────────────────────

    private function seedContactForm(): void
    {
        if (DB::table('forms')->where('slug', 'contact')->exists()) {
            $this->command->info('Form Kontak sudah ada, skip.');
            return;
        }

        $steps = [
            [
                'label'       => 'Kirim Pesan',
                'short_label' => 'Pesan',
                'fields'      => [
                    [
                        'key'         => 'name',
                        'label'       => 'Nama Lengkap',
                        'type'        => 'text',
                        'required'    => true,
                        'placeholder' => 'Nama Anda',
                    ],
                    [
                        'key'         => 'email',
                        'label'       => 'Alamat Email',
                        'type'        => 'email',
                        'required'    => true,
                        'placeholder' => 'nama@email.com',
                    ],
                    [
                        'key'         => 'phone',
                        'label'       => 'No. HP / WhatsApp',
                        'type'        => 'tel',
                        'required'    => false,
                        'placeholder' => '0812xxxxxxxx',
                    ],
                    [
                        'key'      => 'subject',
                        'label'    => 'Topik',
                        'type'     => 'select',
                        'required' => false,
                        'options'  => [
                            'Informasi Umum',
                            'Informasi PPDB',
                            'Fasilitas Sekolah',
                            'Kegiatan & Ekskul',
                            'Kerjasama & Kemitraan',
                            'Lainnya',
                        ],
                    ],
                    [
                        'key'         => 'message',
                        'label'       => 'Pesan',
                        'type'        => 'textarea',
                        'required'    => true,
                        'placeholder' => 'Tuliskan pertanyaan atau pesan Anda di sini...',
                    ],
                ],
            ],
        ];

        DB::table('forms')->insert([
            'name'        => 'Formulir Kontak',
            'type'        => 'contact',
            'slug'        => 'contact',
            'fields'      => json_encode([]),
            'steps'       => json_encode($steps),
            'description' => 'Hubungi kami untuk informasi seputar sekolah, PPDB, maupun kerjasama.',
            'is_active'   => 1,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        $this->command->info('✓ Form Kontak di-seed.');
    }
}
