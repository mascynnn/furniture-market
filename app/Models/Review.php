<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['user_id', 'product_id', 'order_id', 'rating', 'comment'];

    // ── Relasi ─────────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // ── Helper ─────────────────────────────────────────────

    /** Mengembalikan array [filled, empty] jumlah bintang */
    public function getStarsAttribute(): array
    {
        return [
            'filled' => $this->rating,
            'empty'  => 5 - $this->rating,
        ];
    }
}
