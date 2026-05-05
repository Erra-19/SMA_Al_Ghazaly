<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AlbumController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Album::withCount('medias')->orderBy('order')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'cover'        => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'is_published' => 'boolean',
            'order'        => 'integer|min:0',
        ]);

        $album = Album::create([
            ...$request->only(['title', 'cover', 'description', 'is_published', 'order']),
            'slug' => Str::slug($request->title),
        ]);

        return response()->json($album, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Album::with('medias')->findOrFail($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $album = Album::findOrFail($id);

        $request->validate([
            'title'        => 'sometimes|string|max:255',
            'cover'        => 'nullable|string|max:255',
            'description'  => 'nullable|string',
            'is_published' => 'boolean',
            'order'        => 'integer|min:0',
            'medias'       => 'nullable|array',
            'medias.*'     => 'exists:medias,media_id',
        ]);

        $data = $request->only(['title', 'cover', 'description', 'is_published', 'order']);
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $album->update($data);

        if ($request->has('medias')) {
            $album->medias()->sync($request->medias ?? []);
        }

        return response()->json($album->load('medias'));
    }

    public function destroy(int $id): JsonResponse
    {
        Album::findOrFail($id)->delete();
        return response()->json(['message' => 'Album dihapus.']);
    }
}
