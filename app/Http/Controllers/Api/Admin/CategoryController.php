<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Category::with('children')
                ->withCount('posts')
                ->whereNull('parent_id')
                ->orderBy('category_name')
                ->get()
        );
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name'          => 'nullable|string|max:100',
            'category_name' => 'nullable|string|max:100',
            'parent_id'     => 'nullable|exists:categories,category_id',
        ]);

        $name = $request->input('category_name', $request->input('name'));
        abort_if(!$name, 422, 'Nama kategori wajib diisi.');

        $category = Category::create([
            'category_name' => $name,
            'slug'          => Str::slug($name),
            'parent_id'     => $request->parent_id,
        ]);

        return response()->json($category, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name'          => 'nullable|string|max:100',
            'category_name' => 'sometimes|string|max:100',
            'parent_id'     => 'nullable|exists:categories,category_id',
        ]);

        $data = $request->only(['category_name', 'parent_id']);
        if ($request->filled('name')) {
            $data['category_name'] = $request->name;
        }
        if (isset($data['category_name'])) {
            $data['slug'] = Str::slug($data['category_name']);
        }

        $category->update($data);

        return response()->json($category);
    }

    public function destroy(int $id): JsonResponse
    {
        Category::findOrFail($id)->delete();
        return response()->json(['message' => 'Kategori dihapus.']);
    }
}
