<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $medias = Media::with('uploader:id,name')
            ->when($request->type, function ($q) use ($request) {
                $q->where('mime_type', 'like', $request->type . '/%');
            })
            ->orderByDesc('created_at')
            ->paginate(24);

        return response()->json($medias);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240',
        ]);

        $file  = $request->file('file');
        $path  = $file->store('uploads', 'public');

        $media = Media::create([
            'uploader_id' => $request->user()->id,
            'filename'    => $file->getClientOriginalName(),
            'path'        => $path,
            'mime_type'   => $file->getMimeType(),
            'size'        => $file->getSize(),
        ]);

        return response()->json($media, 201);
    }

    public function destroy(int $id): JsonResponse
    {
        $media = Media::findOrFail($id);
        Storage::disk('public')->delete($media->path);
        $media->delete();

        return response()->json(['message' => 'File dihapus.']);
    }
}
