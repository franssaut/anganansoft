@extends('layouts.app')

@section('title', 'Tag: #' . $tag->name . ' - Download Software PC')

@section('content')

    <div style="background:#fff; border:1px solid var(--border-color); padding:20px; border-radius:6px; margin-bottom:22px; border-left:4px solid var(--primary-color);">
        <span style="font-size:12px; color:var(--text-muted); text-transform:uppercase; font-weight:700;">Arsip Tag</span>
        <h1 style="font-size:24px; color:var(--text-heading); font-family:var(--font-heading); margin-top:2px;">
            <i class="fa-solid fa-tag" style="color:var(--primary-color);"></i> #{{ $tag->name }}
        </h1>
    </div>

    @forelse($posts as $post)
        <article class="post-card">
            <div class="post-thumbnail-wrapper">
                <a href="{{ route('blog.show', $post->slug) }}">
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="post-thumbnail" loading="lazy">
                </a>
                <span class="category-badge">{{ $post->category->name }}</span>
            </div>

            <div class="post-body">
                <div>
                    <h2 class="post-title">
                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                    </h2>

                    <div class="post-meta">
                        <span><i class="fa-regular fa-calendar"></i> {{ $post->created_at->format('d F Y') }}</span>
                        <span><i class="fa-regular fa-user"></i> {{ \App\Models\Setting::get('site_name', 'AngananSoft') }}</span>
                        <span><i class="fa-regular fa-eye"></i> {{ number_format($post->views) }} views</span>
                    </div>

                    <p class="post-excerpt">
                        {{ $post->excerpt }}
                    </p>
                </div>

                <div class="post-footer">
                    <a href="{{ route('blog.show', $post->slug) }}" class="read-more-btn">
                        Baca Selengkapnya <i class="fa-solid fa-arrow-right"></i>
                    </a>

                    <div class="social-share-mini">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" target="_blank" class="share-icon-btn share-fb"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-icon-btn share-tw"><i class="fa-brands fa-x-twitter"></i></a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . route('blog.show', $post->slug)) }}" target="_blank" class="share-icon-btn share-wa"><i class="fa-brands fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </article>
    @empty
        <div style="background:#fff; border:1px solid var(--border-color); padding:40px 20px; border-radius:6px; text-align:center;">
            <i class="fa-solid fa-tag" style="font-size:48px; color:#cbd5e1; margin-bottom:15px;"></i>
            <h3 style="font-size:18px; color:var(--text-heading); margin-bottom:8px;">Belum Ada Artikel dengan Tag Ini</h3>
            <p style="color:var(--text-muted); font-size:14px; margin-bottom:20px;">Silakan telusuri software lainnya di beranda.</p>
            <a href="{{ route('blog.index') }}" class="read-more-btn">Kembali ke Beranda</a>
        </div>
    @endforelse

    @if($posts->hasPages())
        <div class="pagination-wrap">
            {{ $posts->links('pagination::simple-bootstrap-4') }}
        </div>
    @endif

@endsection
