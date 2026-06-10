<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use Throwable;

class RegistrationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'student_name'    => 'required|string|max:150',
            'nisn'            => 'nullable|string|max:20',
            'nik'             => 'nullable|string|max:20',
            'birth_place'     => 'nullable|string|max:100',
            'birth_date'      => 'nullable|date',
            'gender'          => 'nullable|in:Laki-laki,Perempuan',
            'agama'           => 'nullable|string|max:30',
            'kebutuhan_khusus' => 'nullable|string|max:50',
            'address'         => 'nullable|string',
            'rt'              => 'nullable|string|max:10',
            'rw'              => 'nullable|string|max:10',
            'nama_kelurahan'  => 'nullable|string|max:100',
            'kecamatan'       => 'nullable|string|max:100',
            'kode_pos'        => 'nullable|string|max:10',
            'tinggal_bersama' => 'nullable|string|max:50',
            'transportasi'    => 'nullable|string|max:50',
            'phone'           => 'nullable|string|max:30',
            'email'           => 'nullable|email|max:100',
            'previous_school' => 'nullable|string|max:150',
            'academic_year'   => 'nullable|string|max:20',
            'wave'            => 'nullable|string|max:50',
            'major_choice'    => 'nullable|string|max:100',
            'jenis_pendaftaran' => 'nullable|string|max:50',
            'no_peserta_un'   => 'nullable|string|max:30',
            'no_skhun'        => 'nullable|string|max:30',
            'no_ijazah'       => 'nullable|string|max:30',
            'nama_ayah'       => 'nullable|string|max:150',
            'tahun_lahir_ayah' => 'nullable|string|max:4',
            'pendidikan_ayah' => 'nullable|string|max:50',
            'pekerjaan_ayah'  => 'nullable|string|max:100',
            'penghasilan_ayah' => 'nullable|string|max:100',
            'nama_ibu'        => 'nullable|string|max:150',
            'tahun_lahir_ibu' => 'nullable|string|max:4',
            'pendidikan_ibu'  => 'nullable|string|max:50',
            'pekerjaan_ibu'   => 'nullable|string|max:100',
            'penghasilan_ibu' => 'nullable|string|max:100',
            'mempunyai_wali'  => 'nullable|boolean',
            'nama_wali'       => 'nullable|string|max:150',
            'tahun_lahir_wali' => 'nullable|string|max:4',
            'pekerjaan_wali'  => 'nullable|string|max:100',
            'tinggi_badan'    => 'nullable|numeric',
            'berat_badan'     => 'nullable|numeric',
            'jarak_sekolah'   => 'nullable|string|max:30',
            'jarak_sekolah_km' => 'nullable|numeric',
            'waktu_tempuh'    => 'nullable|numeric',
            'jumlah_saudara_kandung' => 'nullable|integer',
            'parent_name'     => 'nullable|string|max:150',
            'parent_phone'    => 'nullable|string|max:30',
            'parent_job'      => 'nullable|string|max:100',
        ]);

        $fields = [
            'student_name','nisn','nik','birth_place','birth_date','gender',
            'agama','kebutuhan_khusus','address','rt','rw',
            'nama_kelurahan','kecamatan','kode_pos','tinggal_bersama','transportasi',
            'phone','email','previous_school','academic_year','wave','major_choice',
            'jenis_pendaftaran','no_peserta_un','no_skhun','no_ijazah',
            'nama_ayah','tahun_lahir_ayah','pendidikan_ayah','pekerjaan_ayah','penghasilan_ayah',
            'nama_ibu','tahun_lahir_ibu','pendidikan_ibu','pekerjaan_ibu','penghasilan_ibu',
            'mempunyai_wali','nama_wali','tahun_lahir_wali','pekerjaan_wali',
            'tinggi_badan','berat_badan','jarak_sekolah','jarak_sekolah_km',
            'waktu_tempuh','jumlah_saudara_kandung',
            'parent_name','parent_phone','parent_job',
        ];

        $data = [
            ...$request->only($fields),
            'registration_number' => $this->generateRegistrationNumber(),
            'status'       => 'submitted',
            'submitted_at' => now(),
        ];

        if (Schema::hasColumn('registrations', 'full_name')) {
            $data['full_name'] = $data['student_name'];
        }

        $registration = Registration::create($data);

        return response()->json([
            'message'             => 'Pendaftaran berhasil. Simpan nomor pendaftaran Anda.',
            'registration_number' => $registration->registration_number,
            'registration_id'     => $registration->registration_id,
        ], 201);
    }

    public function uploadDocuments(Request $request, int $id): JsonResponse
    {
        $registration = Registration::findOrFail($id);
        $request->validate([
            'documents'        => 'required|array|min:1',
            'documents.*.file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'documents.*.type' => 'required|string|max:100',
        ]);
        DB::transaction(function () use ($request, $registration) {
            foreach ($request->input('documents', []) as $index => $item) {
                $file = $request->file("documents.$index.file");
                $path = $file->store('ppdb-documents', 'public');
                RegistrationDocument::create([
                    'registration_id' => $registration->registration_id,
                    'document_type'   => $item['type'],
                    'file_path'       => $path,
                    'original_name'   => $file->getClientOriginalName(),
                    'mime_type'       => $file->getMimeType(),
                    'file_size'       => $file->getSize(),
                    'status'          => 'pending',
                ]);
            }
            if (in_array($registration->status, ['draft', 'submitted'], true)) {
                $registration->update(['status' => 'document_review']);
            }
        });
        return response()->json(['message' => 'Dokumen berhasil diupload.']);
    }

    public function checkStatus(string $number): JsonResponse
    {
        $registration = Registration::where('registration_number', $number)
            ->with([
                'payment:payment_id,registration_id,status,amount,paid_amount,paid_at',
                'payments:payment_id,registration_id,order_id,status,amount,paid_amount,paid_at,expired_at',
                'documents:document_id,registration_id,document_type,status,notes,verified_at',
            ])
            ->firstOrFail([
                'registration_id','registration_number','student_name',
                'academic_year','wave','major_choice',
                'payment_total_amount','payment_paid_amount','payment_remaining_amount',
                'payment_status','payment_method','status',
                'submitted_at','verified_at','created_at',
            ]);
        $registration->setAttribute('payment_summary', $registration->syncPaymentSummary());
        return response()->json($registration);
    }

    public function createPayment(Request $request, int $id): JsonResponse
    {
        $registration = Registration::findOrFail($id);
        $request->validate(['installment_percent' => 'nullable|integer|in:10,25,50,100']);
        if (! in_array($registration->status, ['submitted','document_review','verified','accepted'], true)) {
            return response()->json(['message' => 'Pendaftaran belum dapat dibayar.'], 422);
        }
        $summary          = $registration->load('payments')->syncPaymentSummary();
        $totalAmount      = (float) $summary['total_amount'];
        $remainingAmount  = (float) $summary['remaining_amount'];
        $percent          = (int) ($request->installment_percent ?: 100);
        $paymentMethod    = $percent === 100 ? 'full' : 'installment';
        $installmentAmount = $percent === 100
            ? $remainingAmount
            : min($remainingAmount, round($remainingAmount * ($percent / 100), 2));
        if ($totalAmount <= 0) {
            $payment = Payment::firstOrCreate(
                ['registration_id' => $registration->registration_id, 'payment_type' => 'free'],
                ['user_id' => $registration->user_id, 'order_id' => $this->generateOrderId($registration),
                 'amount' => 0, 'paid_amount' => 0, 'status' => 'pending',
                 'metadata' => ['registration_number' => $registration->registration_number,
                                'student_name' => $registration->student_name, 'installment_percent' => 100]]
            );
            $payment->update(['status' => 'paid', 'paid_amount' => 0, 'paid_at' => now(), 'payment_type' => 'free']);
            if (in_array($registration->status, ['submitted','document_review'], true)) {
                $registration->update(['status' => 'verified']);
            }
            $registration->fresh('payments','student')->syncPaymentSummary('free');
            $registration->fresh('payments','student')->syncStudentIfReady();
            return response()->json($payment->fresh());
        }
        if ($remainingAmount <= 0) {
            return response()->json(['message' => 'Pembayaran PPDB sudah lunas.', 'payment_summary' => $summary], 422);
        }
        $payment = Payment::create([
            'user_id' => $registration->user_id,
            'registration_id' => $registration->registration_id,
            'order_id' => $this->generateOrderId($registration),
            'amount' => $installmentAmount, 'paid_amount' => 0, 'status' => 'pending',
            'metadata' => ['registration_number' => $registration->registration_number,
                           'student_name' => $registration->student_name,
                           'installment_percent' => $percent, 'payment_method' => $paymentMethod,
                           'remaining_before_payment' => $remainingAmount, 'total_amount' => $totalAmount],
        ]);
        $registration->fresh('payments')->syncPaymentSummary($paymentMethod);
        if (config('services.midtrans.server_key')) {
            MidtransConfig::$serverKey      = config('services.midtrans.server_key');
            MidtransConfig::$isProduction   = (bool) config('services.midtrans.is_production');
            MidtransConfig::$isSanitized    = (bool) config('services.midtrans.is_sanitized');
            MidtransConfig::$is3ds          = (bool) config('services.midtrans.is_3ds');
            try {
                $payment->update(['snap_token' => Snap::getSnapToken([
                    'transaction_details' => ['order_id' => $payment->order_id,
                                              'gross_amount' => (int) round($payment->amount)],
                    'customer_details'    => ['first_name' => $registration->student_name,
                                              'phone' => $registration->phone],
                    'item_details'        => [['id' => 'ppdb-registration-installment',
                                               'price' => (int) round($payment->amount), 'quantity' => 1,
                                               'name' => "Cicilan PPDB {$percent}%"]],
                ])]);
            } catch (Throwable $e) {
                return response()->json(['message' => 'Gagal membuat token pembayaran.',
                                         'error' => $e->getMessage(), 'payment' => $payment], 502);
            }
        }
        return response()->json([...$payment->fresh()->toArray(),
            'payment_summary' => $registration->fresh('payments')->syncPaymentSummary($paymentMethod)]);
    }

    private function generateRegistrationNumber(): string
    {
        do { $number = 'PPDB-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (Registration::where('registration_number', $number)->exists());
        return $number;
    }

    private function generateOrderId(Registration $registration): string
    {
        return 'PPDB-PAY-' . $registration->registration_id . '-' . strtoupper(Str::random(6));
    }
}
