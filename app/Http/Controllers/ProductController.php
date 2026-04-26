<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Katalog — listing semua produk aktif.
     */
    public function index(Request $request): View
    {
        $query = Product::with(['primaryImage', 'seller'])
                        ->active();

        // Filter kategori
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Filter kondisi
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // FIX: match expression harus diakhiri semicolon dan di-wrap rapi;
        //      tanpa semicolon di akhir baris match PHP bisa parse error
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'rating'     => $query->orderBy('average_rating', 'desc'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();

        // FIX: ambil kategori dari semua produk aktif (bukan dari hasil query yang sudah difilter)
        $categories = Product::active()->distinct()->pluck('category')->filter()->sort()->values();

        return view('products.index', compact('products', 'categories', 'sort'));
    }

    /**
     * Detail produk — foto galeri, info lengkap, review.
     */
    public function show(string $slug): View
    {
        $product = Product::with([
                'images',
                'seller',
                'reviews.user',   // eager load user agar tidak N+1
            ])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        // Apakah user sudah pernah review?
        $userReview = null;
        if (auth()->check()) {
            $userReview = $product->reviews
                            ->firstWhere('user_id', auth()->id());
        }

        // Apakah produk di-wishlist user?
        $isWishlisted = auth()->check()
            ? auth()->user()->hasWishlisted($product->id)
            : false;

        // Produk serupa (kategori sama, kecuali produk ini sendiri)
        $related = Product::with('primaryImage')
                          ->active()
                          ->where('category', $product->category)
                          ->where('id', '!=', $product->id)
                          ->limit(4)
                          ->get();

        // Distribusi rating (5 → 1)
        $ratingDistribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingDistribution[$i] = $product->reviews->where('rating', $i)->count();
        }

        return view('products.show', compact(
            'product',
            'userReview',
            'isWishlisted',
            'related',
            'ratingDistribution',
        ));
    }
}