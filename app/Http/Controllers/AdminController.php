<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin,superadmin']);
    }

    // User Management
    public function users(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $totalUsers = User::count();
        $penjualUsers = User::where('role', 'penjual')->count();
        $pembeliUsers = User::where('role', 'pembeli')->count();
        $adminUsers = User::whereIn('role', ['admin', 'superadmin'])->count();

        return view('admin.users', compact('users', 'totalUsers', 'penjualUsers', 'pembeliUsers', 'adminUsers'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:admin,penjual,pembeli',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'is_active' => true,
        ]);

        return redirect()->route('admin.users')->with('success', 'User berhasil ditambahkan.');
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'role' => 'required|in:admin,penjual,pembeli',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'User berhasil diupdate.');
    }

    public function toggleUserStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Tidak dapat mengubah status akun sendiri.');
        }

        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.users')->with('success', "User berhasil {$status}.");
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')->with('error', 'Tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    // Outlet Management
    public function outlets(Request $request)
    {
        $query = Outlet::with('user');

        // Filter by verification status
        if ($request->filled('verification')) {
            $query->where('is_verified', $request->verification);
        }

        // Filter by outlet status
        if ($request->filled('status')) {
            $query->where('is_open', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $outlets = $query->orderBy('created_at', 'desc')->paginate(15);

        // Statistics
        $totalOutlets = Outlet::count();
        $verifiedOutlets = Outlet::where('is_verified', true)->count();
        $pendingOutlets = Outlet::where('is_verified', false)->count();
        $openOutlets = Outlet::where('is_open', true)->count();

        // Get penjual users for create outlet form
        $penjualUsers = User::where('role', 'penjual')->where('is_active', true)->get();

        return view('admin.outlets', compact('outlets', 'totalOutlets', 'verifiedOutlets', 'pendingOutlets', 'openOutlets', 'penjualUsers'));
    }

    public function storeOutlet(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'is_open' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'user_id' => $request->user_id,
            'name' => $request->name,
            'phone' => $request->phone,
            'description' => $request->description,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'open_time' => $request->open_time,
            'close_time' => $request->close_time,
            'is_open' => $request->boolean('is_open'),
            'is_verified' => true, // Admin created outlets are automatically verified
        ];

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('outlets', 'public');
            $data['image'] = $imagePath;
        }

        Outlet::create($data);

        return redirect()->route('admin.outlets')->with('success', 'Outlet berhasil ditambahkan.');
    }

    public function updateOutlet(Request $request, Outlet $outlet)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'description' => 'nullable|string|max:1000',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'is_open' => 'boolean',
            'is_verified' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'description' => $request->description,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'open_time' => $request->open_time,
            'close_time' => $request->close_time,
            'is_open' => $request->boolean('is_open'),
            'is_verified' => $request->boolean('is_verified'),
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($outlet->image) {
                Storage::disk('public')->delete($outlet->image);
            }
            
            $imagePath = $request->file('image')->store('outlets', 'public');
            $data['image'] = $imagePath;
        }

        $outlet->update($data);

        return redirect()->route('admin.outlets')->with('success', 'Outlet berhasil diupdate.');
    }

    public function verifyOutlet(Outlet $outlet)
    {
        $outlet->update(['is_verified' => true]);

        return redirect()->route('admin.outlets')->with('success', 'Outlet berhasil diverifikasi.');
    }

    public function unverifyOutlet(Outlet $outlet)
    {
        $outlet->update(['is_verified' => false]);

        return redirect()->route('admin.outlets')->with('success', 'Verifikasi outlet berhasil dibatalkan.');
    }

    public function destroyOutlet(Outlet $outlet)
    {
        // Delete outlet image
        if ($outlet->image) {
            Storage::disk('public')->delete($outlet->image);
        }

        $outlet->delete();

        return redirect()->route('admin.outlets')->with('success', 'Outlet berhasil dihapus.');
    }

    // Dashboard Statistics
    public function getDashboardStats()
    {
        $totalUsers = User::count();
        $totalOutlets = Outlet::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'delivered')->sum('total_amount');

        $recentUsers = User::latest()->take(5)->get();
        $recentOutlets = Outlet::with('user')->latest()->take(5)->get();
        $recentOrders = Order::with(['user', 'outlet'])->latest()->take(5)->get();

        return response()->json([
            'totalUsers' => $totalUsers,
            'totalOutlets' => $totalOutlets,
            'totalOrders' => $totalOrders,
            'totalRevenue' => $totalRevenue,
            'recentUsers' => $recentUsers,
            'recentOutlets' => $recentOutlets,
            'recentOrders' => $recentOrders,
        ]);
    }
}
