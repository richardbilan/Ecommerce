<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\Models\Review;

class UserOrder extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'name',
        'price',
        'quantity',
        'payment_method',
        'total_amount',
        'location',
        'items',
        'status',
        'confirmed_at',
        'preparation_started_at',
        'preparation_completed_at',
        'delivery_started_at',
        'delivered_at',
        'subtotal',
        'delivery_address',
        'shop_address',
    ];

    protected $attributes = [
        'status' => 'pending'
    ];

    protected $casts = [
        'items' => 'array',
        'total_amount' => 'decimal:2',
        'price' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'preparation_started_at' => 'datetime',
        'preparation_completed_at' => 'datetime',
        'delivery_started_at' => 'datetime',
        'delivered_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'order_id');
    }
}
