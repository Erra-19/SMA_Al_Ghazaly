<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class RegistrationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $registrations = Registration::with('payment:payment_id,registration_id,status,amount')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->academic_year, fn ($q) => $q->where('academic_year', $request->academic_year))
            ->when($request->search, fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('full_name', 'like', "%{$request->search}%")
                  ->orWhere('registration_number', 'like', "%{$request->search}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($registrations);
    }

    public function show(int $id): JsonResponse
    {
        $registration = Registration::with('documents.media', 'payment')->findOrFail($id);
        return response()->json($registration);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $registration = Registration::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,verified,accepted,rejected',
            'notes'  => 'nullable|string',
        ]);

        $registration->update($request->only('status', 'notes'));

        // Kirim notifikasi email ke pendaftar
        // Notification::route('mail', $registration->parent_email)
        //     ->notify(new RegistrationStatusUpdated($registration));

        return response()->json(['message' => 'Status pendaftaran diperbarui.', 'data' => $registration]);
    }
}
