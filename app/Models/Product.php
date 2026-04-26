<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'category',
        'material',
        'color',
        'dimension',
        'condition',
        'is_active',
        'average_rating',
        'total_reviews',
    ];

    protected $casts = [
        'price'          => 'decimal:2',
        'average_rating' => 'decimal:2',
        'is_active'      => 'boolean',
    ];

    // ── Slug otomatis ───────────────────────────────────────
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name) . '-' . Str::random(6);
            }
        });
    }

    // ── Relasi ─────────────────────────────────────────────
    public function seller()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function wishlistedByUsers()
    {
        return $this->belongsToMany(User::class, 'wishlists')->withTimestamps();
    }

    // ── Scope ──────────────────────────────────────────────
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // FIX: tambah scope inStock untuk filter produk yang tersedia
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // ── Helper ─────────────────────────────────────────────
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * FIX: gunakan relasi `primaryImage` (hasOne) jika sudah di-eager-load,
     *      baru fallback ke koleksi `images`; hindari crash jika koleksi kosong.
     */
    public function getMainImageUrlAttribute(): string
    {
        // Coba dari relasi primaryImage yang sudah di-load
        if ($this->relationLoaded('primaryImage') && $this->primaryImage) {
            return asset('storage/' . $this->primaryImage->image_path);
        }

        // Fallback ke koleksi images jika sudah di-load
        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            $primary = $this->images->firstWhere('is_primary', true)
                        ?? $this->images->first();

            return asset('storage/' . $primary->image_path);
        }

        // Placeholder jika tidak ada gambar sama sekali
        return asset('images/placeholder-furniture.jpg');
    }

    /** Update rata-rata rating setelah review baru disimpan/dihapus */
    public function recalculateRating(): void
    {
        // FIX: hindari ambiguitas — panggil avg & count langsung ke DB
        $avg   = $this->reviews()->avg('rating') ?? 0;
        $total = $this->reviews()->count();

        $this->update([
            'average_rating' => round((float) $avg, 2),
            'total_reviews'  => $total,
        ]);
    }
}