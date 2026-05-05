<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Page::orderBy('order')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'thumbnail'        => 'nullable|string|max:255',
            'is_published'     => 'boolean',
            'order'            => 'integer|min:0',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:255',
        ]);

        $page = Page::create([
            ...$request->only(['title', 'content', 'thumbnail', 'is_published', 'order', 'meta_title', 'meta_description']),
            'slug' => Str::slug($request->title),
        ]);

        return response()->json($page, 201);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json(Page::findOrFail($id));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $page = Page::findOrFail($id);

        $request->validate([
            'title'            => 'sometimes|string|max:255',
            'content'          => 'sometimes|string',
            'thumbnail'        => 'nullable|string|max:255',
            'is_published'     => 'boolean',
            'order'            => 'integer|min:0',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:255',
        ]);

        $data = $request->only(['title', 'content', 'thumbnail', 'is_published', 'order', 'meta_title', 'meta_description']);
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $page->update($data);

        return response()->json($page);
    }

    public function destroy(int $id): JsonResponse
    {
        Page::findOrFail($id)->delete();
        return response()->json(['message' => 'Halaman dihapus.']);
    }
}
