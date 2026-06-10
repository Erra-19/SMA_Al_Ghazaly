<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $columns = [
                'jenis_pendaftaran' => fn () => $table->string('jenis_pendaftaran', 50)->default('Siswa Baru'),
                'no_peserta_un' => fn () => $table->string('no_peserta_un', 50)->nullable(),
                'no_skhun' => fn () => $table->string('no_skhun', 50)->nullable(),
                'no_ijazah' => fn () => $table->string('no_ijazah', 50)->nullable(),
                'nisn' => fn () => $table->string('nisn', 20)->nullable(),
                'nik' => fn () => $table->string('nik', 16)->nullable(),
                'agama' => fn () => $table->string('agama', 50)->nullable()->default('Islam'),
                'kebutuhan_khusus' => fn () => $table->string('kebutuhan_khusus', 100)->nullable()->default('Tidak Ada'),
                'email' => fn () => $table->string('email', 150)->nullable(),
                'rt' => fn () => $table->string('rt', 10)->nullable(),
                'rw' => fn () => $table->string('rw', 10)->nullable(),
                'nama_dusun' => fn () => $table->string('nama_dusun', 100)->nullable(),
                'nama_kelurahan' => fn () => $table->string('nama_kelurahan', 100)->nullable(),
                'kecamatan' => fn () => $table->string('kecamatan', 100)->nullable(),
                'kode_pos' => fn () => $table->string('kode_pos', 10)->nullable(),
                'tinggal_bersama' => fn () => $table->string('tinggal_bersama', 50)->nullable()->default('Orang Tua'),
                'transportasi' => fn () => $table->string('transportasi', 50)->nullable()->default('Kendaraan Pribadi'),
                'nama_ayah' => fn () => $table->string('nama_ayah', 150)->nullable(),
                'tahun_lahir_ayah' => fn () => $table->year('tahun_lahir_ayah')->nullable(),
                'pendidikan_ayah' => fn () => $table->string('pendidikan_ayah', 100)->nullable(),
                'pekerjaan_ayah' => fn () => $table->string('pekerjaan_ayah', 100)->nullable(),
                'penghasilan_ayah' => fn () => $table->string('penghasilan_ayah', 100)->nullable(),
                'kebutuhan_khusus_ayah' => fn () => $table->string('kebutuhan_khusus_ayah', 100)->nullable()->default('Tidak Ada'),
                'nama_ibu' => fn () => $table->string('nama_ibu', 150)->nullable(),
                'tahun_lahir_ibu' => fn () => $table->year('tahun_lahir_ibu')->nullable(),
                'pendidikan_ibu' => fn () => $table->string('pendidikan_ibu', 100)->nullable(),
                'pekerjaan_ibu' => fn () => $table->string('pekerjaan_ibu', 100)->nullable(),
                'penghasilan_ibu' => fn () => $table->string('penghasilan_ibu', 100)->nullable(),
                'kebutuhan_khusus_ibu' => fn () => $table->string('kebutuhan_khusus_ibu', 100)->nullable()->default('Tidak Ada'),
                'mempunyai_wali' => fn () => $table->tinyInteger('mempunyai_wali')->default(0),
                'nama_wali' => fn () => $table->string('nama_wali', 150)->nullable(),
                'tahun_lahir_wali' => fn () => $table->year('tahun_lahir_wali')->nullable(),
                'pendidikan_wali' => fn () => $table->string('pendidikan_wali', 100)->nullable(),
                'pekerjaan_wali' => fn () => $table->string('pekerjaan_wali', 100)->nullable(),
                'penghasilan_wali' => fn () => $table->string('penghasilan_wali', 100)->nullable(),
                'tinggi_badan' => fn () => $table->unsignedSmallInteger('tinggi_badan')->nullable(),
                'berat_badan' => fn () => $table->unsignedSmallInteger('berat_badan')->nullable(),
                'jarak_sekolah' => fn () => $table->string('jarak_sekolah', 20)->nullable(),
                'jarak_sekolah_km' => fn () => $table->unsignedSmallInteger('jarak_sekolah_km')->nullable(),
                'waktu_tempuh' => fn () => $table->unsignedSmallInteger('waktu_tempuh')->nullable(),
                'jumlah_saudara_kandung' => fn () => $table->tinyInteger('jumlah_saudara_kandung')->default(0),
            ];

            foreach ($columns as $column => $definition) {
                if (! Schema::hasColumn('registrations', $column)) {
                    $definition();
                }
            }
        });
    }

    public function down(): void
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_pendaftaran', 'no_peserta_un', 'no_skhun', 'no_ijazah',
                'nik', 'agama', 'kebutuhan_khusus', 'email',
                'rt', 'rw', 'nama_dusun', 'nama_kelurahan', 'kecamatan', 'kode_pos',
                'tinggal_bersama', 'transportasi',
                'nama_ayah', 'tahun_lahir_ayah', 'pendidikan_ayah', 'pekerjaan_ayah',
                'penghasilan_ayah', 'kebutuhan_khusus_ayah',
                'nama_ibu', 'tahun_lahir_ibu', 'pendidikan_ibu', 'pekerjaan_ibu',
                'penghasilan_ibu', 'kebutuhan_khusus_ibu',
                'mempunyai_wali', 'nama_wali', 'tahun_lahir_wali', 'pendidikan_wali',
                'pekerjaan_wali', 'penghasilan_wali',
                'tinggi_badan', 'berat_badan', 'jarak_sekolah', 'jarak_sekolah_km',
                'waktu_tempuh', 'jumlah_saudara_kandung',
            ]);
        });
    }
};
