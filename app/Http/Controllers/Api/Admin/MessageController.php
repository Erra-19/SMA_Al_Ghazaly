<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $messages = Message::orderByRaw('is_read ASC')
            ->latest()
            ->paginate(20);

        return response()->json($messages);
    }

    public function show(int $id): JsonResponse
    {
        $message = Message::findOrFail($id);

        if (! $message->is_read) {
            $message->update(['is_read' => true, 'read_at' => now()]);
        }

        return response()->json($message);
    }

    public function destroy(int $id): JsonResponse
    {
        Message::findOrFail($id)->delete();

        return response()->json(['message' => 'Pesan dihapus.']);
    }
}
