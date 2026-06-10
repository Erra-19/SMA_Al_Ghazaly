<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Program::orderBy('type')->orderBy('order')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['title']);
        $data['features'] = $this->lines($request->input('features'));

        return response()->json(Program::create($data), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $program = Program::findOrFail($id);
        $data = $this->validated($request, false);
        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        if ($request->has('features')) {
            $data['features'] = $this->lines($request->input('features'));
        }

        $program->update($data);

        return response()->json($program);
    }

    public function destroy(int $id): JsonResponse
    {
        Program::findOrFail($id)->delete();

        return response()->json(['message' => 'Program dihapus.']);
    }

    private function validated(Request $request, bool $creating = true): array
    {
        return $request->validate([
            'title' => [$creating ? 'required' : 'sometimes', 'string', 'max:150'],
            'type' => 'nullable|string|max:30',
            'subtitle' => 'nullable|string|max:180',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:50',
            'badge' => 'nullable|string|max:80',
            'stats' => 'nullable|string|max:120',
            'features' => 'nullable',
            'order' => 'nullable|integer|min:0',
            'is_published' => 'boolean',
        ]);
    }

    private function lines(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_filter($value));
        }

        return collect(preg_split('/\r\n|\r|\n/', (string) $value))
            ->map(fn ($line) => trim($line))
            ->filter()
            ->values()
            ->all();
    }
}
