<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function index(Request $request)
    {
        $query = Outlet::with(['category', 'user'])
            ->where('is_verified', true);

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search by name or address
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status (open/closed)
        if ($request->has('is_open')) {
            $query->where('is_open', $request->boolean('is_open'));
        }

        // Filter by verification status
        if ($request->has('is_verified')) {
            $query->where('is_verified', $request->boolean('is_verified'));
        }

        // Filter by distance (if coordinates provided)
        if ($request->has('latitude') && $request->has('longitude')) {
            $lat = $request->latitude;
            $lng = $request->longitude;
            $radius = $request->get('radius', 10); // Default 10km
            
            $query->selectRaw('*, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', 
                [$lat, $lng, $lat])
                ->having('distance', '<=', $radius)
                ->orderBy('distance');
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        $allowedSortFields = ['name', 'created_at', 'rating'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('name', 'asc');
        }

        $outlets = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $outlets->items(),
            'pagination' => [
                'current_page' => $outlets->currentPage(),
                'last_page' => $outlets->lastPage(),
                'per_page' => $outlets->perPage(),
                'total' => $outlets->total(),
                'from' => $outlets->firstItem(),
                'to' => $outlets->lastItem(),
            ]
        ]);
    }

    public function show(Outlet $outlet)
    {
        $outlet->load(['category', 'user', 'menus.category', 'reviews.user']);
        
        // Calculate average rating
        $averageRating = $outlet->reviews->avg('rating') ?? 0;
        $totalReviews = $outlet->reviews->count();
        
        $outlet->average_rating = round($averageRating, 1);
        $outlet->total_reviews = $totalReviews;
        
        return response()->json([
            'success' => true,
            'data' => $outlet
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'category_id' => 'required|exists:categories,id',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'boolean',
            'is_verified' => 'boolean'
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();
        
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('outlets', 'public');
            $data['image'] = $imagePath;
        }

        $outlet = Outlet::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Outlet berhasil dibuat',
            'data' => $outlet->load(['category', 'user'])
        ], 201);
    }

    public function update(Request $request, Outlet $outlet)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'sometimes|required|string',
            'phone' => 'sometimes|required|string|max:20',
            'category_id' => 'sometimes|required|exists:categories,id',
            'latitude' => 'sometimes|required|numeric|between:-90,90',
            'longitude' => 'sometimes|required|numeric|between:-180,180',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_open' => 'boolean',
            'is_verified' => 'boolean'
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($outlet->image) {
                \Storage::disk('public')->delete($outlet->image);
            }
            
            $imagePath = $request->file('image')->store('outlets', 'public');
            $data['image'] = $imagePath;
        }

        $outlet->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Outlet berhasil diupdate',
            'data' => $outlet->load(['category', 'user'])
        ]);
    }

    public function destroy(Outlet $outlet)
    {
        // Delete image if exists
        if ($outlet->image) {
            \Storage::disk('public')->delete($outlet->image);
        }

        $outlet->delete();

        return response()->json([
            'success' => true,
            'message' => 'Outlet berhasil dihapus'
        ]);
    }

    public function toggleStatus(Outlet $outlet)
    {
        $outlet->update(['is_open' => !$outlet->is_open]);

        return response()->json([
            'success' => true,
            'message' => 'Status outlet berhasil diubah',
            'data' => [
                'id' => $outlet->id,
                'is_open' => $outlet->is_open
            ]
        ]);
    }

    public function verify(Outlet $outlet)
    {
        $outlet->update(['is_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Outlet berhasil diverifikasi',
            'data' => [
                'id' => $outlet->id,
                'is_verified' => true
            ]
        ]);
    }

    public function unverify(Outlet $outlet)
    {
        $outlet->update(['is_verified' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi outlet berhasil dibatalkan',
            'data' => [
                'id' => $outlet->id,
                'is_verified' => false
            ]
        ]);
    }

    public function nearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'numeric|min:0.1|max:100', // 0.1km to 100km
            'limit' => 'integer|min:1|max:100'
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;
        $radius = $request->get('radius', 5); // Default 5km
        $limit = $request->get('limit', 20);

        $outlets = Outlet::selectRaw('*, 
            (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance', 
            [$lat, $lng, $lat])
            ->with(['category', 'user'])
            ->where('is_verified', true)
            ->where('is_open', true)
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $outlets,
            'search_params' => [
                'latitude' => $lat,
                'longitude' => $lng,
                'radius_km' => $radius,
                'limit' => $limit
            ]
        ]);
    }
}
