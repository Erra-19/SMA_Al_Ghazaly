<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $alumni = Alumnus::with('testimonial:testimonial_id,alumnus_id,content,rating,is_published')
            ->when($request->year, fn ($q) => $q->where('graduation_year', $request->year))
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
            'occupation'          => 'nullable|string|max:150',
            'location'            => 'nullable|string|max:100',
            'story'               => 'nullable|string',
            'current_institution' => 'nullable|string|max:150',
            'major'               => 'nullable|string|max:100',
            'achievement'         => 'nullable|string',
            'is_published'        => 'boolean',
        ]);

        return response()->json(Alumnus::create([
            ...$request->only(['name', 'graduation_year', 'photo', 'major', 'is_published']),
            'current_institution' => $request->input('current_institution', $request->input('occupation')),
            'achievement' => $request->input('achievement', $request->input('story')),
        ]), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $alumni = Alumnus::findOrFail($id);

        $request->validate([
            'name'                => 'sometimes|string|max:100',
            'graduation_year'     => 'sometimes|integer|digits:4',
            'photo'               => 'nullable|string|max:255',
            'occupation'          => 'nullable|string|max:150',
            'location'            => 'nullable|string|max:100',
            'story'               => 'nullable|string',
            'current_institution' => 'nullable|string|max:150',
            'major'               => 'nullable|string|max:100',
            'achievement'         => 'nullable|string',
            'is_published'        => 'boolean',
        ]);

        $data = $request->only(['name', 'graduation_year', 'photo', 'current_institution', 'major', 'achievement', 'is_published']);
        if ($request->has('occupation')) {
            $data['current_institution'] = $request->occupation;
        }
        if ($request->has('story')) {
            $data['achievement'] = $request->story;
        }

        $alumni->update($data);

        return response()->json($alumni->load('testimonial:testimonial_id,alumnus_id,content,rating,is_published'));
    }

    public function destroy(int $id): JsonResponse
    {
        Alumnus::findOrFail($id)->delete();
        return response()->json(['message' => 'Data alumni dihapus.']);
    }
}
