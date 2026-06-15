<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Form::orderBy('name')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'        => 'required|string|max:150',
            'slug'        => 'required|string|max:120|unique:forms,slug',
            'type'        => 'nullable|in:ppdb,contact,general',
            'fields'      => 'nullable|array',
            'steps'       => 'nullable|array',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $form = Form::create($data);
        return response()->json($form, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $form = Form::findOrFail($id);

        $data = $request->validate([
            'name'        => 'sometimes|string|max:150',
            'slug'        => "sometimes|string|max:120|unique:forms,slug,{$id},form_id",
            'type'        => 'nullable|in:ppdb,contact,general',
            'fields'      => 'nullable|array',
            'steps'       => 'nullable|array',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $form->update($data);
        return response()->json($form->fresh());
    }

    public function activate(int $id): JsonResponse
    {
        $form = Form::findOrFail($id);

        // Deactivate all forms of the same type, then activate only this one
        Form::where('type', $form->type)->update(['is_active' => 0]);
        $form->update(['is_active' => 1]);

        return response()->json(['message' => 'Form diaktifkan.', 'form' => $form->fresh()]);
    }

    public function destroy(int $id): JsonResponse
    {
        Form::findOrFail($id)->delete();
        return response()->json(['message' => 'Form dihapus.']);
    }
}
