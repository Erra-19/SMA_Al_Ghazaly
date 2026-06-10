<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $programs = Program::where('is_published', true)
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->orderBy('order')
            ->orderBy('title')
            ->get();

        return response()->json($programs);
    }
}
