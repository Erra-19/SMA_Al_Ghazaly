<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $posts = Post::with('author:id,name', 'categories:category_id,category_name,slug')
            ->where('status', 'published')
            ->when($request->category, fn ($q) => $q->whereHas('categories', fn ($q) => $q->where('slug', $request->category)))
            ->when($request->search, fn ($q) => $q->where('title', 'like', "%{$request->search}%"))
            ->orderByDesc('published_at')
            ->paginate(12);

        return response()->json($posts);
    }

    public function show(string $slug): JsonResponse
    {
        $post = Post::with('author:id,name', 'categories:category_id,category_name,slug')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return response()->json($post);
    }
}
