<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    // Define the table name (optional if it follows the convention)
    protected $table = 'products';

    // If you don't want timestamps
    public $timestamps = false;

    // The fields that are mass assignable
    protected $fillable = [
        'product_name',
        'category',
        'price',
        'availability'
    ];
}
