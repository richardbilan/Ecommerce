<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopStatus extends Model
{
    use HasFactory;

    protected $table = 'shop_status';
    protected $fillable = ['is_open'];

    protected $casts = [
        'is_open' => 'boolean'
    ];

    public static function getStatus()
    {
        $status = self::first();
        return $status ? $status->is_open : false;
    }

    public static function updateStatus($isOpen)
    {
        $status = self::first();
        if (!$status) {
            $status = self::create(['is_open' => $isOpen]);
        } else {
            $status->is_open = $isOpen;
            $status->save();
        }
        return $status;
    }

    public static function getCurrentStatus()
    {
        return self::latest()->first() ?? self::create(['is_open' => false]);
    }

    public function cafeLocation()
    {
        return $this->belongsTo(CafeLocation::class, 'current_location_id');
    }
} 