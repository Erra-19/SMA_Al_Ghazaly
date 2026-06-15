<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $q = Student::query();

        if ($request->filled('search')) {
            $s = '%' . $request->search . '%';
            $q->where(function ($sub) use ($s) {
                $sub->where('name', 'like', $s)
                    ->orWhere('nisn', 'like', $s)
                    ->orWhere('nis', 'like', $s);
            });
        }
        if ($request->filled('grade_level')) {
            $q->where('grade_level', $request->grade_level);
        }
        if ($request->filled('academic_year')) {
            $q->where('academic_year', $request->academic_year);
        }
        if ($request->filled('status')) {
            $q->where('status', $request->status);
        }

        $students = $q->orderBy('order')->orderBy('name')
            ->paginate($request->input('per_page', 20));

        return response()->json($students);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name'            => 'required|string|max:150',
            'nis'             => 'nullable|string|max:30|unique:students,nis',
            'nisn'            => 'nullable|string|max:20|unique:students,nisn',
            'nik'             => 'nullable|string|max:20',
            'gender'          => 'nullable|in:Laki-laki,Perempuan',
            'birth_place'     => 'nullable|string|max:100',
            'birth_date'      => 'nullable|date',
            'phone'           => 'nullable|string|max:30',
            'email'           => 'nullable|email|max:100',
            'address'         => 'nullable|string',
            'photo'           => 'nullable|string|max:255',
            'parent_name'     => 'nullable|string|max:150',
            'parent_phone'    => 'nullable|string|max:30',
            'previous_school' => 'nullable|string|max:150',
            'academic_year'   => 'nullable|string|max:20',
            'grade_level'     => 'nullable|string|max:20',
            'major'           => 'nullable|string|max:100',
            'status'          => 'nullable|in:active,inactive,graduated,transferred,dropped_out',
            'notes'           => 'nullable|string',
            'order'           => 'nullable|integer|min:0',
            'is_active'       => 'boolean',
        ]);

        $student = Student::create($data);
        return response()->json($student, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $student = Student::findOrFail($id);

        $data = $request->validate([
            'name'            => 'sometimes|string|max:150',
            'nis'             => "nullable|string|max:30|unique:students,nis,{$id},student_id",
            'nisn'            => "nullable|string|max:20|unique:students,nisn,{$id},student_id",
            'nik'             => 'nullable|string|max:20',
            'gender'          => 'nullable|in:Laki-laki,Perempuan',
            'birth_place'     => 'nullable|string|max:100',
            'birth_date'      => 'nullable|date',
            'phone'           => 'nullable|string|max:30',
            'email'           => 'nullable|email|max:100',
            'address'         => 'nullable|string',
            'photo'           => 'nullable|string|max:255',
            'parent_name'     => 'nullable|string|max:150',
            'parent_phone'    => 'nullable|string|max:30',
            'previous_school' => 'nullable|string|max:150',
            'academic_year'   => 'nullable|string|max:20',
            'grade_level'     => 'nullable|string|max:20',
            'major'           => 'nullable|string|max:100',
            'status'          => 'nullable|in:active,inactive,graduated,transferred,dropped_out',
            'notes'           => 'nullable|string',
            'order'           => 'nullable|integer|min:0',
            'is_active'       => 'boolean',
        ]);

        $student->update($data);
        return response()->json($student->fresh());
    }

    public function destroy(int $id): JsonResponse
    {
        Student::findOrFail($id)->delete();
        return response()->json(['message' => 'Data murid dihapus.']);
    }
}
