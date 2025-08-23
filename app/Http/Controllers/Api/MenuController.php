<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::with(['category', 'outlet'])
            ->where('is_available', true);

        // Filter by outlet
        if ($request->has('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search by name or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSortFields = ['name', 'price', 'created_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('name', 'asc');
        }

        $menus = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $menus->items(),
            'pagination' => [
                'current_page' => $menus->currentPage(),
                'last_page' => $menus->lastPage(),
                'per_page' => $menus->perPage(),
                'total' => $menus->total(),
                'from' => $menus->firstItem(),
                'to' => $menus->lastItem(),
            ]
        ]);
    }

    public function show(Menu $menu)
    {
        $menu->load(['category', 'outlet', 'reviews.user']);
        
        return response()->json([
            'success' => true,
            'data' => $menu
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'outlet_id' => 'required|exists:outlets,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        $menu = Menu::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dibuat',
            'data' => $menu->load(['category', 'outlet'])
        ], 201);
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'category_id' => 'sometimes|required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($menu->image) {
                \Storage::disk('public')->delete($menu->image);
            }
            
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        $menu->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil diupdate',
            'data' => $menu->load(['category', 'outlet'])
        ]);
    }

    public function destroy(Menu $menu)
    {
        // Delete image if exists
        if ($menu->image) {
            \Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Menu berhasil dihapus'
        ]);
    }

    public function toggleAvailability(Menu $menu)
    {
        $menu->update(['is_available' => !$menu->is_available]);

        return response()->json([
            'success' => true,
            'message' => 'Status ketersediaan menu berhasil diubah',
            'data' => [
                'id' => $menu->id,
                'is_available' => $menu->is_available
            ]
        ]);
    }
}
