@extends('layouts.app')

@section('title', isset($search) && $search ? 'Hasil Pencarian: ' . $search : 'Download Software PC, Game, Windows Full Version')

@section('content')

    @if(isset($search) && $search)
        <div style="background:#fff; border:1px solid var(--border-color); padding:16px 20px; border-radius:6px; margin-bottom:20px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <span style="font-size:12px; color:var(--text-muted); text-transform:uppercase; font-weight:700;">Pencarian</span>
                <h2 style="font-size:18px; color:var(--text-heading); margin-top:2px;">Hasil untuk: "<em>{{ $search }}</em>"</h2>
            </div>
            <a href="{{ route('blog.index') }}" style="font-size:12.5px; font-weight:600; color:#ef4444;"><i class="fa-solid fa-xmark"></i> Hapus Filter</a>
        </div>
    @endif

    @forelse($posts as $post)
        <article class="post-card">
            <div class="post-thumbnail-wrapper">
                <a href="{{ route('blog.show', $post->slug) }}">
                    <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="post-thumbnail" loading="lazy">
                </a>
                <a href="{{ route('blog.category', $post->category->slug) }}" class="category-badge">
                    {{ $post->category->name }}
                </a>
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
                        <span><i class="fa-regular fa-comments"></i> {{ $post->comments()->where('status', 'approved')->count() }}</span>
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
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" target="_blank" class="share-icon-btn share-fb" title="Bagikan ke Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $post->slug)) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-icon-btn share-tw" title="Bagikan ke Twitter">
                            <i class="fa-brands fa-x-twitter"></i>
                        </a>
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . route('blog.show', $post->slug)) }}" target="_blank" class="share-icon-btn share-wa" title="Bagikan ke WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
            </div>
        </article>
    @empty
        <div style="background:#fff; border:1px solid var(--border-color); padding:40px 20px; border-radius:6px; text-align:center;">
            <i class="fa-solid fa-file-circle-question" style="font-size:48px; color:#cbd5e1; margin-bottom:15px;"></i>
            <h3 style="font-size:18px; color:var(--text-heading); margin-bottom:8px;">Belum Ada Artikel Ditemukan</h3>
            <p style="color:var(--text-muted); font-size:14px; max-width:400px; margin:0 auto 20px;">Maaf, artikel atau software yang Anda cari belum tersedia. Coba gunakan kata kunci pencarian yang lain.</p>
            <a href="{{ route('blog.index') }}" class="read-more-btn">Kembali ke Beranda</a>
        </div>
    @endforelse

    <!-- Pagination -->
    @if($posts->hasPages())
        <div class="pagination-wrap">
            {{ $posts->links('pagination::simple-bootstrap-4') }}
        </div>
    @endif

@endsection
