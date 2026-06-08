<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Testimonial::latest('testimonial_id')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'         => 'required|string|max:100',
            'role'         => 'nullable|string|max:100',
            'content'      => 'required|string',
            'photo'        => 'nullable|string|max:255',
            'rating'       => 'nullable|integer|min:1|max:5',
            'is_published' => 'boolean',
        ]);

        return response()->json(Testimonial::create($request->only(['name', 'role', 'content', 'photo', 'rating', 'is_published'])), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $testimonial = Testimonial::findOrFail($id);

        $request->validate([
            'name'         => 'sometimes|string|max:100',
            'role'         => 'nullable|string|max:100',
            'content'      => 'sometimes|string',
            'photo'        => 'nullable|string|max:255',
            'rating'       => 'nullable|integer|min:1|max:5',
            'is_published' => 'boolean',
        ]);

        $testimonial->update($request->only(['name', 'role', 'content', 'photo', 'rating', 'is_published']));

        return response()->json($testimonial);
    }

    public function destroy(int $id): JsonResponse
    {
        Testimonial::findOrFail($id)->delete();
        return response()->json(['message' => 'Testimoni dihapus.']);
    }
}
