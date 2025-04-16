<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    // Show all posts
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:1|max:255',
        ]);
    
        $query = $request->input('query');
    
        $posts = Post::with(['product', 'user'])
            ->where('title', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orWhereHas('product', function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%");
            })
            ->orWhereHas('user', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%");
            })
            ->paginate(10); // Add pagination
    
        return response()->json($posts);
    }
}
