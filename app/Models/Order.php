<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'items', 'order_mode', 'subtotal', 'delivery_fee', 'discount', 'total', 'payment_method', 'status'
    ];

    protected $casts = [
        'items' => 'array', // Convert JSON to array
    ];
}
