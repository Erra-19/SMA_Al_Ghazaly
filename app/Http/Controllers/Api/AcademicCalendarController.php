<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademicCalendarController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = AcademicCalendar::published();

        if ($request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }

        $items = $query->get([
            'calendar_id', 'title', 'description',
            'start_date', 'end_date',
            'category', 'color', 'academic_year',
        ]);

        return response()->json($items);
    }
}
