<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicCalendar;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AcademicCalendarController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = AcademicCalendar::orderBy('start_date');

        if ($request->academic_year) {
            $query->where('academic_year', $request->academic_year);
        }

        return response()->json($query->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title'         => 'required|string|max:150',
            'description'   => 'nullable|string',
            'start_date'    => 'required|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'category'      => 'nullable|string|max:50',
            'color'         => 'nullable|string|max:20',
            'academic_year' => 'nullable|string|max:20',
            'is_published'  => 'boolean',
            'order'         => 'integer',
        ]);

        $item = AcademicCalendar::create($data);

        return response()->json($item, 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $item = AcademicCalendar::findOrFail($id);

        $data = $request->validate([
            'title'         => 'sometimes|required|string|max:150',
            'description'   => 'nullable|string',
            'start_date'    => 'sometimes|required|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'category'      => 'nullable|string|max:50',
            'color'         => 'nullable|string|max:20',
            'academic_year' => 'nullable|string|max:20',
            'is_published'  => 'boolean',
            'order'         => 'integer',
        ]);

        $item->update($data);

        return response()->json($item);
    }

    public function destroy($id): JsonResponse
    {
        AcademicCalendar::findOrFail($id)->delete();

        return response()->json(['message' => 'Agenda dihapus.']);
    }
}
