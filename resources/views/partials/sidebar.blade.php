<!-- Search Widget -->
<div class="widget">
    <div class="widget-title">
        <span><i class="fa-solid fa-magnifying-glass"></i> Cari Blog Ini</span>
    </div>
    <div class="widget-content">
        <form action="{{ route('blog.index') }}" method="GET" class="search-widget-form">
            <input type="text" name="q" placeholder="Ketik kata kunci..." value="{{ request('q') }}">
            <button type="submit">Cari</button>
        </form>
    </div>
</div>

<!-- Social Follow Widget -->
<div class="widget">
    <div class="widget-title">
        <span><i class="fa-solid fa-share-nodes"></i> Ikuti Kami</span>
    </div>
    <div class="widget-content">
        <div style="display:flex; gap:10px;">
            <a href="{{ \App\Models\Setting::get('facebook_url', '#') }}" target="_blank" style="flex:1; background:#3b5998; color:#fff; padding:10px; border-radius:4px; text-align:center; font-weight:700; font-size:12.5px;">
                <i class="fa-brands fa-facebook-f"></i> Facebook
            </a>
        </div>
    </div>
</div>

<!-- Featured Post Widget -->
@if(isset($featuredPost) && $featuredPost)
<div class="widget">
    <div class="widget-title">
        <span><i class="fa-solid fa-star" style="color:#f59e0b;"></i> Entri yang Diunggulkan</span>
    </div>
    <div class="widget-content" style="padding:0;">
        <div class="featured-widget-card">
            <a href="{{ route('blog.show', $featuredPost->slug) }}">
                <img src="{{ $featuredPost->image_url }}" alt="{{ $featuredPost->title }}">
            </a>
            <div class="featured-widget-overlay">
                <span class="category-badge" style="position:static; display:inline-block; margin-bottom:8px;">{{ $featuredPost->category->name }}</span>
                <h4><a href="{{ route('blog.show', $featuredPost->slug) }}" style="color:var(--text-heading);">{{ $featuredPost->title }}</a></h4>
                <p style="font-size:12.5px; color:var(--text-muted); line-height:1.4;">{{ Str::limit($featuredPost->excerpt, 90) }}</p>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Popular Posts Widget -->
@if(isset($popularPosts) && $popularPosts->count() > 0)
<div class="widget">
    <div class="widget-title">
        <span><i class="fa-solid fa-fire" style="color:#ef4444;"></i> Entri Populer</span>
    </div>
    <div class="widget-content">
        <ul class="popular-list">
            @foreach($popularPosts as $index => $pPost)
            <li class="popular-item">
                <div class="popular-thumb-wrap">
                    <span class="popular-rank">{{ $index + 1 }}</span>
                    <img src="{{ $pPost->image_url }}" alt="{{ $pPost->title }}" class="popular-thumb">
                </div>
                <div class="popular-info">
                    <h5><a href="{{ route('blog.show', $pPost->slug) }}">{{ Str::limit($pPost->title, 48) }}</a></h5>
                    <div class="date"><i class="fa-regular fa-clock"></i> {{ $pPost->created_at->format('d M Y') }} &bull; <i class="fa-regular fa-eye"></i> {{ number_format($pPost->views) }}</div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<!-- Categories Widget -->
@if(isset($categories) && $categories->count() > 0)
<div class="widget">
    <div class="widget-title">
        <span><i class="fa-solid fa-folder-open"></i> Kategori / Label</span>
    </div>
    <div class="widget-content">
        <ul class="cat-list">
            @foreach($categories as $cItem)
            <li>
                <a href="{{ route('blog.category', $cItem->slug) }}">
                    <span><i class="fa-solid fa-angle-right" style="color:var(--primary-color); margin-right:6px;"></i> {{ $cItem->name }}</span>
                    <span class="count">{{ $cItem->posts_count ?? $cItem->posts()->count() }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<!-- Tags Cloud Widget -->
@if(isset($tags) && $tags->count() > 0)
<div class="widget">
    <div class="widget-title">
        <span><i class="fa-solid fa-tags"></i> Tag Populer</span>
    </div>
    <div class="widget-content">
        <div class="tags-cloud">
            @foreach($tags as $tItem)
            <a href="{{ route('blog.tag', $tItem->slug) }}" class="tag-pill">
                #{{ $tItem->name }}
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif
