<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InternalMessageController extends Controller
{
    /** Inbox: pesan yang diterima user ini (receiver = me atau broadcast) */
    public function inbox(Request $request): JsonResponse
    {
        $me = Auth::id();

        $messages = AdminMessage::with('sender:id,name')
            ->where(fn ($q) => $q->where('receiver_id', $me)->orWhereNull('receiver_id'))
            ->where('sender_id', '!=', $me)          // jangan tampilkan pesan sendiri di inbox
            ->latest()
            ->paginate(20);

        return response()->json($messages);
    }

    /** Sent: pesan yang dikirim user ini */
    public function sent(Request $request): JsonResponse
    {
        $messages = AdminMessage::with('receiver:id,name')
            ->where('sender_id', Auth::id())
            ->latest()
            ->paginate(20);

        return response()->json($messages);
    }

    /** Jumlah pesan belum dibaca di inbox */
    public function unreadCount(): JsonResponse
    {
        $me = Auth::id();

        $count = AdminMessage::where(fn ($q) => $q->where('receiver_id', $me)->orWhereNull('receiver_id'))
            ->where('sender_id', '!=', $me)
            ->where('is_read', 0)
            ->count();

        return response()->json(['count' => $count]);
    }

    /** List semua admin selain diri sendiri (untuk dropdown penerima) */
    public function adminList(): JsonResponse
    {
        $admins = User::with('role:role_id,name')
            ->whereHas('role', fn ($q) => $q->whereIn('name', ['super_admin', 'admin']))
            ->where('id', '!=', Auth::id())
            ->orderBy('name')
            ->get(['id', 'name', 'role_id']);

        return response()->json($admins);
    }

    /** Kirim pesan baru */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'receiver_id' => 'nullable|integer|exists:users,id',
            'subject'     => 'nullable|string|max:150',
            'body'        => 'required|string|max:5000',
        ]);

        $message = AdminMessage::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id ?: null,
            'subject'     => $request->subject,
            'body'        => $request->body,
            'is_read'     => 0,
        ]);

        return response()->json($message->load('sender:id,name', 'receiver:id,name'), 201);
    }

    /** Baca pesan — otomatis tandai dibaca */
    public function show(int $id): JsonResponse
    {
        $me = Auth::id();

        $message = AdminMessage::with('sender:id,name', 'receiver:id,name')
            ->where(function ($q) use ($me) {
                // Boleh lihat jika: pengirim = saya, atau penerima = saya, atau broadcast (receiver = null)
                $q->where('sender_id', $me)
                  ->orWhere('receiver_id', $me)
                  ->orWhereNull('receiver_id');
            })
            ->findOrFail($id);

        // Tandai dibaca jika saya bukan pengirimnya
        if ($message->sender_id !== $me && ! $message->is_read) {
            $message->update(['is_read' => true, 'read_at' => now()]);
        }

        return response()->json($message);
    }

    /** Hapus pesan — hanya pengirim atau penerima yang boleh */
    public function destroy(int $id): JsonResponse
    {
        $me = Auth::id();

        $message = AdminMessage::where(fn ($q) =>
            $q->where('sender_id', $me)
              ->orWhere('receiver_id', $me)
              ->orWhereNull('receiver_id')
        )->findOrFail($id);

        $message->delete();

        return response()->json(['message' => 'Pesan dihapus.']);
    }
}
