<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        $testimonials = Testimonial::where('is_published', 1)
            ->orderBy('order')
            ->get(['testimonial_id', 'name', 'role', 'content', 'photo', 'rating']);

        return response()->json($testimonials);
    }
}
