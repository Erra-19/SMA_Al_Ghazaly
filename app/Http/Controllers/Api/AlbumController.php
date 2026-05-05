<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Album;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $albums = Album::where('is_published', 1)
            ->orderBy('order')
            ->paginate(12, ['album_id', 'title', 'slug', 'cover', 'description', 'created_at']);

        return response()->json($albums);
    }

    public function show(string $slug): JsonResponse
    {
        $album = Album::where('slug', $slug)
            ->where('is_published', 1)
            ->with('medias:media_id,filename,path,mime_type')
            ->firstOrFail();

        return response()->json($album);
    }
}
