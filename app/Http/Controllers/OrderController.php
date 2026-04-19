<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * List orders for the authenticated user.
     */
    public function index(Request $request)
    {
        $query = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show order detail.
     */
    public function show($id)
    {
        $order = Order::with('items.product', 'user')->findOrFail($id);
        abort_unless($order->user_id === Auth::id(), 403);

        return view('orders.show', compact('order'));
    }

    /**
     * Cancel a pending order.
     */
    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Hanya pesanan dengan status "menunggu" yang dapat dibatalkan.');
        }

        $order->update(['status' => 'cancelled']);

        // Restore stock
        foreach ($order->items as $item) {
            if ($item->product) {
                $item->product->increment('stock', $item->quantity);
            }
        }

        return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan.');
    }
}
