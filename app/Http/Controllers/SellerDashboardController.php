<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerDashboardController extends Controller
{
    public function index()
    {
        $seller = Auth::user();

        $products = $seller->products()->count();
        $orders = Order::whereHas('items.product', fn ($query) => $query->where('user_id', $seller->id))->count();

        return view('seller.dashboard', compact('seller', 'products', 'orders'));
    }

    public function products()
    {
        $products = Auth::user()->products()->latest()->paginate(10);

        return view('seller.products.index', compact('products'));
    }

    public function createProduct()
    {
        return view('seller.products.create');
    }

    public function storeProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:2000',
            'material' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_preorder' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'price', 'description', 'material', 'size']);
        $data['is_preorder'] = $request->has('is_preorder');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Auth::user()->products()->create($data);

        return redirect()->route('seller.products.index')->with('success', 'Product added successfully.');
    }

    public function editProduct(Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        return view('seller.products.edit', compact('product'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:2000',
            'material' => 'nullable|string|max:255',
            'size' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_preorder' => 'nullable|boolean',
        ]);

        $data = $request->only(['name', 'price', 'description', 'material', 'size']);
        $data['is_preorder'] = $request->has('is_preorder');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('seller.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroyProduct(Product $product)
    {
        abort_if($product->user_id !== Auth::id(), 403);

        $product->delete();

        return back()->with('success', 'Product removed.');
    }

    public function orders()
    {
        $orders = Order::whereHas('items.product', fn ($query) => $query->where('user_id', Auth::id()))
            ->with('items.product')
            ->latest()
            ->paginate(10);

        return view('seller.orders', compact('orders'));
    }

    public function updateOrder(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:processed,shipped,completed',
            'courier' => 'nullable|string|max:255',
            'tracking_number' => 'nullable|string|max:255',
        ]);

        abort_if(! $order->items()->whereHas('product', fn ($query) => $query->where('user_id', Auth::id()))->exists(), 403);

        $order->status = $request->status;
        $order->courier = $request->courier;
        $order->tracking_number = $request->tracking_number ?: $order->tracking_number;

        if ($request->status === 'shipped' && ! $order->tracking_number) {
            return back()->withErrors(['tracking_number' => 'Tracking number is required for shipped orders.']);
        }

        $order->shipping_status = $request->status === 'shipped' ? 'shipped' : ($request->status === 'completed' ? 'completed' : 'processing');
        $order->save();

        return back()->with('success', 'Order status updated.');
    }
}
