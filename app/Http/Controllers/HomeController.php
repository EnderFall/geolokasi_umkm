<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Outlet;
use App\Models\Category;
use App\Models\Menu;

class HomeController extends Controller
{
    /**
     * Show the home page
     */
    public function index()
    {
        $featured_outlets = Outlet::with(['user', 'categories'])
            ->verified()
            ->open()
            ->take(6)
            ->get();

        $popular_categories = Category::active()
            ->withCount('outlets')
            ->orderBy('outlets_count', 'desc')
            ->take(8)
            ->get();

        $recommended_menus = Menu::with(['outlet.user'])
            ->recommended()
            ->available()
            ->take(8)
            ->get();

        return view('home', compact('featured_outlets', 'popular_categories', 'recommended_menus'));
    }

    /**
     * Show about page
     */
    public function about()
    {
        return view('about');
    }

    /**
     * Show contact page
     */
    public function contact()
    {
        return view('contact');
    }
}
