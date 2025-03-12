<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    // You don't need to include product_id in the fillable array as it's auto-incrementing
    protected $fillable = [
        'product_name',
        'category',
        'price',
        'availability'
    ];

    public $timestamps = false;  // Disable timestamps if not using them
}
