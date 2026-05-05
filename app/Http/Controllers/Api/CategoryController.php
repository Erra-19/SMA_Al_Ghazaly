<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::whereNull('parent_id')
            ->with('children:category_id,category_name,slug,parent_id')
            ->orderBy('category_name')
            ->get(['category_id', 'category_name', 'slug', 'parent_id']);

        return response()->json($categories);
    }
}
