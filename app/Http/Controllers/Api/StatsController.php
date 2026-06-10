<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\Registration;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            // Jumlah pendaftar PPDB dengan status diterima
            'accepted_registrations' => Registration::where('status', 'accepted')->count(),

            // Jumlah guru aktif
            'active_teachers' => Teacher::where('is_active', 1)->count(),

            // Jumlah ekskul yang dipublish
            'ekskul_count' => Program::where('type', 'ekskul')->where('is_published', 1)->count(),
        ]);
    }
}
