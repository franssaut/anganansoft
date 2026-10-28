@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', Str::limit(strip_tags($post->excerpt), 150))

@section('content')

    <article class="single-post-card">
        <!-- Breadcrumb -->
        <nav class="breadcrumb">
            <a href="{{ route('blog.index') }}"><i class="fa-solid fa-house"></i> Beranda</a>
            <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
            <a href="{{ route('blog.category', $post->category->slug) }}">{{ $post->category->name }}</a>
            <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
            <span>{{ Str::limit($post->title, 40) }}</span>
        </nav>

        <!-- Post Title -->
        <h1 class="single-title">{{ $post->title }}</h1>

        <!-- Post Meta -->
        <div class="post-meta" style="margin-bottom:20px; padding-bottom:14px; border-bottom:1px solid #edf2f7;">
            <span><i class="fa-regular fa-calendar"></i> {{ $post->created_at->format('d F Y') }}</span>
            <span><i class="fa-regular fa-user"></i> Diposting oleh <strong>{{ \App\Models\Setting::get('site_name', 'AngananSoft') }}</strong></span>
            <span><i class="fa-regular fa-folder"></i> <a href="{{ route('blog.category', $post->category->slug) }}">{{ $post->category->name }}</a></span>
            <span><i class="fa-regular fa-eye"></i> {{ number_format($post->views) }} kali dilihat</span>
            <span><i class="fa-regular fa-comments"></i> {{ $post->comments->count() }} Komentar</span>
        </div>

        <!-- Featured Image -->
        @if($post->featured_image)
            <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="single-featured-image">
        @endif

        <!-- Main Content -->
        <div class="post-content-body">
            {!! $post->content !!}
        </div>

        <!-- Download & Specifications Box (AngananSoft Signature) -->
        <div class="download-box">
            <div class="download-box-header">
                <i class="fa-solid fa-download"></i>
                <div>
                    <h3>Informasi & Link Download</h3>
                    <p style="font-size:12.5px; color:var(--text-muted);">Silakan download file melalui tautan mirror server berkecepatan tinggi di bawah ini.</p>
                </div>
            </div>

            <table class="specs-table">
                <tbody>
                    <tr>
                        <td>Nama Software / Game</td>
                        <td><strong>{{ $post->title }}</strong></td>
                    </tr>
                    @if($post->software_version)
                    <tr>
                        <td>Versi Software</td>
                        <td>{{ $post->software_version }}</td>
                    </tr>
                    @endif
                    @if($post->file_size)
                    <tr>
                        <td>Ukuran File</td>
                        <td><span style="background:#e0f2fe; color:#0369a1; padding:2px 8px; border-radius:4px; font-weight:700;">{{ $post->file_size }}</span></td>
                    </tr>
                    @endif
                    @if($post->license_type)
                    <tr>
                        <td>Tipe Lisensi</td>
                        <td><span style="color:#15803d; font-weight:700;"><i class="fa-solid fa-check-circle"></i> {{ $post->license_type }}</span></td>
                    </tr>
                    @endif
                    @if($post->os_support)
                    <tr>
                        <td>Sistem Operasi</td>
                        <td>{{ $post->os_support }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Password Arsip (RAR)</td>
                        <td>
                            <div class="password-badge">
                                <span>{{ $post->password_archive ?? \App\Models\Setting::get('archive_password', 'anganansoft') }}</span>
                                <button type="button" class="btn-copy" onclick="copyPassword('{{ $post->password_archive ?? \App\Models\Setting::get('archive_password', 'anganansoft') }}')">
                                    <i class="fa-regular fa-copy"></i> Salin
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Download Buttons -->
            @if(!empty($post->download_links) && is_array($post->download_links) && count($post->download_links) > 0)
                <h4 style="font-size:14px; font-weight:700; color:#1e293b; margin-bottom:10px;">
                    <i class="fa-solid fa-cloud-arrow-down" style="color:var(--primary-color);"></i> Pilih Server Download:
                </h4>
                <div class="download-buttons-grid">
                    @foreach($post->download_links as $idx => $link)
                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" class="download-btn {{ $idx % 2 == 1 ? 'mirror-2' : ($idx % 3 == 2 ? 'mirror-3' : '') }}">
                            <i class="fa-solid fa-download"></i> {{ $link['name'] }}
                        </a>
                    @endforeach
                </div>
            @else
                <div class="download-buttons-grid">
                    <a href="https://drive.google.com/" target="_blank" class="download-btn">
                        <i class="fa-solid fa-download"></i> Server 1: Google Drive
                    </a>
                    <a href="https://mega.nz/" target="_blank" class="download-btn mirror-2">
                        <i class="fa-solid fa-download"></i> Server 2: Mega.nz
                    </a>
                </div>
            @endif

            <!-- Installation Guide -->
            @if($post->installation_guide)
                <div class="info-card">
                    <h4><i class="fa-solid fa-list-check" style="color:#0ea5e9;"></i> Panduan Instalasi & Aktivasi:</h4>
                    <pre>{{ $post->installation_guide }}</pre>
                </div>
            @endif

            <!-- System Requirements -->
            @if($post->system_requirements)
                <div class="info-card">
                    <h4><i class="fa-solid fa-laptop-code" style="color:#8b5cf6;"></i> Kebutuhan Sistem Minimum:</h4>
                    <pre>{{ $post->system_requirements }}</pre>
                </div>
            @endif
        </div>

        <!-- Tags List -->
        @if($post->tags->count() > 0)
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin:25px 0;">
                <span style="font-weight:700; font-size:13px; color:var(--text-heading);"><i class="fa-solid fa-tags"></i> Tag:</span>
                @foreach($post->tags as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}" class="tag-pill">#{{ $tag->name }}</a>
                @endforeach
            </div>
        @endif

        <!-- Social Share Bottom -->
        <div style="background:#f8fafc; border:1px solid #e2e8f0; padding:15px; border-radius:6px; margin:25px 0; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <span style="font-weight:700; font-size:13px; color:#1e293b;">
                <i class="fa-solid fa-share-nodes" style="color:var(--primary-color);"></i> Bagikan artikel ini kepada teman Anda:
            </span>
            <div style="display:flex; gap:8px;">
                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" class="share-icon-btn share-fb" style="width:36px; height:36px; font-size:14px;" title="Bagikan ke Facebook">
                    <i class="fa-brands fa-facebook-f"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-icon-btn share-tw" style="width:36px; height:36px; font-size:14px;" title="Bagikan ke Twitter">
                    <i class="fa-brands fa-x-twitter"></i>
                </a>
                <a href="https://api.whatsapp.com/send?text={{ urlencode($post->title . ' ' . request()->url()) }}" target="_blank" class="share-icon-btn share-wa" style="width:36px; height:36px; font-size:14px;" title="Bagikan ke WhatsApp">
                    <i class="fa-brands fa-whatsapp"></i>
                </a>
                <a href="https://t.me/share/url?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" target="_blank" class="share-icon-btn" style="width:36px; height:36px; font-size:14px; background:#0088cc;" title="Bagikan ke Telegram">
                    <i class="fa-brands fa-telegram"></i>
                </a>
            </div>
        </div>

        <!-- Related Posts Section -->
        @if(isset($relatedPosts) && $relatedPosts->count() > 0)
            <div class="related-section">
                <h3 class="related-title">Artikel Terkait Lainnya</h3>
                <div class="related-grid">
                    @foreach($relatedPosts as $relPost)
                        <div class="related-item">
                            <a href="{{ route('blog.show', $relPost->slug) }}">
                                <img src="{{ $relPost->image_url }}" alt="{{ $relPost->title }}">
                            </a>
                            <div class="related-item-body">
                                <h5><a href="{{ route('blog.show', $relPost->slug) }}">{{ Str::limit($relPost->title, 45) }}</a></h5>
                                <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">{{ $relPost->created_at->format('d M Y') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </article>

    <!-- Comments Section -->
    <section class="comments-section" id="comments">
        <h3 style="font-size:18px; font-family:var(--font-heading); color:#1e293b; margin-bottom:20px; text-transform:uppercase; border-bottom:2px solid var(--primary-color); padding-bottom:8px;">
            <i class="fa-solid fa-comments"></i> Komentar ({{ $post->comments->count() }})
        </h3>

        @if($post->comments->count() > 0)
            <ul class="comments-list">
                @foreach($post->comments as $comment)
                    <li class="comment-item">
                        <div class="comment-avatar">
                            {{ strtoupper(substr($comment->author_name, 0, 1)) }}
                        </div>
                        <div class="comment-content">
                            <div class="comment-author-name">{{ $comment->author_name }}</div>
                            <div class="comment-date"><i class="fa-regular fa-clock"></i> {{ $comment->created_at->diffForHumans() }}</div>
                            <p class="comment-text">{{ $comment->content }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p style="color:var(--text-muted); font-size:13.5px; margin-bottom:25px; font-style:italic;">Belum ada komentar untuk artikel ini. Jadilah yang pertama berkomentar!</p>
        @endif

        <!-- Form Tambah Komentar -->
        <div style="background:#f8fafc; padding:20px; border-radius:6px; border:1px solid #e2e8f0;">
            <h4 style="font-size:15px; font-weight:700; color:#1e293b; margin-bottom:15px;">Tinggalkan Komentar</h4>

            <form action="{{ route('blog.comment', $post->slug) }}" method="POST">
                @csrf
                {{-- Anti-Spam Honeypot Field --}}
                <div style="display:none;" aria-hidden="true">
                    <label for="website_url_hp">Jangan isi kolom ini (anti-spam)</label>
                    <input type="text" name="website_url_hp" id="website_url_hp" tabindex="-1" autocomplete="off">
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">
                    <div class="comment-form-group">
                        <label for="author_name">Nama Lengkap *</label>
                        <input type="text" id="author_name" name="author_name" placeholder="Nama Anda" required value="{{ old('author_name') }}">
                    </div>
                    <div class="comment-form-group">
                        <label for="author_email">Email (Opsional)</label>
                        <input type="email" id="author_email" name="author_email" placeholder="nama@email.com" value="{{ old('author_email') }}">
                    </div>
                </div>

                <div class="comment-form-group">
                    <label for="content">Komentar / Pertanyaan *</label>
                    <textarea id="content" name="content" rows="4" placeholder="Tuliskan komentar atau kendala instalasi Anda..." required>{{ old('content') }}</textarea>
                </div>

                <button type="submit" class="btn-submit-comment">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Komentar
                </button>
            </form>
        </div>
    </section>

@endsection
