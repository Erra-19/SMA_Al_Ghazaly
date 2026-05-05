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
            'name'      => 'required|string|max:100',
            'position'  => 'required|string|max:100',
            'subject'   => 'nullable|string|max:100',
            'photo'     => 'nullable|string|max:255',
            'bio'       => 'nullable|string',
            'order'     => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $teacher = Teacher::create($request->only(['name', 'position', 'subject', 'photo', 'bio', 'order', 'is_active']));

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
            'name'      => 'sometimes|string|max:100',
            'position'  => 'sometimes|string|max:100',
            'subject'   => 'nullable|string|max:100',
            'photo'     => 'nullable|string|max:255',
            'bio'       => 'nullable|string',
            'order'     => 'integer|min:0',
            'is_active' => 'boolean',
        ]);

        $teacher->update($request->only(['name', 'position', 'subject', 'photo', 'bio', 'order', 'is_active']));

        return response()->json($teacher);
    }

    public function destroy(int $id): JsonResponse
    {
        Teacher::findOrFail($id)->delete();
        return response()->json(['message' => 'Data guru dihapus.']);
    }
}
