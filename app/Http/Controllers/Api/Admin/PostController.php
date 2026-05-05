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
        $posts = Post::with('author:id,name', 'categories:category_id,category_name')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->orderByDesc('created_at')
            ->paginate(15);

        return response()->json($posts);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'excerpt'          => 'nullable|string',
            'thumbnail'        => 'nullable|string|max:255',
            'status'           => 'required|in:draft,published,archived',
            'categories'       => 'nullable|array',
            'categories.*'     => 'exists:categories,category_id',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string|max:255',
        ]);

        $post = Post::create([
            ...$request->only([
                'title', 'content', 'excerpt', 'thumbnail', 'status',
                'meta_title', 'meta_description', 'meta_keywords',
            ]),
            'author_id'    => $request->user()->id,
            'slug'         => Str::slug($request->title),
            'published_at' => $request->status === 'published' ? now() : null,
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
            'content'          => 'sometimes|string',
            'excerpt'          => 'nullable|string',
            'thumbnail'        => 'nullable|string|max:255',
            'status'           => 'sometimes|in:draft,published,archived',
            'categories'       => 'nullable|array',
            'categories.*'     => 'exists:categories,category_id',
            'meta_title'       => 'nullable|string|max:160',
            'meta_description' => 'nullable|string|max:255',
            'meta_keywords'    => 'nullable|string|max:255',
        ]);

        $data = $request->only([
            'title', 'content', 'excerpt', 'thumbnail', 'status',
            'meta_title', 'meta_description', 'meta_keywords',
        ]);

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        if (isset($data['status']) && $data['status'] === 'published' && ! $post->published_at) {
            $data['published_at'] = now();
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
