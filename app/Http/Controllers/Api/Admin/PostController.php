<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $posts = Post::with('author:id,name', 'categories:category_id,category_name,slug')
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->when($request->has('is_published'), fn ($q) => $q->where('is_published', $request->boolean('is_published')))
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($posts);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'type'             => 'required|in:news,article,event',
            'content'          => 'required|string',
            'thumbnail'        => 'nullable|string|max:255',
            'is_published'     => 'boolean',
            'order'            => 'integer|min:0',
            'categories'       => 'nullable|array',
            'categories.*'     => 'exists:categories,category_id',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:255',
            'event_start_at'   => 'nullable|date',
            'event_end_at'     => 'nullable|date|after_or_equal:event_start_at',
            'event_location'   => 'nullable|string|max:150',
        ]);

        $post = Post::create([
            ...$request->only([
                'title',
                'type',
                'content',
                'thumbnail',
                'is_published',
                'order',
                'meta_title',
                'meta_description',
                'event_start_at',
                'event_end_at',
                'event_location',
            ]),
            'author_id' => $request->user()?->id,
            'slug' => Str::slug($request->title),
        ]);

        if ($request->filled('categories')) {
            $post->categories()->sync($request->categories);
        }

        return response()->json($post->load('categories'), 201);
    }

    public function show(int $id): JsonResponse
    {
        $post = Post::with('author:id,name', 'categories')->findOrFail($id);
        return response()->json($post);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $post = Post::findOrFail($id);

        $request->validate([
            'title'            => 'sometimes|string|max:255',
            'type'             => 'sometimes|in:news,article,event',
            'content'          => 'sometimes|string',
            'thumbnail'        => 'nullable|string|max:255',
            'is_published'     => 'boolean',
            'order'            => 'integer|min:0',
            'categories'       => 'nullable|array',
            'categories.*'     => 'exists:categories,category_id',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:255',
            'event_start_at'   => 'nullable|date',
            'event_end_at'     => 'nullable|date|after_or_equal:event_start_at',
            'event_location'   => 'nullable|string|max:150',
        ]);

        $data = $request->only([
            'title',
            'type',
            'content',
            'thumbnail',
            'is_published',
            'order',
            'meta_title',
            'meta_description',
            'event_start_at',
            'event_end_at',
            'event_location',
        ]);

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $post->update($data);

        if ($request->has('categories')) {
            $post->categories()->sync($request->categories ?? []);
        }

        return response()->json($post->load('categories'));
    }

    public function destroy(int $id): JsonResponse
    {
        Post::findOrFail($id)->delete();
        return response()->json(['message' => 'Post dihapus.']);
    }
}
