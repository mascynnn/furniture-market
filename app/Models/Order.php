<?php
// ═══════════════════════════════════════════════
// app/Models/Order.php
// ═══════════════════════════════════════════════
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number', 'user_id', 'status',
        'recipient_name', 'phone', 'address',
        'province', 'province_id', 'city', 'city_id', 'postal_code',
        'notes', 'courier', 'shipping_service', 'shipping_cost',
        'subtotal', 'total_amount', 'weight',
        'tracking_number', 'estimated_delivery',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scope: filter by status
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
