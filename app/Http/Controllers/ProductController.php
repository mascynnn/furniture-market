<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Katalog — listing semua produk aktif.
     * Query filter (category, min_price, max_price, condition) ditangani Najwa
     * via scope, tapi kita siapkan dasar di sini agar terintegrasi.
     */
    public function index(Request $request)
    {
        $query = Product::with(['primaryImage', 'seller'])
                        ->active();

        // Filter dasar (kompatibel dengan SearchController Najwa)
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Pengurutan
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_asc'  => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'rating'     => $query->orderByDesc('average_rating'),
            default      => $query->latest(),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Product::active()->distinct()->pluck('category');

        return view('products.index', compact('products', 'categories', 'sort'));
    }

    /**
     * Detail produk — foto galeri, info lengkap, review.
     */
    public function show(string $slug)
    {
        $product = Product::with([
                'images',
                'seller',
                'reviews.user',
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

        // Produk serupa (kategori sama)
        $related = Product::with('primaryImage')
                          ->active()
                          ->where('category', $product->category)
                          ->where('id', '!=', $product->id)
                          ->limit(4)
                          ->get();

        // Distribusi rating
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
