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
        return response()->json(Category::with('children')->whereNull('parent_id')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'category_name' => 'required|string|max:100',
            'parent_id'     => 'nullable|exists:categories,category_id',
        ]);

        $category = Category::create([
            'category_name' => $request->category_name,
            'slug'          => Str::slug($request->category_name),
            'parent_id'     => $request->parent_id,
        ]);

        return response()->json($category, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category_name' => 'sometimes|string|max:100',
            'parent_id'     => 'nullable|exists:categories,category_id',
        ]);

        $data = $request->only(['category_name', 'parent_id']);
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
