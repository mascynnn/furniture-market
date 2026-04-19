<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $pendingSellers = User::where('role', 'seller')->where('is_verified', false)->latest()->get();
        $products = Product::with('user')->latest()->paginate(12);
        $orders = Order::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact('pendingSellers', 'products', 'orders'));
    }

    public function verifySeller(User $seller)
    {
        abort_if($seller->role !== 'seller', 404);

        $seller->is_verified = true;
        $seller->save();

        return back()->with('success', 'Seller verified successfully.');
    }

    public function destroyProduct(Product $product)
    {
        $product->delete();

        return back()->with('success', 'Product removed from marketplace.');
    }

    public function confirmPayment(Order $order)
    {
        if ($order->payment_proof) {
            $order->payment_status = 'paid';
            $order->save();
        }

        return back()->with('success', 'Payment status updated.');
    }
}
