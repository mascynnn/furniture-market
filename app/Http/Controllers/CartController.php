<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function index()
    {
        $cart      = session('cart', []);
        $cartItems = [];
        $subtotal  = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $cartItems[$productId] = [
                    'name'      => $product->name,
                    'price'     => $product->price,
                    'quantity'  => $item['quantity'],
                    'thumbnail' => $product->thumbnail,
                    'category'  => $product->category->name ?? null,
                ];
                $subtotal += $product->price * $item['quantity'];
            }
        }

        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    /**
     * Add a product to the cart (session).
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1|max:99',
        ]);

        $productId = $request->product_id;
        $quantity  = $request->quantity;

        $cart = session('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
            // Cap at stock limit
            $stock = Product::find($productId)->stock ?? 99;
            $cart[$productId]['quantity'] = min($cart[$productId]['quantity'], $stock);
        } else {
            $cart[$productId] = ['quantity' => $quantity];
        }

        session(['cart' => $cart]);

        return redirect()->back()->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    /**
     * Update quantity of an item in cart.
     */
    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99',
        ]);

        $cart = session('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $request->quantity;
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index')->with('success', 'Jumlah item diperbarui.');
    }

    /**
     * Remove a single item from the cart.
     */
    public function remove($productId)
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);

        return redirect()->route('cart.index')->with('success', 'Item dihapus dari keranjang.');
    }

    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index')->with('info', 'Keranjang berhasil dikosongkan.');
    }

    /**
     * Helper: get cart items with product data (for use in other controllers).
     */
    public static function getCartWithData(): array
    {
        $cart     = session('cart', []);
        $items    = [];
        $subtotal = 0;
        $weight   = 0;

        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if ($product) {
                $items[$productId] = [
                    'name'      => $product->name,
                    'price'     => $product->price,
                    'quantity'  => $item['quantity'],
                    'thumbnail' => $product->thumbnail,
                    'category'  => $product->category->name ?? null,
                    'weight'    => $product->weight ?? 500,
                ];
                $subtotal += $product->price * $item['quantity'];
                $weight   += ($product->weight ?? 500) * $item['quantity'];
            }
        }

        return compact('items', 'subtotal', 'weight');
    }
}
