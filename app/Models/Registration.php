<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Registration extends Model
{
	protected $primaryKey = 'registration_id';

    protected $fillable = [
        'user_id',
        'registration_number',
        'student_name',
        'nisn',
        'birth_place',
        'birth_date',
        'gender',
        'address',
        'phone',
        'previous_school',
        'parent_name',
        'parent_phone',
        'parent_job',
        'academic_year',
        'wave',
        'major_choice',
        'payment_total_amount',
        'payment_paid_amount',
        'payment_remaining_amount',
        'payment_status',
        'payment_method',
        'status',
        'submitted_at',
        'verified_at',
        'verified_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'payment_total_amount' => 'decimal:2',
        'payment_paid_amount' => 'decimal:2',
        'payment_remaining_amount' => 'decimal:2',
        'submitted_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by', 'id');
    }

    public function documents()
    {
        return $this->hasMany(RegistrationDocument::class, 'registration_id', 'registration_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'registration_id', 'registration_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'registration_id', 'registration_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class, 'registration_id', 'registration_id');
    }

    public function hasConfirmedPayment(): bool
    {
        if (in_array($this->payment_status, ['partial', 'paid', 'free'], true)) {
            return true;
        }

        if (! $this->relationLoaded('payments')) {
            $this->load('payments');
        }

        return $this->payments->contains(function (Payment $payment) {
            return in_array($payment->status, ['paid', 'partial'], true)
                || (float) $payment->paid_amount > 0
                || ($payment->paid_at !== null && (float) $payment->amount <= 0);
        });
    }

    public function isReadyToBecomeStudent(): bool
    {
        return $this->status === 'accepted' && $this->hasConfirmedPayment();
    }

    public function paymentSummary(?float $totalAmount = null): array
    {
        if (! $this->relationLoaded('payments')) {
            $this->load('payments');
        }

        $totalAmount ??= (float) (Setting::where('key', 'ppdb_registration_fee')->value('value') ?? 0);
        $paidAmount = $this->payments
            ->filter(fn (Payment $payment) => in_array($payment->status, ['partial', 'paid'], true))
            ->sum(fn (Payment $payment) => (float) ($payment->paid_amount ?: ($payment->status === 'paid' ? $payment->amount : 0)));
        $paidAmount = min($paidAmount, $totalAmount);
        $remainingAmount = max($totalAmount - $paidAmount, 0);

        $paymentStatus = match (true) {
            $totalAmount <= 0 => 'free',
            $paidAmount >= $totalAmount => 'paid',
            $paidAmount > 0 => 'partial',
            default => 'unpaid',
        };

        return [
            'total_amount' => $totalAmount,
            'paid_amount' => $paidAmount,
            'remaining_amount' => $remainingAmount,
            'payment_status' => $paymentStatus,
            'payment_method' => $this->resolvePaymentMethod($paymentStatus),
            'installment_options' => $this->installmentOptions($remainingAmount),
        ];
    }

    public function syncPaymentSummary(?string $paymentMethod = null, ?float $totalAmount = null): array
    {
        $summary = $this->paymentSummary($totalAmount);
        $method = $paymentMethod ?? $summary['payment_method'];

        $this->forceFill([
            'payment_total_amount' => $summary['total_amount'],
            'payment_paid_amount' => $summary['paid_amount'],
            'payment_remaining_amount' => $summary['remaining_amount'],
            'payment_status' => $summary['payment_status'],
            'payment_method' => $method,
        ])->save();

        return [
            ...$summary,
            'payment_method' => $method,
        ];
    }

    public function syncStudentIfReady(array $studentData = []): ?Student
    {
        if (! $this->isReadyToBecomeStudent()) {
            return null;
        }

        $student = $this->student()->first();

        return Student::updateOrCreate(
            ['registration_id' => $this->registration_id],
            [
                'user_id' => $this->user_id,
                'nis' => $studentData['nis'] ?? $student?->nis ?? $this->generateNis(),
                'nisn' => $this->nisn,
                'name' => $this->student_name,
                'gender' => $this->gender,
                'birth_place' => $this->birth_place,
                'birth_date' => $this->birth_date,
                'address' => $this->address,
                'phone' => $this->phone,
                'parent_name' => $this->parent_name,
                'parent_phone' => $this->parent_phone,
                'previous_school' => $this->previous_school,
                'academic_year' => $this->academic_year,
                'grade_level' => $studentData['grade_level'] ?? $student?->grade_level,
                'major' => $studentData['major'] ?? $student?->major ?? $this->major_choice,
                'status' => $studentData['status'] ?? $student?->status ?? 'active',
            ]
        );
    }

    public function generateNis(): string
    {
        return date('Y') . str_pad((string) $this->registration_id, 5, '0', STR_PAD_LEFT);
    }

    private function resolvePaymentMethod(string $paymentStatus): string
    {
        if ($paymentStatus === 'free') {
            return 'free';
        }

        if ($paymentStatus === 'unpaid') {
            return $this->payment_method && $this->payment_method !== 'unpaid'
                ? $this->payment_method
                : 'unpaid';
        }

        if ($this->payment_method && ! in_array($this->payment_method, ['unpaid', 'free'], true)) {
            return $this->payment_method;
        }

        return $paymentStatus === 'paid' ? 'full' : 'installment';
    }

    private function installmentOptions(float $remainingAmount): array
    {
        if ($remainingAmount <= 0) {
            return [];
        }

        return collect([10, 25, 50])
            ->map(fn (int $percent) => [
                'percent' => $percent,
                'amount' => round($remainingAmount * ($percent / 100), 2),
            ])
            ->filter(fn (array $option) => $option['amount'] > 0)
            ->push([
                'percent' => 100,
                'amount' => round($remainingAmount, 2),
            ])
            ->values()
            ->all();
    }
}
