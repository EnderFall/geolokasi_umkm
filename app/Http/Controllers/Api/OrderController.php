<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Order::with(['outlet', 'orderItems.menu']);

        // Filter by user role
        if ($user->hasRole('penjual')) {
            $query->whereHas('outlet', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by outlet
        if ($request->has('outlet_id')) {
            $query->where('outlet_id', $request->outlet_id);
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
        
        $allowedSortFields = ['created_at', 'total_amount', 'status'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $orders = $query->paginate($request->get('per_page', 20));

        return response()->json([
            'success' => true,
            'data' => $orders->items(),
            'pagination' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'from' => $orders->firstItem(),
                'to' => $orders->lastItem(),
            ]
        ]);
    }

    public function show(Order $order)
    {
        $user = auth()->user();
        
        // Check if user can view this order
        if ($user->hasRole('penjual')) {
            if ($order->outlet->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this order'
                ], 403);
            }
        } else {
            if ($order->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this order'
                ], 403);
            }
        }

        $order->load(['outlet', 'user', 'orderItems.menu']);

        return response()->json([
            'success' => true,
            'data' => $order
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.notes' => 'nullable|string',
            'notes' => 'nullable|string',
            'total_amount' => 'required|numeric|min:0'
        ]);

        $user = auth()->user();
        
        // Check if outlet is open and verified
        $outlet = \App\Models\Outlet::find($request->outlet_id);
        if (!$outlet->is_open) {
            return response()->json([
                'success' => false,
                'message' => 'Outlet sedang tutup'
            ], 422);
        }

        if (!$outlet->is_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Outlet belum diverifikasi'
            ], 422);
        }

        // Validate menu availability and prices
        $totalCalculated = 0;
        foreach ($request->items as $item) {
            $menu = \App\Models\Menu::find($item['menu_id']);
            
            if (!$menu->is_available) {
                return response()->json([
                    'success' => false,
                    'message' => "Menu {$menu->name} tidak tersedia"
                ], 422);
            }

            if ($menu->outlet_id != $request->outlet_id) {
                return response()->json([
                    'success' => false,
                    'message' => "Menu {$menu->name} tidak tersedia di outlet ini"
                ], 422);
            }

            $totalCalculated += $item['price'] * $item['quantity'];
        }

        // Validate total amount
        if (abs($totalCalculated - $request->total_amount) > 0.01) {
            return response()->json([
                'success' => false,
                'message' => 'Total amount tidak valid'
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'outlet_id' => $request->outlet_id,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'status' => 'pending',
                'total_amount' => $request->total_amount,
                'notes' => $request->notes,
                'ordered_at' => now()
            ]);

            // Create order items
            foreach ($request->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'notes' => $item['notes'] ?? null
                ]);
            }

            DB::commit();

            $order->load(['outlet', 'orderItems.menu']);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat',
                'data' => $order
            ], 201);

        } catch (\Exception $e) {
            DB::rollback();
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pesanan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function confirm(Order $order)
    {
        $user = auth()->user();
        
        // Check if user is the outlet owner
        if ($order->outlet->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dikonfirmasi'
            ], 422);
        }

        $order->update(['status' => 'confirmed']);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dikonfirmasi',
            'data' => $order->load(['outlet', 'orderItems.menu'])
        ]);
    }

    public function markReady(Order $order)
    {
        $user = auth()->user();
        
        // Check if user is the outlet owner
        if ($order->outlet->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        if ($order->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat ditandai siap'
            ], 422);
        }

        $order->update(['status' => 'ready']);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil ditandai siap',
            'data' => $order->load(['outlet', 'orderItems.menu'])
        ]);
    }

    public function markDelivered(Order $order)
    {
        $user = auth()->user();
        
        // Check if user is the outlet owner
        if ($order->outlet->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        if ($order->status !== 'ready') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat ditandai selesai'
            ], 422);
        }

        $order->update(['status' => 'delivered']);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil ditandai selesai',
            'data' => $order->load(['outlet', 'orderItems.menu'])
        ]);
    }

    public function destroy(Order $order)
    {
        $user = auth()->user();
        
        // Check if user can cancel this order
        if ($user->hasRole('penjual')) {
            if ($order->outlet->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }
        } else {
            if ($order->user_id !== $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action'
                ], 403);
            }
        }

        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak dapat dibatalkan'
            ], 422);
        }

        $order->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibatalkan',
            'data' => $order->load(['outlet', 'orderItems.menu'])
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled'
        ]);

        $user = auth()->user();
        
        // Check if user is the outlet owner
        if ($order->outlet->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action'
            ], 403);
        }

        $order->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status pesanan berhasil diupdate',
            'data' => $order->load(['outlet', 'orderItems.menu'])
        ]);
    }

    public function getStats(Request $request)
    {
        $user = auth()->user();
        $query = Order::query();

        // Filter by user role
        if ($user->hasRole('penjual')) {
            $query->whereHas('outlet', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        } else {
            $query->where('user_id', $user->id);
        }

        // Filter by date range
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $stats = [
            'total_orders' => $query->count(),
            'pending' => (clone $query)->where('status', 'pending')->count(),
            'confirmed' => (clone $query)->where('status', 'confirmed')->count(),
            'preparing' => (clone $query)->where('status', 'preparing')->count(),
            'ready' => (clone $query)->where('status', 'ready')->count(),
            'delivered' => (clone $query)->where('status', 'delivered')->count(),
            'cancelled' => (clone $query)->where('status', 'cancelled')->count(),
            'total_revenue' => (clone $query)->where('status', 'delivered')->sum('total_amount'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
