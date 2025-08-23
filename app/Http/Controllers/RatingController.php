<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Store a newly created rating in storage.
     */
    public function store(Request $request, Outlet $outlet)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Check if user already rated this outlet
        $existingRating = Rating::where('user_id', Auth::id())
            ->where('outlet_id', $outlet->id)
            ->first();

        if ($existingRating) {
            // Update existing rating
            $existingRating->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            
            $message = 'Rating berhasil diperbarui!';
        } else {
            // Create new rating
            Rating::create([
                'user_id' => Auth::id(),
                'outlet_id' => $outlet->id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            
            $message = 'Rating berhasil ditambahkan!';
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Update the specified rating in storage.
     */
    public function update(Request $request, Rating $rating)
    {
        $this->authorize('update', $rating);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        $rating->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Rating berhasil diperbarui!');
    }

    /**
     * Remove the specified rating from storage.
     */
    public function destroy(Rating $rating)
    {
        $this->authorize('delete', $rating);

        $rating->delete();

        return redirect()->back()->with('success', 'Rating berhasil dihapus!');
    }

    /**
     * Get average rating for an outlet.
     */
    public function getAverageRating(Outlet $outlet)
    {
        $averageRating = $outlet->ratings()->avg('rating');
        $totalRatings = $outlet->ratings()->count();

        return response()->json([
            'average_rating' => round($averageRating, 1),
            'total_ratings' => $totalRatings,
        ]);
    }

    /**
     * Get user's rating for an outlet.
     */
    public function getUserRating(Outlet $outlet)
    {
        $rating = Rating::where('user_id', Auth::id())
            ->where('outlet_id', $outlet->id)
            ->first();

        return response()->json([
            'rating' => $rating ? $rating->rating : null,
            'comment' => $rating ? $rating->comment : null,
        ]);
    }
}
