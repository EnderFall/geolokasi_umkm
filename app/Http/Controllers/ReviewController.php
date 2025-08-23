<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Outlet;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $request->validate([
            'reviewable_type' => 'required|in:App\Models\Outlet,App\Models\Menu',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = Auth::user();
        $reviewableType = $request->reviewable_type;
        $reviewableId = $request->reviewable_id;

        // Check if reviewable exists
        $reviewable = $reviewableType::find($reviewableId);
        if (!$reviewable) {
            return back()->with('error', 'Item yang direview tidak ditemukan.');
        }

        // Check if user has already reviewed this item
        $existingReview = Review::where('user_id', $user->id)
            ->where('reviewable_type', $reviewableType)
            ->where('reviewable_id', $reviewableId)
            ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan review untuk item ini.');
        }

        // Check if user has ordered from this outlet (for outlet reviews)
        if ($reviewableType === Outlet::class) {
            $hasOrdered = $user->orders()
                ->where('outlet_id', $reviewableId)
                ->where('status', 'delivered')
                ->exists();

            if (!$hasOrdered) {
                return back()->with('error', 'Anda harus memesan dan menyelesaikan pesanan dari outlet ini sebelum memberikan review.');
            }
        }

        // Check if user has ordered this menu (for menu reviews)
        if ($reviewableType === Menu::class) {
            $hasOrdered = $user->orders()
                ->whereHas('orderItems', function($query) use ($reviewableId) {
                    $query->where('menu_id', $reviewableId);
                })
                ->where('status', 'delivered')
                ->exists();

            if (!$hasOrdered) {
                return back()->with('error', 'Anda harus memesan dan menyelesaikan pesanan menu ini sebelum memberikan review.');
            }
        }

        try {
            $review = Review::create([
                'user_id' => $user->id,
                'reviewable_type' => $reviewableType,
                'reviewable_id' => $reviewableId,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'images' => $this->handleImageUploads($request)
            ]);

            // Update average rating for reviewable
            $this->updateAverageRating($reviewable);

            return back()->with('success', 'Review berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan review: ' . $e->getMessage());
        }
    }

    public function update(Request $request, Review $review)
    {
        $user = Auth::user();
        
        // Check if user owns this review
        if ($review->user_id !== $user->id) {
            return back()->with('error', 'Anda tidak dapat mengedit review ini.');
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:1000',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $oldImages = $review->images ?? [];
            
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'images' => $this->handleImageUploads($request, $oldImages)
            ]);

            // Update average rating for reviewable
            $this->updateAverageRating($review->reviewable);

            return back()->with('success', 'Review berhasil diupdate.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengupdate review: ' . $e->getMessage());
        }
    }

    public function destroy(Review $review)
    {
        $user = Auth::user();
        
        // Check if user owns this review or is admin
        if ($review->user_id !== $user->id && !$user->hasRole(['admin', 'superadmin'])) {
            return back()->with('error', 'Anda tidak dapat menghapus review ini.');
        }

        try {
            $reviewable = $review->reviewable;
            
            // Delete review images
            if ($review->images) {
                foreach ($review->images as $image) {
                    if (file_exists(public_path('storage/' . $image))) {
                        unlink(public_path('storage/' . $image));
                    }
                }
            }

            $review->delete();

            // Update average rating for reviewable
            $this->updateAverageRating($reviewable);

            return back()->with('success', 'Review berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus review: ' . $e->getMessage());
        }
    }

    public function index(Request $request)
    {
        $query = Review::with(['user', 'reviewable']);

        // Filter by reviewable type
        if ($request->has('type')) {
            $type = $request->type === 'outlet' ? Outlet::class : Menu::class;
            $query->where('reviewable_type', $type);
        }

        // Filter by rating
        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSortFields = ['created_at', 'rating', 'updated_at'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $reviews = $query->paginate(20);

        return view('reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'reviewable']);
        
        return view('reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $user = Auth::user();
        
        // Check if user owns this review
        if ($review->user_id !== $user->id) {
            return back()->with('error', 'Anda tidak dapat mengedit review ini.');
        }

        return view('reviews.edit', compact('review'));
    }

    private function handleImageUploads(Request $request, $existingImages = [])
    {
        $images = $existingImages;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('reviews', 'public');
                $images[] = $path;
            }
        }

        return $images;
    }

    private function updateAverageRating($reviewable)
    {
        $averageRating = $reviewable->reviews()->avg('rating');
        $totalReviews = $reviewable->reviews()->count();

        $reviewable->update([
            'rating' => round($averageRating, 1),
            'total_reviews' => $totalReviews
        ]);
    }

    public function getOutletReviews(Outlet $outlet)
    {
        $reviews = $outlet->reviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ]
        ]);
    }

    public function getMenuReviews(Menu $menu)
    {
        $reviews = $menu->reviews()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reviews->items(),
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ]
        ]);
    }

    public function createOutletReview(Outlet $outlet)
    {
        $user = auth()->user();
        
        // Check if user can review this outlet
        if (!Review::canUserReview($user->id, Outlet::class, $outlet->id)) {
            return back()->with('error', 'Anda tidak dapat memberikan review untuk outlet ini.');
        }
        
        return view('reviews.create', [
            'item' => $outlet,
            'item_type' => 'outlet'
        ]);
    }

    public function createMenuReview(Menu $menu)
    {
        $user = auth()->user();
        
        // Check if user can review this menu
        if (!Review::canUserReview($user->id, Menu::class, $menu->id)) {
            return back()->with('error', 'Anda tidak dapat memberikan review untuk menu ini.');
        }
        
        return view('reviews.create', [
            'item' => $menu,
            'item_type' => 'menu'
        ]);
    }
}
