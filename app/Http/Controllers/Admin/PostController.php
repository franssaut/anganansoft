<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of posts.
     */
    public function index(Request $request): View
    {
        $query = Post::with(['category', 'user'])->latest();

        if ($request->filled('search')) {
            $search = $request->query('search');
            $query->where('title', 'like', "%{$search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $posts = $query->paginate(10)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new post.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $allTags = Tag::orderBy('name')->pluck('name')->toArray();

        return view('admin.posts.create', compact('categories', 'allTags'));
    }

    /**
     * Store a newly created post in storage with hardened validation and sanitization.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug',
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,svg|max:10240',
            'featured_image_url' => 'nullable|url:http,https|max:2000',
            'software_version' => 'nullable|string|max:100',
            'file_size' => 'nullable|string|max:50',
            'license_type' => 'nullable|string|max:100',
            'os_support' => 'nullable|string|max:200',
            'password_archive' => 'nullable|string|max:100',
            'download_names' => 'nullable|array',
            'download_names.*' => 'nullable|string|max:100',
            'download_urls' => 'nullable|array',
            'download_urls.*' => 'nullable|url:http,https|max:500',
            'system_requirements' => 'nullable|string|max:2000',
            'installation_guide' => 'nullable|string|max:2000',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:published,draft',
            'tags' => 'nullable|string|max:255',
        ]);

        // Generate or clean slug
        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        // Handle Image
        $imagePath = null;
        if ($request->hasFile('featured_image_file')) {
            $imagePath = $this->processUploadedImage($request->file('featured_image_file'));
        } elseif (! empty($validated['featured_image_url'])) {
            $imagePath = $validated['featured_image_url'];
        }

        // Process Download Mirrors
        $downloadLinks = [];
        if (! empty($request->download_names) && is_array($request->download_names)) {
            foreach ($request->download_names as $index => $name) {
                $url = $request->download_urls[$index] ?? '';
                if (! empty(trim($name)) && ! empty(trim($url)) && filter_var($url, FILTER_VALIDATE_URL)) {
                    $downloadLinks[] = [
                        'name' => strip_tags(trim($name)),
                        'url' => trim($url),
                    ];
                }
            }
        }

        $sanitizedContent = $this->sanitizeHtml($validated['content']);

        $post = Post::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'title' => strip_tags($validated['title']),
            'slug' => $slug,
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($sanitizedContent), 160),
            'content' => $sanitizedContent,
            'featured_image' => $imagePath,
            'software_version' => $validated['software_version'] ?? null,
            'file_size' => $validated['file_size'] ?? null,
            'license_type' => $validated['license_type'] ?? 'Full Version',
            'os_support' => $validated['os_support'] ?? 'Windows 11 / 10 / 8 / 7',
            'password_archive' => $validated['password_archive'] ?? 'anganansoft',
            'download_links' => $downloadLinks,
            'system_requirements' => $validated['system_requirements'] ?? null,
            'installation_guide' => $validated['installation_guide'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        // Process Tags
        $this->syncTags($post, $request->input('tags'));

        return redirect()->route('admin.posts.index')->with('success', 'Postingan baru berhasil diterbitkan!');
    }

    /**
     * Show the form for editing the specified post.
     */
    public function edit(Post $post): View
    {
        $categories = Category::orderBy('name')->get();
        $allTags = Tag::orderBy('name')->pluck('name')->toArray();
        $currentTags = $post->tags->pluck('name')->implode(', ');

        return view('admin.posts.edit', compact('post', 'categories', 'allTags', 'currentTags'));
    }

    /**
     * Update the specified post in storage with hardened validation and sanitization.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:posts,slug,'.$post->id,
            'category_id' => 'required|exists:categories,id',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'featured_image_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,svg|max:10240',
            'featured_image_url' => 'nullable|url:http,https|max:2000',
            'software_version' => 'nullable|string|max:100',
            'file_size' => 'nullable|string|max:50',
            'license_type' => 'nullable|string|max:100',
            'os_support' => 'nullable|string|max:200',
            'password_archive' => 'nullable|string|max:100',
            'download_names' => 'nullable|array',
            'download_names.*' => 'nullable|string|max:100',
            'download_urls' => 'nullable|array',
            'download_urls.*' => 'nullable|url:http,https|max:500',
            'system_requirements' => 'nullable|string|max:2000',
            'installation_guide' => 'nullable|string|max:2000',
            'is_featured' => 'nullable|boolean',
            'status' => 'required|in:published,draft',
            'tags' => 'nullable|string|max:255',
        ]);

        $slug = ! empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']);
        $originalSlug = $slug;
        $counter = 1;
        while (Post::where('slug', $slug)->where('id', '!=', $post->id)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }

        // Handle Image
        $imagePath = $post->featured_image;
        if ($request->hasFile('featured_image_file')) {
            if ($post->featured_image && ! Str::startsWith($post->featured_image, ['http://', 'https://', 'data:image/'])) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $imagePath = $this->processUploadedImage($request->file('featured_image_file'));
        } elseif ($request->filled('featured_image_url')) {
            $imagePath = $validated['featured_image_url'];
        }

        // Process Download Mirrors
        $downloadLinks = [];
        if (! empty($request->download_names) && is_array($request->download_names)) {
            foreach ($request->download_names as $index => $name) {
                $url = $request->download_urls[$index] ?? '';
                if (! empty(trim($name)) && ! empty(trim($url)) && filter_var($url, FILTER_VALIDATE_URL)) {
                    $downloadLinks[] = [
                        'name' => strip_tags(trim($name)),
                        'url' => trim($url),
                    ];
                }
            }
        }

        $sanitizedContent = $this->sanitizeHtml($validated['content']);

        $post->update([
            'category_id' => $validated['category_id'],
            'title' => strip_tags($validated['title']),
            'slug' => $slug,
            'excerpt' => $validated['excerpt'] ?? Str::limit(strip_tags($sanitizedContent), 160),
            'content' => $sanitizedContent,
            'featured_image' => $imagePath,
            'software_version' => $validated['software_version'] ?? null,
            'file_size' => $validated['file_size'] ?? null,
            'license_type' => $validated['license_type'] ?? 'Full Version',
            'os_support' => $validated['os_support'] ?? 'Windows 11 / 10 / 8 / 7',
            'password_archive' => $validated['password_archive'] ?? 'anganansoft',
            'download_links' => $downloadLinks,
            'system_requirements' => $validated['system_requirements'] ?? null,
            'installation_guide' => $validated['installation_guide'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'status' => $validated['status'],
            'published_at' => ($validated['status'] === 'published' && ! $post->published_at) ? now() : $post->published_at,
        ]);

        $this->syncTags($post, $request->input('tags'));

        return redirect()->route('admin.posts.index')->with('success', 'Postingan berhasil diperbarui!');
    }

    /**
     * Remove the specified post from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        if ($post->featured_image && ! Str::startsWith($post->featured_image, ['http://', 'https://'])) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('success', 'Postingan berhasil dihapus.');
    }

    /**
     * Sync tags helper.
     */
    protected function syncTags(Post $post, ?string $rawTags): void
    {
        if (empty($rawTags)) {
            $post->tags()->sync([]);

            return;
        }

        $tagsArray = array_map('trim', explode(',', $rawTags));
        $tagIds = [];

        foreach ($tagsArray as $tagName) {
            $cleanTagName = strip_tags($tagName);
            if (! empty($cleanTagName)) {
                $tag = Tag::firstOrCreate(
                    ['slug' => Str::slug($cleanTagName)],
                    ['name' => $cleanTagName, 'slug' => Str::slug($cleanTagName)]
                );
                $tagIds[] = $tag->id;
            }
        }

        $post->tags()->sync($tagIds);
    }

    /**
     * Process uploaded image: resize, optimize, and encode as base64 Data URI
     * for permanent persistence on serverless Vercel and TiDB Cloud.
     */
    protected function processUploadedImage(UploadedFile $file): string
    {
        $mime = $file->getMimeType() ?: 'image/jpeg';
        $contents = file_get_contents($file->getRealPath());

        // Optimize if GD extension is loaded and file is a bitmap
        if (extension_loaded('gd') && function_exists('imagecreatefromstring') && $mime !== 'image/svg+xml') {
            try {
                $img = @imagecreatefromstring($contents);
                if ($img !== false) {
                    $origWidth = imagesx($img);
                    $origHeight = imagesy($img);
                    $maxWidth = 1000;

                    if ($origWidth > $maxWidth) {
                        $newWidth = $maxWidth;
                        $newHeight = (int) ($origHeight * ($maxWidth / $origWidth));
                        $resized = imagecreatetruecolor($newWidth, $newHeight);

                        imagealphablending($resized, false);
                        imagesavealpha($resized, true);
                        $transparent = imagecolorallocatealpha($resized, 255, 255, 255, 127);
                        imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);

                        imagecopyresampled($resized, $img, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                        imagedestroy($img);
                        $img = $resized;
                    }

                    ob_start();
                    if (function_exists('imagewebp')) {
                        imagewebp($img, null, 85);
                        $contents = ob_get_clean();
                        $mime = 'image/webp';
                    } else {
                        imagejpeg($img, null, 85);
                        $contents = ob_get_clean();
                        $mime = 'image/jpeg';
                    }
                    imagedestroy($img);
                }
            } catch (\Throwable $e) {
                // If GD optimization fails, fallback to raw uploaded contents
            }
        }

        return 'data:'.$mime.';base64,'.base64_encode($contents);
    }

    /**
     * Sanitize HTML content to prevent stored XSS attacks while safely allowing images.
     */
    protected function sanitizeHtml(string $html): string
    {
        // Remove script tags and their content
        $clean = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);

        // Remove dangerous object/iframe/embed/form elements
        $clean = preg_replace('/<(iframe|object|embed|applet|form|input|button|textarea|select)\b[^>]*>/is', '', $clean);
        $clean = preg_replace('/<\/(iframe|object|embed|applet|form|textarea|select)>/is', '', $clean);

        // Remove javascript: pseudo-protocol from href and src
        $clean = preg_replace('/(href|src)\s*=\s*["\']\s*javascript:[^"\']*["\']/is', '$1="#"', $clean);

        // Block non-image data: URIs (e.g. data:text/html) but allow image data: URIs
        $clean = preg_replace('/href\s*=\s*["\']\s*data:[^"\']*["\']/is', 'href="#"', $clean);
        $clean = preg_replace('/src\s*=\s*["\']\s*data:(?!image\/(png|jpe?g|webp|gif|svg\+xml))[^"\']*["\']/is', 'src="#"', $clean);

        // Remove inline event handlers (onload, onerror, onclick, etc.)
        $clean = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/is', '', $clean);

        return $clean;
    }
}
