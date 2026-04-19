<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();

        if ($products->isEmpty()) {
            return redirect()->route('products.index')->with('warning', 'Your cart is empty.');
        }

        $total = $products->reduce(function ($carry, $product) use ($cart) {
            return $carry + ($product->price * ($cart[$product->id] ?? 1));
        }, 0);

        return view('checkout.index', compact('products', 'cart', 'total'));
    }

    public function store(Request $request)
    {
        $cart = session('cart', []);
        $products = Product::whereIn('id', array_keys($cart))->get();

        if ($products->isEmpty()) {
            return redirect()->route('products.index')->with('warning', 'Your cart is empty.');
        }

        $request->validate([
            'address' => 'required|string|max:1000',
            'payment_proof' => 'nullable|image|max:2048',
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $products->reduce(function ($carry, $product) use ($cart) {
                return $carry + ($product->price * ($cart[$product->id] ?? 1));
            }, 0),
            'status' => 'pending',
            'payment_status' => $request->hasFile('payment_proof') ? 'pending' : 'pending',
            'shipping_status' => 'pending',
            'address' => $request->address,
        ]);

        foreach ($products as $product) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $cart[$product->id] ?? 1,
                'price' => $product->price,
            ]);
        }

        if ($request->hasFile('payment_proof')) {
            $order->payment_proof = $request->file('payment_proof')->store('payment-proofs', 'public');
            $order->save();
        }

        session()->forget('cart');

        return redirect()->route('buyer.orders')->with('success', 'Order placed successfully. Upload proof if needed from your order details.');
    }

    public function proof(Request $request, Order $order)
    {
        abort_if($order->user_id !== Auth::id(), 403);

        $request->validate(['payment_proof' => 'required|image|max:2048']);

        $order->payment_proof = $request->file('payment_proof')->store('payment-proofs', 'public');
        $order->payment_status = 'pending';
        $order->save();

        return back()->with('success', 'Payment proof uploaded. We will review it shortly.');
    }
}
