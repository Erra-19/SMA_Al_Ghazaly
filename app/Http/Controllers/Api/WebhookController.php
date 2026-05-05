<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function midtrans(Request $request): JsonResponse
    {
        $payload = $request->all();

        // Verifikasi signature Midtrans
        $serverKey    = config('services.midtrans.server_key');
        $orderId      = $payload['order_id'] ?? '';
        $statusCode   = $payload['status_code'] ?? '';
        $grossAmount  = $payload['gross_amount'] ?? '';
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== ($payload['signature_key'] ?? '')) {
            Log::warning('Midtrans webhook: signature tidak valid', ['order_id' => $orderId]);
            return response()->json(['message' => 'Invalid signature.'], 403);
        }

        $payment = Payment::where('order_id', $orderId)->first();

        if (! $payment) {
            return response()->json(['message' => 'Order tidak ditemukan.'], 404);
        }

        $transactionStatus = $payload['transaction_status'];
        $paymentStatus = match ($transactionStatus) {
            'capture', 'settlement' => 'paid',
            'cancel', 'deny'        => 'failed',
            'expire'                => 'expired',
            default                 => $payment->status,
        };

        $payment->update([
            'transaction_id' => $payload['transaction_id'] ?? $payment->transaction_id,
            'payment_type'   => $payload['payment_type'] ?? $payment->payment_type,
            'status'         => $paymentStatus,
            'paid_at'        => $paymentStatus === 'paid' ? now() : $payment->paid_at,
        ]);

        // Update status registrasi jika pembayaran lunas
        if ($paymentStatus === 'paid' && $payment->registration_id) {
            Registration::where('registration_id', $payment->registration_id)
                ->where('status', 'pending')
                ->update(['status' => 'verified']);
        }

        return response()->json(['message' => 'OK']);
    }
}
