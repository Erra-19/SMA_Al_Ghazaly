<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormSubmissionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $submissions = FormSubmission::with('form:form_id,name')
            ->when($request->form_id, fn ($q) => $q->where('form_id', $request->form_id))
            ->when($request->is_read !== null, fn ($q) => $q->where('is_read', $request->boolean('is_read')))
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($submissions);
    }

    public function show(int $id): JsonResponse
    {
        $submission = FormSubmission::with('form')->findOrFail($id);
        $submission->update(['is_read' => 1]);

        return response()->json($submission);
    }

    public function destroy(int $id): JsonResponse
    {
        FormSubmission::findOrFail($id)->delete();
        return response()->json(['message' => 'Pesan dihapus.']);
    }
}
