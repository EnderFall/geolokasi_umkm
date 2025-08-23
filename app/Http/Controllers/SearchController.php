<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;
use App\Models\Menu;
use App\Models\Category;

class SearchController extends Controller
{
    /**
     * Search outlets
     */
    public function searchOutlets(Request $request)
    {
        $query = $request->get('q');
        $category = $request->get('category');
        $location = $request->get('location');
        $rating = $request->get('rating');

        $outlets = Outlet::with(['user', 'categories'])
            ->verified()
            ->open();

        if ($query) {
            $outlets->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('address', 'like', "%{$query}%");
            });
        }

        if ($category) {
            $outlets->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category);
            });
        }

        if ($rating) {
            $outlets->whereHas('ratings', function($q) use ($rating) {
                $q->havingRaw('AVG(rating) >= ?', [$rating]);
            });
        }

        $outlets = $outlets->paginate(12);
        $categories = Category::active()->get();

        return view('search.outlets', compact('outlets', 'categories', 'query', 'category', 'rating'));
    }

    /**
     * Search menus
     */
    public function searchMenus(Request $request)
    {
        $query = $request->get('q');
        $category = $request->get('category');
        $price_min = $request->get('price_min');
        $price_max = $request->get('price_max');

        $menus = Menu::with(['outlet.user', 'outlet.categories'])
            ->available();

        if ($query) {
            $menus->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%");
            });
        }

        if ($category) {
            $menus->whereHas('outlet.categories', function($q) use ($category) {
                $q->where('categories.id', $category);
            });
        }

        if ($price_min) {
            $menus->where('price', '>=', $price_min);
        }

        if ($price_max) {
            $menus->where('price', '<=', $price_max);
        }

        $menus = $menus->paginate(12);
        $categories = Category::active()->get();

        return view('search.menus', compact('menus', 'categories', 'query', 'category', 'price_min', 'price_max'));
    }

    /**
     * Search by location
     */
    public function searchByLocation(Request $request)
    {
        $latitude = $request->get('latitude');
        $longitude = $request->get('longitude');
        $radius = $request->get('radius', 5); // Default 5km

        if (!$latitude || !$longitude) {
            return redirect()->back()->with('error', 'Lokasi tidak ditemukan.');
        }

        $outlets = Outlet::verified()
            ->open()
            ->selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [$latitude, $longitude, $latitude])
            ->having('distance', '<=', $radius)
            ->orderBy('distance')
            ->with(['user', 'categories'])
            ->paginate(12);

        return view('search.location', compact('outlets', 'latitude', 'longitude', 'radius'));
    }
}
