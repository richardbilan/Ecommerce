<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'products', 'quantity', 'category', 'temperature',
        'promo_code', 'subtotal', 'order_type', 'delivery_fee',
        'promo_discount', 'total_amount', 'payment_method'
    ];

    protected $casts = [
        'products' => 'array', // Automatically decode JSON
    ];
}
