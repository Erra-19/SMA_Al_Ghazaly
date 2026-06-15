<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\PaymentHistory;
use App\Models\Registration;
use Illuminate\Database\Seeder;

class PaymentTestSeeder extends Seeder
{
    public function run(): void
    {
        // ── Skenario 1 ─────────────────────────────────────────────────────────
        // Murid baru upload bukti transfer penuh → siap dikonfirmasi lunas
        $this->scenario(
            reg: [
                'registration_number' => 'PPDB-TEST-0001',
                'student_name'        => 'Ahmad Fauzi Pratama',
                'nisn'                => '0091234001',
                'birth_place'         => 'Bogor',
                'birth_date'          => '2010-03-15',
                'gender'              => 'Laki-laki',
                'agama'               => 'Islam',
                'phone'               => '08111000001',
                'email'               => 'fauzi.test@example.com',
                'previous_school'     => 'SMP Negeri 1 Bogor',
                'academic_year'       => '2026/2027',
                'wave'                => 'Gelombang 1',
                'major_choice'        => 'IPA',
                'nama_ayah'           => 'Budi Pratama',
                'nama_ibu'            => 'Siti Rahayu',
                'parent_name'         => 'Budi Pratama',
                'parent_phone'        => '08111000002',
                'status'              => 'submitted',
                'payment_status'      => 'pending',
                'payment_total_amount' => 500000,
                'form_data'           => [
                    'asal_sekolah'     => 'SMP Negeri 1 Bogor',
                    'nilai_rata_rata'  => '88.5',
                    'prestasi'         => 'Juara 2 Olimpiade Matematika Kota Bogor',
                    'hobi'             => 'Membaca, Coding',
                    'pilihan_ekskul'   => 'Robotika',
                    'ukuran_baju'      => 'M',
                    'pekerjaan_ayah'   => 'Wiraswasta',
                    'penghasilan_ayah' => '5.000.000 - 10.000.000',
                ],
            ],
            payment: [
                'order_id'     => 'TEST-PAY-0001',
                'amount'       => 500000,
                'paid_amount'  => 0,
                'status'       => 'pending',
                'payment_type' => 'bank_transfer',
                'proof_url'    => 'https://picsum.photos/seed/bukti1/480/680',
                'metadata'     => ['catatan_pendaftar' => 'Transfer penuh via BCA. Mohon dikonfirmasi.'],
            ],
        );

        // ── Skenario 2 ─────────────────────────────────────────────────────────
        // Murid upload bukti transfer, tapi mau bayar separuh dulu
        $this->scenario(
            reg: [
                'registration_number' => 'PPDB-TEST-0002',
                'student_name'        => 'Siti Nuraini Rahma',
                'nisn'                => '0091234002',
                'birth_place'         => 'Jakarta',
                'birth_date'          => '2010-07-22',
                'gender'              => 'Perempuan',
                'agama'               => 'Islam',
                'phone'               => '08111000003',
                'email'               => 'nuraini.test@example.com',
                'previous_school'     => 'SMP Islam Al-Azhar',
                'academic_year'       => '2026/2027',
                'wave'                => 'Gelombang 1',
                'major_choice'        => 'IPS',
                'nama_ayah'           => 'Rahmat Hidayat',
                'nama_ibu'            => 'Dewi Susanti',
                'parent_name'         => 'Rahmat Hidayat',
                'parent_phone'        => '08111000004',
                'status'              => 'submitted',
                'payment_status'      => 'pending',
                'payment_total_amount' => 500000,
                'form_data'           => [
                    'asal_sekolah'     => 'SMP Islam Al-Azhar',
                    'nilai_rata_rata'  => '90.2',
                    'prestasi'         => 'Juara 1 Lomba Baca Puisi Kabupaten',
                    'hobi'             => 'Menulis, Menggambar',
                    'pilihan_ekskul'   => 'Pramuka, Seni Lukis',
                    'ukuran_baju'      => 'S',
                    'pekerjaan_ayah'   => 'PNS',
                    'penghasilan_ayah' => '4.000.000 - 6.000.000',
                ],
            ],
            payment: [
                'order_id'     => 'TEST-PAY-0002',
                'amount'       => 500000,
                'paid_amount'  => 0,
                'status'       => 'pending',
                'payment_type' => 'bank_transfer',
                'proof_url'    => 'https://picsum.photos/seed/bukti2/480/680',
                'metadata'     => ['catatan_pendaftar' => 'Baru bisa bayar Rp 250.000 dulu, sisanya minggu depan.'],
            ],
        );

        // ── Skenario 3 ─────────────────────────────────────────────────────────
        // Murid upload bukti transfer penuh, masuk gelombang 2
        $this->scenario(
            reg: [
                'registration_number' => 'PPDB-TEST-0003',
                'student_name'        => 'Rizky Maulana Akbar',
                'nisn'                => '0091234003',
                'birth_place'         => 'Depok',
                'birth_date'          => '2009-11-05',
                'gender'              => 'Laki-laki',
                'agama'               => 'Islam',
                'phone'               => '08111000005',
                'email'               => 'rizky.test@example.com',
                'previous_school'     => 'SMP Negeri 3 Depok',
                'academic_year'       => '2026/2027',
                'wave'                => 'Gelombang 2',
                'major_choice'        => 'IPA',
                'nama_ayah'           => 'Hendra Akbar',
                'nama_ibu'            => 'Rina Wulandari',
                'parent_name'         => 'Hendra Akbar',
                'parent_phone'        => '08111000006',
                'status'              => 'submitted',
                'payment_status'      => 'pending',
                'payment_total_amount' => 500000,
                'form_data'           => [
                    'asal_sekolah'     => 'SMP Negeri 3 Depok',
                    'nilai_rata_rata'  => '93.7',
                    'prestasi'         => 'Juara 1 OSN Fisika Tingkat Kota',
                    'hobi'             => 'Basket, Programming',
                    'pilihan_ekskul'   => 'Basket, Robotika',
                    'ukuran_baju'      => 'L',
                    'pekerjaan_ayah'   => 'Dokter',
                    'penghasilan_ayah' => '> 10.000.000',
                ],
            ],
            payment: [
                'order_id'     => 'TEST-PAY-0003',
                'amount'       => 500000,
                'paid_amount'  => 0,
                'status'       => 'pending',
                'payment_type' => 'bank_transfer',
                'proof_url'    => 'https://picsum.photos/seed/bukti3/480/680',
                'metadata'     => ['catatan_pendaftar' => 'Transfer via Mandiri. Nomor ref: MDR2026061200003.'],
            ],
        );

        $this->command->info('PaymentTestSeeder: 3 pendaftar + bukti transfer siap diverifikasi.');
        $this->command->line('  [1] PPDB-TEST-0001 — Ahmad Fauzi Pratama   (transfer penuh)');
        $this->command->line('  [2] PPDB-TEST-0002 — Siti Nuraini Rahma   (ingin bayar cicilan)');
        $this->command->line('  [3] PPDB-TEST-0003 — Rizky Maulana Akbar  (transfer penuh, gel. 2)');
        $this->command->line('  Semua status: pending, ada bukti, belum dikonfirmasi.');
    }

    // ──────────────────────────────────────────────────────────────────────────

    private function scenario(array $reg, array $payment): Registration
    {
        // Hapus data lama jika ada (idempotent)
        $old = Registration::where('registration_number', $reg['registration_number'])->first();
        if ($old) {
            Payment::where('registration_id', $old->registration_id)->each(function ($p) {
                PaymentHistory::where('payment_id', $p->payment_id)->delete();
                $p->delete();
            });
            $old->delete();
        }

        $registration = Registration::create(array_merge([
            'user_id'      => null,
            'submitted_at' => now()->subHours(rand(1, 48)),
        ], $reg));

        Payment::create(array_merge([
            'registration_id' => $registration->registration_id,
            'user_id'         => null,
            'paid_at'         => null,
        ], $payment));

        return $registration;
    }
}
