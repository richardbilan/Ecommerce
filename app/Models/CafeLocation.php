<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CafeLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'address',
        'latitude',
        'longitude',
        'description',
        'opening_time',
        'closing_time',
        'is_current',
        'is_open',
        'last_opened_at',
        'last_closed_at'
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'is_open' => 'boolean',
        'opening_time' => 'datetime',
        'closing_time' => 'datetime',
        'last_opened_at' => 'datetime',
        'last_closed_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float'
    ];

    /**
     * Get the current location of the cafe
     */
    public static function getCurrentLocation()
    {
        return self::where('is_current', true)->first();
    }

    /**
     * Set this location as the current cafe location
     */
    public function setAsCurrent()
    {
        // Begin transaction to ensure data consistency
        \DB::transaction(function () {
            // First, set all locations as not current
            self::query()->update(['is_current' => false]);
            
            // Then set this location as current
            $this->update(['is_current' => true]);
        });

        return $this;
    }

    /**
     * Toggle the shop open/closed status and record the time
     */
    public function toggleShopStatus()
    {
        $this->is_open = !$this->is_open;
        
        if ($this->is_open) {
            $this->last_opened_at = now();
        } else {
            $this->last_closed_at = now();
        }
        
        $this->save();
        return $this;
    }

    /**
     * Check if the shop is open based on both status and operating hours
     */
    public function isShopOpen()
    {
        if (!$this->is_open) {
            return false;
        }

        // If we have operating hours, check them
        if ($this->opening_time && $this->closing_time) {
            $now = now();
            $opening = today()->setTimeFromTimeString($this->opening_time);
            $closing = today()->setTimeFromTimeString($this->closing_time);

            return $now->between($opening, $closing);
        }

        return $this->is_open;
    }

    /**
     * Calculate distance to another point in kilometers
     */
    public function distanceTo($lat2, $lon2)
    {
        $lat1 = $this->latitude;
        $lon1 = $this->longitude;
        
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + 
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        
        // Convert miles to kilometers
        return $miles * 1.609344;
    }

    /**
     * Check if the cafe is currently open based on operating hours
     */
    public function isOpen()
    {
        if (!$this->opening_time || !$this->closing_time) {
            return false;
        }

        $now = now();
        $opening = today()->setTimeFromTimeString($this->opening_time);
        $closing = today()->setTimeFromTimeString($this->closing_time);

        return $now->between($opening, $closing);
    }
} 