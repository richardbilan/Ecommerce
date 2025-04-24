<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;
use App\Models\Favorite;

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
        'image',
        'status',
        'Description'
    ];

    protected $casts = [
        'price_hot' => 'decimal:2',
        'price_iced' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function posts()
    {
        return $this->hasMany(Post::class, 'product_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'product_id');
    }

    public function index()
    {
        $products = Products::where('status', 'active')->get(); // Or any condition you want
        return view('home', compact('products'));
    }
}
