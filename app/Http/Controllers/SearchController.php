<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', true);

        // Full-text search (Eloquent query scope)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qb) use ($q) {
                $qb->where('name', 'LIKE', "%{$q}%")
                   ->orWhere('description', 'LIKE', "%{$q}%")
                   ->orWhere('material', 'LIKE', "%{$q}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', function ($qb) use ($request) {
                $qb->where('slug', $request->category);
            });
        }

        // Material filter
        if ($request->filled('material')) {
            $query->where('material', $request->material);
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('order_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products  = $query->paginate(9);
        $categories = Category::all();

        // Distinct materials for filter dropdown
        $materials = Product::where('is_active', true)
            ->whereNotNull('material')
            ->distinct()
            ->pluck('material')
            ->sort()
            ->values();

        return view('search.index', compact('products', 'categories', 'materials'));
    }
}
