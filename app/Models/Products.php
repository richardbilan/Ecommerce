<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'product_id',
        'product_name',
        'category',
        'price_hot',
        'price_iced',
        'availability',
        'tag',
        'image', // Include 'image' if you upload images
    ];
    public function index()
{
    $products = Products ::where('status', 'active')->get(); // Or any condition you want
    return view('home', compact('products'));
}
}
