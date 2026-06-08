<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\RegistrationDocument;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $registrations = Registration::with('payment:payment_id,registration_id,status,amount,paid_amount')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->academic_year, fn ($q) => $q->where('academic_year', $request->academic_year))
            ->when($request->wave, fn ($q) => $q->where('wave', $request->wave))
            ->when($request->search, fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('student_name', 'like', "%{$request->search}%")
                  ->orWhere('nisn', 'like', "%{$request->search}%")
                  ->orWhere('registration_number', 'like', "%{$request->search}%");
            }))
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($registrations);
    }

    public function show(int $id): JsonResponse
    {
        $registration = Registration::with([
            'documents.verifier:id,name,email',
            'payment',
            'payments',
            'user:id,name,email',
            'verifier:id,name,email',
            'student',
        ])->findOrFail($id);

        return response()->json($registration);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $registration = Registration::findOrFail($id);

        $request->validate([
            'status' => 'required|in:draft,submitted,document_review,verified,accepted,rejected',
            'nis' => 'nullable|string|max:30|unique:students,nis',
            'grade_level' => 'nullable|string|max:20',
            'major' => 'nullable|string|max:100',
        ]);

        $data = $request->only('status');
        if (in_array($data['status'], ['verified', 'accepted'], true)) {
            $data['verified_at'] = now();
            $data['verified_by'] = $request->user()?->id;
        }

        $registration->update($data);

        $freshRegistration = $registration->fresh('payments', 'student');
        $student = $freshRegistration->syncStudentIfReady($request->only('nis', 'grade_level', 'major'));

        return response()->json([
            'message' => $student
                ? 'Status pendaftaran diperbarui dan data murid sudah disinkronkan.'
                : 'Status pendaftaran diperbarui. Data murid akan dibuat setelah pendaftaran diterima dan pembayaran terkonfirmasi.',
            'data' => $freshRegistration->fresh('payment', 'student'),
        ]);
    }

    public function updateDocumentStatus(Request $request, int $id, int $documentId): JsonResponse
    {
        $registration = Registration::with('documents')->findOrFail($id);
        $document = RegistrationDocument::where('registration_id', $registration->registration_id)
            ->where('document_id', $documentId)
            ->firstOrFail();

        $request->validate([
            'status' => 'required|in:pending,verified,rejected',
            'notes' => 'nullable|string',
        ]);

        $document->update([
            'status' => $request->status,
            'notes' => $request->notes,
            'verified_at' => $request->status === 'pending' ? null : now(),
            'verified_by' => $request->status === 'pending' ? null : $request->user()?->id,
        ]);

        $documents = $registration->documents()->get();
        if ($documents->isNotEmpty() && $documents->every(fn ($item) => $item->status === 'verified')) {
            $registration->update([
                'status' => 'verified',
                'verified_at' => now(),
                'verified_by' => $request->user()?->id,
            ]);
        } elseif ($registration->status === 'submitted') {
            $registration->update(['status' => 'document_review']);
        }

        return response()->json([
            'message' => 'Status dokumen diperbarui.',
            'data' => $document->fresh('verifier'),
        ]);
    }
}
