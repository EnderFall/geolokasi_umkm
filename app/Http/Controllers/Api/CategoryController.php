<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::query();

        // Search by name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSortFields = ['name', 'created_at', 'outlet_count'];
        if (in_array($sortBy, $allowedSortFields)) {
            if ($sortBy === 'outlet_count') {
                $query->withCount('outlets')->orderBy('outlets_count', $sortOrder);
            } else {
                $query->orderBy($sortBy, $sortOrder);
            }
        } else {
            $query->orderBy('name', 'asc');
        }

        $categories = $query->paginate($request->get('per_page', 50));

        return response()->json([
            'success' => true,
            'data' => $categories->items(),
            'pagination' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'per_page' => $categories->perPage(),
                'total' => $categories->total(),
                'from' => $categories->firstItem(),
                'to' => $categories->lastItem(),
            ]
        ]);
    }

    public function show(Category $category)
    {
        $category->load(['outlets' => function($query) {
            $query->where('is_verified', true)
                  ->where('is_open', true)
                  ->withCount('menus')
                  ->withAvg('reviews', 'rating');
        }]);
        
        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'type' => 'required|in:outlet,menu',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7', // Hex color code
            'is_active' => 'boolean'
        ]);

        $category = Category::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dibuat',
            'data' => $category
        ], 201);
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'type' => 'sometimes|required|in:outlet,menu',
            'icon' => 'nullable|string|max:100',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean'
        ]);

        $category->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diupdate',
            'data' => $category
        ]);
    }

    public function destroy(Category $category)
    {
        // Check if category has outlets or menus
        if ($category->outlets()->exists() || $category->menus()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak dapat dihapus karena masih memiliki outlet atau menu'
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    }

    public function toggleStatus(Category $category)
    {
        $category->update(['is_active' => !$category->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Status kategori berhasil diubah',
            'data' => [
                'id' => $category->id,
                'is_active' => $category->is_active
            ]
        ]);
    }

    public function withStats()
    {
        $categories = Category::withCount(['outlets', 'menus'])
            ->withAvg('outlets as avg_rating', 'reviews.rating')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    public function popular()
    {
        $categories = Category::withCount(['outlets' => function($query) {
            $query->where('is_verified', true)
                  ->where('is_open', true);
        }])
        ->where('is_active', true)
        ->orderByDesc('outlets_count')
        ->limit(10)
        ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}
