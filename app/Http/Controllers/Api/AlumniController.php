<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $alumni = Alumni::where('is_published', 1)
            ->when($request->year, fn ($q) => $q->where('graduation_year', $request->year))
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
            ->orderByDesc('graduation_year')
            ->paginate(15);

        return response()->json($alumni);
    }
}
