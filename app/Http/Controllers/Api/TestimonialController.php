<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\JsonResponse;

class TestimonialController extends Controller
{
    public function index(): JsonResponse
    {
        $testimonials = Testimonial::with('alumnus:alumnus_id,name,photo,current_institution,major,graduation_year,achievement')
            ->where('is_published', 1)
            ->orderBy('order')
            ->latest('testimonial_id')
            ->get([
                'testimonial_id', 'alumnus_id', 'name', 'role', 'content', 'photo', 'rating',
                'university', 'major', 'graduation_year',
            ]);

        return response()->json($testimonials);
    }
}
