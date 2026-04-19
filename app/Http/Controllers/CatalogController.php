<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    /**
     * Pencarian & Katalog Produk — Bagian "Najwa"
     */
    public function search(Request $request)
    {
        $query = Product::with(['kategori'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->active();

        // ── Full-text search ──────────────────────────────────────────────
        if ($q = $request->input('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama',        'like', "%{$q}%")
                    ->orWhere('deskripsi', 'like', "%{$q}%")
                    ->orWhere('material',  'like', "%{$q}%")
                    ->orWhereHas('kategori', fn($k) => $k->where('nama', 'like', "%{$q}%"));
            });
        }

        // ── Category filter ───────────────────────────────────────────────
        if ($slugs = $request->input('kategori')) {
            $slugs = (array) $slugs;
            if (!in_array('semua', $slugs)) {
                $query->whereHas('kategori', fn($k) => $k->whereIn('slug', $slugs));
            }
        }

        // ── Price range ───────────────────────────────────────────────────
        if ($min = $request->input('harga_min')) {
            $query->where('harga', '>=', (int) $min);
        }
        if ($max = $request->input('harga_max')) {
            $query->where('harga', '<=', (int) $max);
        }

        // ── Material ──────────────────────────────────────────────────────
        if ($materials = $request->input('material')) {
            $query->whereIn('material_slug', (array) $materials);
        }

        // ── Color ─────────────────────────────────────────────────────────
        if ($colors = $request->input('warna')) {
            $query->whereIn('warna_utama', (array) $colors);
        }

        // ── Rating ────────────────────────────────────────────────────────
        if ($ratings = $request->input('rating')) {
            $minRating = min((array) $ratings);
            $query->having('reviews_avg_rating', '>=', $minRating);
        }

        // ── Sorting ───────────────────────────────────────────────────────
        match ($request->input('sort', 'terbaru')) {
            'terpopuler' => $query->orderByDesc('views_count'),
            'harga-asc'  => $query->orderBy('harga'),
            'harga-desc' => $query->orderByDesc('harga'),
            'rating'     => $query->orderByDesc('reviews_avg_rating'),
            default      => $query->orderByDesc('created_at'),
        };

        $products = $query->paginate(12)->withQueryString();

        // Sidebar categories + count
        $categories = Category::withCount([
            'products' => fn($p) => $p->where('is_active', true),
        ])->active()->get();

        return view('catalog.search', compact('products', 'categories'));
    }
}
