<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Outlet;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Rating;
use App\Models\Category;

class DashboardController extends Controller
{
    /**
     * Show dashboard based on user role
     */
    public function index()
    {
        $user = Auth::user();
        
        switch ($user->role) {
            case 'superadmin':
            case 'admin':
                return $this->adminDashboard();
            case 'penjual':
                return $this->penjualDashboard();
            case 'pembeli':
                return $this->pembeliDashboard();
            default:
                return redirect()->route('home');
        }
    }

    /**
     * Admin/Superadmin Dashboard
     */
    private function adminDashboard()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_outlets' => Outlet::count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
        ];

        $recent_outlets = Outlet::with('user')->latest()->take(5)->get();
        $recent_orders = Order::with(['user', 'outlet'])->latest()->take(5)->get();

        return view('dashboard.admin', compact('stats', 'recent_outlets', 'recent_orders'));
    }

    /**
     * Penjual Dashboard
     */
    private function penjualDashboard()
    {
        $user = Auth::user();
        $outlet = $user->outlet;
        
        if (!$outlet) {
            return redirect()->route('outlets.create')
                ->with('warning', 'Anda belum memiliki outlet. Silakan buat outlet terlebih dahulu.');
        }

        $stats = [
            'total_menus' => $outlet->menus()->count(),
            'total_orders' => $outlet->orders()->count(),
            'total_revenue' => $outlet->orders()->where('status', 'delivered')->sum('total_amount'),
            'average_rating' => $outlet->average_rating,
        ];

        $recent_orders = $outlet->orders()->with('user')->latest()->take(5)->get();
        $popular_menus = $outlet->menus()->withCount('orderItems')->orderBy('order_items_count', 'desc')->take(5)->get();

        return view('dashboard.penjual', compact('outlet', 'stats', 'recent_orders', 'popular_menus'));
    }

    /**
     * Pembeli Dashboard
     */
    private function pembeliDashboard()
    {
        $user = Auth::user();
        
        $stats = [
            'total_orders' => $user->orders()->count(),
            'total_reviews' => $user->reviews()->count(),
            'total_ratings' => $user->ratings()->count(),
        ];

        $recent_orders = $user->orders()->with('outlet')->latest()->take(5)->get();
        $nearby_outlets = Outlet::verified()
            ->open()
            ->selectRaw('*, ( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance', [0, 0, 0])
            ->orderBy('distance')
            ->take(5)
            ->get();

        return view('dashboard.pembeli', compact('stats', 'recent_orders', 'nearby_outlets'));
    }
}
