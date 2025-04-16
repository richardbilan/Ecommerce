<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class UserOrder extends Model
{
    protected $fillable = [
        'user_id',
        'user_name', // 👈 include this
        'name',
        'price',
        'quantity',
        'payment_method',
        'total_amount',
    ];


    protected $casts = [
        'items' => 'array',
    ];
    public function items()
{
    return $this->hasMany(OrderItem::class);
}

}
