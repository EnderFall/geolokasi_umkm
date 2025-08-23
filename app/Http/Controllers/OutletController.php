<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Outlet;
use App\Models\Category;

class OutletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $outlets = Outlet::with(['user', 'categories'])
            ->verified()
            ->open()
            ->paginate(12);

        $categories = Category::active()->get();

        return view('outlets.index', compact('outlets', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role !== 'penjual') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $categories = Category::active()->get();
        return view('outlets.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'penjual') {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('outlets', 'public');
            $data['image'] = $imagePath;
        }

        $outlet = Outlet::create($data);

        if ($request->has('categories')) {
            $outlet->categories()->attach($request->categories);
        }

        return redirect()->route('dashboard')
            ->with('success', 'Outlet berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Outlet $outlet)
    {
        $outlet->load(['user', 'categories', 'menus.available', 'ratings.user']);
        
        $related_outlets = Outlet::where('id', '!=', $outlet->id)
            ->whereHas('categories', function($query) use ($outlet) {
                $query->whereIn('categories.id', $outlet->categories->pluck('id'));
            })
            ->verified()
            ->open()
            ->take(4)
            ->get();

        return view('outlets.show', compact('outlet', 'related_outlets'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Outlet $outlet)
    {
        if (Auth::user()->id !== $outlet->user_id && !Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $categories = Category::active()->get();
        return view('outlets.edit', compact('outlet', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Outlet $outlet)
    {
        if (Auth::user()->id !== $outlet->user_id && !Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // Delete old image
            if ($outlet->image) {
                Storage::disk('public')->delete($outlet->image);
            }
            
            $imagePath = $request->file('image')->store('outlets', 'public');
            $data['image'] = $imagePath;
        }

        $outlet->update($data);

        if ($request->has('categories')) {
            $outlet->categories()->sync($request->categories);
        }

        return redirect()->route('outlets.show', $outlet)
            ->with('success', 'Outlet berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Outlet $outlet)
    {
        if (Auth::user()->id !== $outlet->user_id && !Auth::user()->isAdmin()) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        // Delete outlet image
        if ($outlet->image) {
            Storage::disk('public')->delete($outlet->image);
        }

        $outlet->delete();

        return redirect()->route('dashboard')
            ->with('success', 'Outlet berhasil dihapus!');
    }

    /**
     * Toggle outlet status
     */
    public function toggleStatus(Outlet $outlet)
    {
        if (Auth::user()->id !== $outlet->user_id && !Auth::user()->isAdmin()) {
            return redirect()->back()->with('error', 'Akses ditolak.');
        }

        $outlet->update(['is_open' => !$outlet->is_open]);

        $status = $outlet->is_open ? 'dibuka' : 'ditutup';
        return redirect()->back()->with('success', "Outlet berhasil {$status}!");
    }
}
