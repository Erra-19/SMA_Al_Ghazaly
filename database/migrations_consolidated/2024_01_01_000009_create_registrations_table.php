<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Consolidated: create_registrations_table
//   + add_payment_summary_to_registrations
//   + add_extended_fields_to_registrations_table
//   + normalize_registration_columns_for_react_frontend
//   + add_form_data_to_registrations_table

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id('registration_id');
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('registration_number', 50)->unique();

            // ── Identitas Siswa ───────────────────────────────────────────
            $table->string('student_name', 150);
            $table->string('nisn', 20)->nullable();
            $table->string('nik', 16)->nullable();
            $table->string('no_peserta_un', 50)->nullable();
            $table->string('no_skhun', 50)->nullable();
            $table->string('no_ijazah', 50)->nullable();
            $table->string('birth_place', 100)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender', 30)->nullable();
            $table->string('agama', 50)->nullable()->default('Islam');
            $table->string('kebutuhan_khusus', 100)->nullable()->default('Tidak Ada');
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();

            // ── Alamat ────────────────────────────────────────────────────
            $table->text('address')->nullable();
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();
            $table->string('nama_dusun', 100)->nullable();
            $table->string('nama_kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();

            // ── Logistik ──────────────────────────────────────────────────
            $table->string('tinggal_bersama', 50)->nullable()->default('Orang Tua');
            $table->string('transportasi', 50)->nullable()->default('Kendaraan Pribadi');
            $table->string('jarak_sekolah', 20)->nullable();
            $table->unsignedSmallInteger('jarak_sekolah_km')->nullable();
            $table->unsignedSmallInteger('waktu_tempuh')->nullable();

            // ── Fisik ─────────────────────────────────────────────────────
            $table->unsignedSmallInteger('tinggi_badan')->nullable();
            $table->unsignedSmallInteger('berat_badan')->nullable();

            // ── Keluarga — Ayah ───────────────────────────────────────────
            $table->string('nama_ayah', 150)->nullable();
            $table->year('tahun_lahir_ayah')->nullable();
            $table->string('pendidikan_ayah', 100)->nullable();
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->string('penghasilan_ayah', 100)->nullable();
            $table->string('kebutuhan_khusus_ayah', 100)->nullable()->default('Tidak Ada');

            // ── Keluarga — Ibu ────────────────────────────────────────────
            $table->string('nama_ibu', 150)->nullable();
            $table->year('tahun_lahir_ibu')->nullable();
            $table->string('pendidikan_ibu', 100)->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->string('penghasilan_ibu', 100)->nullable();
            $table->string('kebutuhan_khusus_ibu', 100)->nullable()->default('Tidak Ada');

            // ── Keluarga — Wali ───────────────────────────────────────────
            $table->tinyInteger('mempunyai_wali')->default(0);
            $table->string('nama_wali', 150)->nullable();
            $table->year('tahun_lahir_wali')->nullable();
            $table->string('pendidikan_wali', 100)->nullable();
            $table->string('pekerjaan_wali', 100)->nullable();
            $table->string('penghasilan_wali', 100)->nullable();

            // ── Orang tua lama ────────────────────────────────────────────
            $table->string('parent_name', 150)->nullable();
            $table->string('parent_phone', 30)->nullable();
            $table->string('parent_job', 100)->nullable();

            // ── Saudara ───────────────────────────────────────────────────
            $table->tinyInteger('jumlah_saudara_kandung')->default(0);

            // ── Sekolah Asal ──────────────────────────────────────────────
            $table->string('previous_school', 150)->nullable();

            // ── Pendaftaran ───────────────────────────────────────────────
            $table->string('jenis_pendaftaran', 50)->default('Siswa Baru');
            $table->string('academic_year', 20)->nullable();
            $table->string('wave', 50)->nullable();
            $table->string('major_choice', 100)->nullable();
            $table->json('form_data')->nullable();

            // ── Ringkasan Pembayaran ──────────────────────────────────────
            $table->decimal('payment_total_amount', 12, 2)->default(0);
            $table->decimal('payment_paid_amount', 12, 2)->default(0);
            $table->decimal('payment_remaining_amount', 12, 2)->default(0);
            $table->string('payment_status', 30)->default('unpaid');
            $table->string('payment_method', 30)->default('unpaid');

            // ── Status & Workflow ─────────────────────────────────────────
            $table->string('status', 30)->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by')->nullable();

            $table->timestamps();

            $table->index('user_id');
            $table->index('nisn');
            $table->index('status');
            $table->index('payment_status');
            $table->index('payment_method');
            $table->index('academic_year');
            $table->index('wave');
            $table->index('verified_by');

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('verified_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
