<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\FormSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function show(string $slug): JsonResponse
    {
        $form = Form::where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail(['form_id', 'name', 'slug', 'fields']);

        return response()->json($form);
    }

    public function submit(Request $request, string $slug): JsonResponse
    {
        $form = Form::where('slug', $slug)->where('is_active', 1)->firstOrFail();

        $request->validate([
            'data'            => 'required|array',
            'submitter_email' => 'nullable|email|max:100',
        ]);

        FormSubmission::create([
            'form_id'         => $form->form_id,
            'data'            => $request->data,
            'submitter_ip'    => $request->ip(),
            'submitter_email' => $request->submitter_email,
            'is_read'         => 0,
        ]);

        return response()->json(['message' => 'Pesan berhasil dikirim.'], 201);
    }
}
