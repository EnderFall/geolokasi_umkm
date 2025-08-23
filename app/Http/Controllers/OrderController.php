<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Menu;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['outlet', 'orderItems.menu'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $outlets = Outlet::where('is_open', true)
            ->where('is_verified', true)
            ->with('menus')
            ->get();

        return view('orders.create', compact('outlets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'outlet_id' => 'required|exists:outlets,id',
            'items' => 'required|array|min:1',
            'items.*.menu_id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.notes' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total amount
            $totalAmount = 0;
            foreach ($request->items as $item) {
                $menu = Menu::find($item['menu_id']);
                $totalAmount += $menu->price * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid()),
                'user_id' => Auth::id(),
                'outlet_id' => $request->outlet_id,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'notes' => $request->notes,
                'ordered_at' => now(),
            ]);

            // Create order items
            foreach ($request->items as $item) {
                $menu = Menu::find($item['menu_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['menu_id'],
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'subtotal' => $menu->price * $item['quantity'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Terjadi kesalahan saat membuat pesanan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        // Check if user can view this order
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $order->load(['outlet', 'orderItems.menu', 'user']);

        return view('orders.show', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled',
        ]);

        // Only outlet owner or admin can update order status
        if ($order->outlet->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // Only user who created the order or admin can delete it
        if ($order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        // Only pending orders can be cancelled
        if ($order->status !== 'pending') {
            return back()->with('error', 'Hanya pesanan pending yang dapat dibatalkan.');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan!');
    }

    /**
     * Show orders for a specific outlet (outlet owner view).
     */
    public function outletOrders()
    {
        $outlet = Auth::user()->outlet;
        
        if (!$outlet) {
            return redirect()->route('outlets.create')
                ->with('error', 'Anda harus membuat outlet terlebih dahulu.');
        }

        $orders = Order::where('outlet_id', $outlet->id)
            ->with(['user', 'orderItems.menu'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('outlet.orders', compact('orders', 'outlet'));
    }

    /**
     * Confirm an order (outlet owner).
     */
    public function confirmOrder(Order $order)
    {
        if ($order->outlet->user_id !== Auth::id()) {
            abort(403);
        }

        $order->update(['status' => 'confirmed']);

        return redirect()->back()->with('success', 'Pesanan berhasil dikonfirmasi!');
    }

    /**
     * Mark order as ready (outlet owner).
     */
    public function markReady(Order $order)
    {
        if ($order->outlet->user_id !== Auth::id()) {
            abort(403);
        }

        $order->update(['status' => 'ready']);

        return redirect()->back()->with('success', 'Pesanan siap diambil!');
    }

    /**
     * Mark order as delivered (outlet owner).
     */
    public function markDelivered(Order $order)
    {
        if ($order->outlet->user_id !== Auth::id()) {
            abort(403);
        }

        $order->update(['status' => 'delivered']);

        return redirect()->back()->with('success', 'Pesanan berhasil diselesaikan!');
    }

    /**
     * Get order statistics for dashboard.
     */
    public function getOrderStats()
    {
        if (Auth::user()->isPembeli()) {
            $stats = [
                'total_orders' => Order::where('user_id', Auth::id())->count(),
                'pending_orders' => Order::where('user_id', Auth::id())->where('status', 'pending')->count(),
                'completed_orders' => Order::where('user_id', Auth::id())->where('status', 'delivered')->count(),
                'total_spent' => Order::where('user_id', Auth::id())->where('status', 'delivered')->sum('total_amount'),
            ];
        } else {
            $outlet = Auth::user()->outlet;
            if ($outlet) {
                $stats = [
                    'total_orders' => Order::where('outlet_id', $outlet->id)->count(),
                    'pending_orders' => Order::where('outlet_id', $outlet->id)->where('status', 'pending')->count(),
                    'completed_orders' => Order::where('outlet_id', $outlet->id)->where('status', 'delivered')->count(),
                    'total_revenue' => Order::where('outlet_id', $outlet->id)->where('status', 'delivered')->sum('total_amount'),
                ];
            } else {
                $stats = [];
            }
        }

        return response()->json($stats);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled'
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diupdate.');
    }
}
