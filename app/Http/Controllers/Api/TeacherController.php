<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    public function index(): JsonResponse
    {
        $teachers = Teacher::where('is_active', 1)
            ->orderBy('order')
            ->get([
                'teacher_id', 'name', 'position', 'subject', 'photo', 'bio',
                'email', 'phone', 'category', 'education', 'philosophy',
                'experience', 'tags', 'is_leadership', 'order',
            ]);

        return response()->json($teachers);
    }
}
