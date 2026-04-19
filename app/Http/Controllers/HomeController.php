<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $featured = Product::with('user')
            ->whereHas('user', fn ($query) => $query->where('is_verified', true))
            ->latest()
            ->take(4)
            ->get();

        $latest = Product::with('user')
            ->whereHas('user', fn ($query) => $query->where('is_verified', true))
            ->latest()
            ->take(8)
            ->get();

        return view('home', compact('featured', 'latest'));
    }
}
