<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
    /**
     * Get common sidebar data.
     *
     * @return array<string, mixed>
     */
    protected function getSidebarData(): array
    {
        return Cache::remember('blog_sidebar_data', 120, function () {
            $recent = Post::published()->latest()->take(5)->get();

            return [
                'breakingPosts' => $recent,
                'featuredPost' => Post::published()->where('is_featured', true)->latest()->first() ?? $recent->first(),
                'popularPosts' => Post::published()->orderByDesc('views')->take(5)->get(),
                'categories' => Category::withCount(['posts' => function ($q) {
                    $q->where('status', 'published');
                }])->get(),
                'recentPosts' => $recent,
                'tags' => Tag::has('posts')->get(),
            ];
        });
    }

    /**
     * Display the homepage / search results.
     */
    public function index(Request $request): View
    {
        $query = Post::published()->with(['category', 'tags'])->latest();

        $search = $request->query('q') ?? $request->query('search');
        if (! empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(6)->withQueryString();
        $sidebar = $this->getSidebarData();

        return view('blog.index', array_merge([
            'posts' => $posts,
            'search' => $search,
        ], $sidebar));
    }

    /**
     * Display single post detail.
     */
    public function show(string $slug): View
    {
        $post = Post::published()->with(['category', 'tags', 'comments' => function ($q) {
            $q->where('status', 'approved')->latest();
        }])->where('slug', $slug)->firstOrFail();

        // Increment views safely
        $post->increment('views');

        // Related posts from same category
        $relatedPosts = Post::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(3)
            ->get();

        $sidebar = $this->getSidebarData();

        return view('blog.show', array_merge([
            'post' => $post,
            'relatedPosts' => $relatedPosts,
        ], $sidebar));
    }

    /**
     * Filter posts by category.
     */
    public function category(string $slug): View
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = Post::published()
            ->where('category_id', $category->id)
            ->with(['category', 'tags'])
            ->latest()
            ->paginate(6);

        $sidebar = $this->getSidebarData();

        return view('blog.category', array_merge([
            'category' => $category,
            'posts' => $posts,
        ], $sidebar));
    }

    /**
     * Filter posts by tag.
     */
    public function tag(string $slug): View
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()
            ->where('status', 'published')
            ->with(['category', 'tags'])
            ->latest()
            ->paginate(6);

        $sidebar = $this->getSidebarData();

        return view('blog.tag', array_merge([
            'tag' => $tag,
            'posts' => $posts,
        ], $sidebar));
    }

    /**
     * Store comment on a post with anti-spam honeypot and sanitization.
     */
    public function storeComment(Request $request, string $slug): RedirectResponse
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        // Anti-spam Honeypot: automated spam bots fill invisible fields
        if ($request->filled('website_url_hp')) {
            return redirect()->to(route('blog.show', $post->slug).'#comments')
                ->with('success', 'Komentar Anda berhasil dikirimkan!');
        }

        $validated = $request->validate([
            'author_name' => 'required|string|max:100',
            'author_email' => 'nullable|email|max:150',
            'content' => 'required|string|max:2000',
        ]);

        // Sanitize inputs to prevent any stored XSS in comments
        $cleanAuthor = strip_tags(trim($validated['author_name']));
        $cleanContent = strip_tags(trim($validated['content']));

        // If comment contains multiple external links, flag for moderation
        $linkCount = substr_count(strtolower($cleanContent), 'http://') + substr_count(strtolower($cleanContent), 'https://');
        $status = $linkCount >= 2 ? 'pending' : 'approved';

        $post->comments()->create([
            'author_name' => $cleanAuthor,
            'author_email' => $validated['author_email'] ?? null,
            'content' => $cleanContent,
            'status' => $status,
        ]);

        $message = $status === 'pending'
            ? 'Komentar Anda telah diterima dan sedang menunggu persetujuan moderator.'
            : 'Komentar Anda berhasil dikirimkan!';

        return redirect()->to(route('blog.show', $post->slug).'#comments')
            ->with('success', $message);
    }

    /**
     * Serve post featured image (extracting binary from data URI or storage).
     */
    public function image(Post $post): Response
    {
        if (empty($post->featured_image)) {
            abort(404);
        }

        if (Str::startsWith($post->featured_image, 'data:image/')) {
            if (preg_match('/^data:([^;]+);base64,(.*)$/s', $post->featured_image, $matches)) {
                $mimeType = $matches[1];
                $binary = base64_decode($matches[2]);

                return response($binary, 200, [
                    'Content-Type' => $mimeType,
                    'Content-Length' => strlen($binary),
                    'Cache-Control' => 'public, max-age=604800, stale-while-revalidate=86400',
                ]);
            }
        }

        if (Str::startsWith($post->featured_image, ['http://', 'https://'])) {
            return redirect()->away($post->featured_image);
        }

        $filePath = storage_path('app/public/'.$post->featured_image);
        if (file_exists($filePath)) {
            $mime = mime_content_type($filePath) ?: 'application/octet-stream';

            return response()->file($filePath, ['Content-Type' => $mime]);
        }

        abort(404);
    }

    /**
     * Display static page (About, Contact, Privacy, etc.)
     */
    public function page(string $slug): View
    {
        $titles = [
            'tentang-kami' => 'Tentang Kami',
            'kontak' => 'Hubungi Kami',
            'kebijakan-privasi' => 'Kebijakan Privasi (Privacy Policy)',
            'disclaimer' => 'Disclaimer',
        ];

        $pageTitle = $titles[$slug] ?? ucwords(str_replace('-', ' ', $slug));
        $sidebar = $this->getSidebarData();

        return view('blog.page', array_merge([
            'slug' => $slug,
            'pageTitle' => $pageTitle,
        ], $sidebar));
    }
}
