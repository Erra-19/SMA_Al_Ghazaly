<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $alumni = Alumni::when($request->year, fn ($q) => $q->where('graduation_year', $request->year))
            ->orderByDesc('graduation_year')
            ->paginate(15);

        return response()->json($alumni);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'                => 'required|string|max:100',
            'graduation_year'     => 'required|integer|digits:4',
            'photo'               => 'nullable|string|max:255',
            'current_institution' => 'nullable|string|max:150',
            'major'               => 'nullable|string|max:100',
            'achievement'         => 'nullable|string',
            'is_published'        => 'boolean',
        ]);

        return response()->json(Alumni::create($request->only(['name', 'graduation_year', 'photo', 'current_institution', 'major', 'achievement', 'is_published'])), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $alumni = Alumni::findOrFail($id);

        $request->validate([
            'name'                => 'sometimes|string|max:100',
            'graduation_year'     => 'sometimes|integer|digits:4',
            'photo'               => 'nullable|string|max:255',
            'current_institution' => 'nullable|string|max:150',
            'major'               => 'nullable|string|max:100',
            'achievement'         => 'nullable|string',
            'is_published'        => 'boolean',
        ]);

        $alumni->update($request->only(['name', 'graduation_year', 'photo', 'current_institution', 'major', 'achievement', 'is_published']));

        return response()->json($alumni);
    }

    public function destroy(int $id): JsonResponse
    {
        Alumni::findOrFail($id)->delete();
        return response()->json(['message' => 'Data alumni dihapus.']);
    }
}
