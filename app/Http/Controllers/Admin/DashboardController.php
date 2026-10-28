<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function index(): View
    {
        $stats = [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('status', 'published')->count(),
            'draft_posts' => Post::where('status', 'draft')->count(),
            'total_categories' => Category::count(),
            'total_views' => Post::sum('views'),
            'total_comments' => Comment::count(),
        ];

        $recentPosts = Post::with('category')->latest()->take(7)->get();
        $popularPosts = Post::with('category')->orderByDesc('views')->take(5)->get();
        $categories = Category::withCount('posts')->get();

        return view('admin.dashboard', compact('stats', 'recentPosts', 'popularPosts', 'categories'));
    }
}
