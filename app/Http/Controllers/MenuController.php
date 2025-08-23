<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Menu;
use App\Models\Outlet;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::with(['outlet.user'])
            ->available()
            ->paginate(12);

        return view('menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role !== 'penjual') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $outlet = Auth::user()->outlet;
        if (!$outlet) {
            return redirect()->route('outlets.create')
                ->with('warning', 'Anda harus memiliki outlet terlebih dahulu.');
        }

        return view('menus.create', compact('outlet'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'penjual') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $outlet = Auth::user()->outlet;
        if (!$outlet) {
            return redirect()->route('outlets.create')
                ->with('warning', 'Anda harus memiliki outlet terlebih dahulu.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'is_recommended' => 'boolean',
        ]);

        $data = $request->all();
        $data['outlet_id'] = $outlet->id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        Menu::create($data);

        return redirect()->route('dashboard')
            ->with('success', 'Menu berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        $menu->load(['outlet.user', 'reviews.user']);
        
        $related_menus = Menu::where('outlet_id', $menu->outlet_id)
            ->where('id', '!=', $menu->id)
            ->available()
            ->take(4)
            ->get();

        return view('menus.show', compact('menu', 'related_menus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        if (Auth::user()->id !== $menu->outlet->user_id && !Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        return view('menus.edit', compact('menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        if (Auth::user()->id !== $menu->outlet->user_id && !Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_available' => 'boolean',
            'is_recommended' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            
            $imagePath = $request->file('image')->store('menus', 'public');
            $data['image'] = $imagePath;
        }

        $menu->update($data);

        return redirect()->route('menus.show', $menu)
            ->with('success', 'Menu berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        if (Auth::user()->id !== $menu->outlet->user_id && !Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        // Delete menu image
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }

        $menu->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Menu berhasil dihapus!');
    }

    /**
     * Toggle menu availability
     */
    public function toggleAvailability(Menu $menu)
    {
        if (Auth::user()->id !== $menu->outlet->user_id && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $menu->update(['is_available' => !$menu->is_available]);

        $status = $menu->is_available ? 'tersedia' : 'tidak tersedia';
        return redirect()->back()->with('success', "Menu berhasil diubah menjadi {$status}!");
    }

    /**
     * Toggle menu recommendation
     */
    public function toggleRecommendation(Menu $menu)
    {
        if (Auth::user()->id !== $menu->outlet->user_id && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $menu->update(['is_recommended' => !$menu->is_recommended]);

        $status = $menu->is_recommended ? 'direkomendasikan' : 'tidak direkomendasikan';
        return redirect()->back()->with('success', "Menu berhasil diubah menjadi {$status}!");
    }
}
