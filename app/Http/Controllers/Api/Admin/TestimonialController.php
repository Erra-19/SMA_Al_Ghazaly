<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumnus;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Testimonial::with('alumnus:alumnus_id,name,photo,current_institution,major,graduation_year')
                ->latest('testimonial_id')
                ->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'alumnus_id'     => 'nullable|integer|exists:alumni,alumnus_id',
            'name'           => 'required|string|max:100',
            'role'           => 'nullable|string|max:100',
            'content'        => 'required|string',
            'photo'          => 'nullable|string|max:255',
            'rating'         => 'nullable|integer|min:1|max:5',
            'university'     => 'nullable|string|max:150',
            'major'          => 'nullable|string|max:150',
            'graduation_year'=> 'nullable|integer',
            'is_published'   => 'boolean',
        ]);

        $data = $request->only([
            'alumnus_id', 'name', 'role', 'content', 'photo',
            'rating', 'university', 'major', 'graduation_year', 'is_published',
        ]);

        // Auto-sync from alumnus when linked
        if (!empty($data['alumnus_id'])) {
            $data = $this->syncFromAlumnus($data['alumnus_id'], $data);
        }

        return response()->json(Testimonial::create($data), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'alumnus_id'     => 'nullable|integer|exists:alumni,alumnus_id',
            'name'           => 'sometimes|string|max:100',
            'role'           => 'nullable|string|max:100',
            'content'        => 'sometimes|string',
            'photo'          => 'nullable|string|max:255',
            'rating'         => 'nullable|integer|min:1|max:5',
            'university'     => 'nullable|string|max:150',
            'major'          => 'nullable|string|max:150',
            'graduation_year'=> 'nullable|integer',
            'is_published'   => 'boolean',
        ]);

        $data = $request->only([
            'alumnus_id', 'name', 'role', 'content', 'photo',
            'rating', 'university', 'major', 'graduation_year', 'is_published',
        ]);

        // Auto-sync from alumnus when linked (or re-linked)
        $newAlumnusId = $request->input('alumnus_id');
        if ($newAlumnusId && $newAlumnusId != $testimonial->alumnus_id) {
            $data = $this->syncFromAlumnus($newAlumnusId, $data);
        }

        $testimonial->update($data);

        return response()->json($testimonial->load('alumnus:alumnus_id,name,photo,current_institution,major,graduation_year'));
    }

    public function destroy(int $id): JsonResponse
    {
        Testimonial::findOrFail($id)->delete();
        return response()->json(['message' => 'Testimoni dihapus.']);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function syncFromAlumnus(int $alumnusId, array $data): array
    {
        $alumnus = Alumnus::find($alumnusId);
        if (!$alumnus) return $data;

        // Auto-fill from alumnus if the field is empty
        if (empty($data['name'])) {
            $data['name'] = $alumnus->name;
        }
        if (empty($data['photo'])) {
            $data['photo'] = $alumnus->photo;
        }
        if (empty($data['university'])) {
            $data['university'] = $alumnus->current_institution;
        }
        if (empty($data['major'])) {
            $data['major'] = $alumnus->major;
        }
        if (empty($data['graduation_year'])) {
            $data['graduation_year'] = $alumnus->graduation_year;
        }

        return $data;
    }
}
