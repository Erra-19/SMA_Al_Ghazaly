<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email|max:150',
            'phone'   => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:100',
            'message' => 'required|string|max:5000',
        ]);

        Message::create($request->only(['name', 'email', 'phone', 'subject', 'message']));

        return response()->json([
            'message' => 'Pesan berhasil dikirim. Terima kasih, kami akan menghubungi Anda segera.',
        ], 201);
    }
}
