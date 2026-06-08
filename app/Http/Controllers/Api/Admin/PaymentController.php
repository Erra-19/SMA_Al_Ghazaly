<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $payments = Payment::with('registration:registration_id,student_name,registration_number')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, fn ($q) => $q->where('order_id', 'like', "%{$request->search}%"))
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($payments);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Payment::with('registration', 'user:id,name,email')->findOrFail($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $payment = Payment::with('registration.payments')->findOrFail($id);

        $request->validate([
            'paid_amount' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,partial,paid,failed,expired,refunded',
            'payment_type' => 'nullable|string|max:50',
            'transaction_id' => 'nullable|string|max:100',
        ]);

        $paidAmount = $request->has('paid_amount')
            ? min((float) $request->paid_amount, (float) $payment->amount)
            : (float) $payment->paid_amount;

        $status = $request->status;
        if (! $status) {
            $status = match (true) {
                $paidAmount >= (float) $payment->amount => 'paid',
                $paidAmount > 0 => 'partial',
                default => 'pending',
            };
        }

        if ($status === 'paid' && $paidAmount <= 0) {
            $paidAmount = (float) $payment->amount;
        }

        $oldStatus = $payment->status;
        $payment->update([
            'paid_amount' => $paidAmount,
            'status' => $status,
            'payment_type' => $request->payment_type ?? $payment->payment_type,
            'transaction_id' => $request->transaction_id ?? $payment->transaction_id,
            'paid_at' => in_array($status, ['partial', 'paid'], true)
                ? ($payment->paid_at ?? now())
                : null,
        ]);

        PaymentHistory::create([
            'payment_id' => $payment->payment_id,
            'order_id' => $payment->order_id,
            'transaction_id' => $payment->transaction_id,
            'old_status' => $oldStatus,
            'new_status' => $status,
            'event_type' => 'admin_update',
            'payload' => $request->all(),
        ]);

        $registration = $payment->fresh('registration.payments')->registration;
        $registration?->syncPaymentSummary();
        $student = $registration?->syncStudentIfReady();

        return response()->json([
            'message' => $student
                ? 'Pembayaran diperbarui dan data murid sudah disinkronkan.'
                : 'Pembayaran diperbarui.',
            'data' => $payment->fresh('registration.student'),
        ]);
    }
}
