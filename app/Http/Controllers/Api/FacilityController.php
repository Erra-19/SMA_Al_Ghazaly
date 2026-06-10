<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $facilities = Facility::where('is_published', true)
            ->when($request->category, fn ($q) => $q->where('category', $request->category))
            ->orderByDesc('is_featured')
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        return response()->json($facilities);
    }
}
