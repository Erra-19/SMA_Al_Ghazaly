<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Teacher::orderBy('order')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'nip'       => 'nullable|string|max:50|unique:teachers,nip',
            'name'      => 'required|string|max:100',
            'position'  => 'required|string|max:100',
            'subject'   => 'nullable|string|max:100',
            'photo'     => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:30',
            'email'     => 'nullable|email|max:100',
            'bio'       => 'nullable|string',
            'order'     => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $teacher = Teacher::create($request->only([
            'nip',
            'name',
            'position',
            'subject',
            'photo',
            'phone',
            'email',
            'bio',
            'order',
            'is_active',
        ]));

        return response()->json($teacher, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Teacher::findOrFail($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $teacher = Teacher::findOrFail($id);

        $request->validate([
            'nip'       => "nullable|string|max:50|unique:teachers,nip,{$id},teacher_id",
            'name'      => 'sometimes|string|max:100',
            'position'  => 'sometimes|string|max:100',
            'subject'   => 'nullable|string|max:100',
            'photo'     => 'nullable|string|max:255',
            'phone'     => 'nullable|string|max:30',
            'email'     => 'nullable|email|max:100',
            'bio'       => 'nullable|string',
            'order'     => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $teacher->update($request->only([
            'nip',
            'name',
            'position',
            'subject',
            'photo',
            'phone',
            'email',
            'bio',
            'order',
            'is_active',
        ]));

        return response()->json($teacher);
    }

    public function destroy(int $id): JsonResponse
    {
        Teacher::findOrFail($id)->delete();
        return response()->json(['message' => 'Data guru dihapus.']);
    }
}
