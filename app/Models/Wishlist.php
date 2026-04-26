<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    protected $fillable = ['user_id', 'product_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * FIX: hapus ->with('images') dari definisi relasi.
     * Eager loading sebaiknya dilakukan di Controller (Wishlist::with(['product.images']))
     * agar tidak selalu ikut ter-load saat relasi ini digunakan di konteks lain.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}