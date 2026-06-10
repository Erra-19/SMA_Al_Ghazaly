<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FacilityController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Facility::orderBy('category')->orderBy('order')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']);
        $data['specs'] = $this->lines($request->input('specs'));

        return response()->json(Facility::create($data), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $facility = Facility::findOrFail($id);
        $data = $this->validated($request, false);
        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        if ($request->has('specs')) {
            $data['specs'] = $this->lines($request->input('specs'));
        }

        $facility->update($data);

        return response()->json($facility);
    }

    public function destroy(int $id): JsonResponse
    {
        Facility::findOrFail($id)->delete();

        return response()->json(['message' => 'Fasilitas dihapus.']);
    }

    private function validated(Request $request, bool $creating = true): array
    {
        return $request->validate([
            'name' => [$creating ? 'required' : 'sometimes', 'string', 'max:150'],
            'category' => 'nullable|string|max:50',
            'image' => 'nullable|string|max:255',
            'icon_name' => 'nullable|string|max:50',
            'short_desc' => 'nullable|string|max:255',
            'long_desc' => 'nullable|string',
            'capacity' => 'nullable|string|max:80',
            'specs' => 'nullable',
            'operational_hours' => 'nullable|string|max:100',
            'location' => 'nullable|string|max:150',
            'order' => 'nullable|integer|min:0',
            'is_featured' => 'boolean',
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
