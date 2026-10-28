@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header_title', 'Ringkasan Dashboard')

@section('content')

    <!-- Metrics Cards Grid -->
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-bottom:28px;">
        <!-- Card 1 -->
        <div class="card" style="margin-bottom:0; border-left:4px solid #0284c7;">
            <div class="card-body" style="display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <div style="font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Total Postingan</div>
                    <div style="font-size:28px; font-weight:800; color:var(--text-dark); margin-top:4px;">{{ $stats['total_posts'] }}</div>
                </div>
                <div style="width:48px; height:48px; border-radius:8px; background:#e0f2fe; color:#0284c7; display:flex; align-items:center; justify-content:center; font-size:20px;">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="card" style="margin-bottom:0; border-left:4px solid #10b981;">
            <div class="card-body" style="display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <div style="font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Diterbitkan</div>
                    <div style="font-size:28px; font-weight:800; color:#10b981; margin-top:4px;">{{ $stats['published_posts'] }}</div>
                </div>
                <div style="width:48px; height:48px; border-radius:8px; background:#dcfce7; color:#10b981; display:flex; align-items:center; justify-content:center; font-size:20px;">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="card" style="margin-bottom:0; border-left:4px solid #f59e0b;">
            <div class="card-body" style="display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <div style="font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Total Kategori</div>
                    <div style="font-size:28px; font-weight:800; color:var(--text-dark); margin-top:4px;">{{ $stats['total_categories'] }}</div>
                </div>
                <div style="width:48px; height:48px; border-radius:8px; background:#fef3c7; color:#f59e0b; display:flex; align-items:center; justify-content:center; font-size:20px;">
                    <i class="fa-solid fa-folder"></i>
                </div>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="card" style="margin-bottom:0; border-left:4px solid #8b5cf6;">
            <div class="card-body" style="display:flex; align-items:center; justify-content:space-between;">
                <div>
                    <div style="font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Total Pembaca</div>
                    <div style="font-size:28px; font-weight:800; color:var(--text-dark); margin-top:4px;">{{ number_format($stats['total_views']) }}</div>
                </div>
                <div style="width:48px; height:48px; border-radius:8px; background:#ede9fe; color:#8b5cf6; display:flex; align-items:center; justify-content:center; font-size:20px;">
                    <i class="fa-solid fa-eye"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Banner -->
    <div style="background:linear-gradient(135deg, #0f172a, #1e293b); color:#fff; padding:24px; border-radius:8px; margin-bottom:28px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
        <div>
            <h3 style="font-size:18px; font-weight:700; margin-bottom:4px;">Siap memposting software atau game baru?</h3>
            <p style="color:#94a3b8; font-size:13.5px;">Gunakan form posting lengkap dengan mirror download, spesifikasi sistem, dan panduan instalasi.</p>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('admin.posts.create') }}" class="btn btn-teal">
                <i class="fa-solid fa-plus-circle"></i> Buat Postingan Baru
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn" style="background:#334155; color:#fff;">
                <i class="fa-solid fa-tags"></i> Kelola Kategori
            </a>
        </div>
    </div>

    <!-- 2 Column Layout: Recent Posts & Sidebar Stats -->
    <div style="display:grid; grid-template-columns:2fr 1fr; gap:25px;">
        <!-- Recent Posts Table -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-clock-rotate-left" style="color:#0284c7;"></i> Postingan Terbaru</div>
                <a href="{{ route('admin.posts.index') }}" style="font-size:13px; font-weight:600; color:#0284c7;">Lihat Semua &rarr;</a>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Tayangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPosts as $post)
                            <tr>
                                <td>
                                    <div style="font-weight:600; color:var(--text-dark); margin-bottom:2px;">{{ Str::limit($post->title, 35) }}</div>
                                    <div style="font-size:11.5px; color:var(--text-muted);">{{ $post->created_at->format('d M Y') }}</div>
                                </td>
                                <td>
                                    <span class="badge" style="background:#e0f2fe; color:#0284c7;">{{ $post->category->name }}</span>
                                </td>
                                <td>
                                    @if($post->status === 'published')
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Published</span>
                                    @else
                                        <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Draft</span>
                                    @endif
                                </td>
                                <td style="font-weight:600; color:#475569;">
                                    {{ number_format($post->views) }}
                                </td>
                                <td>
                                    <div style="display:flex; gap:6px;">
                                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-sm" style="background:#f1f5f9; color:#475569;" title="Lihat di Web">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-primary" title="Edit Postingan">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; color:var(--text-muted); padding:30px;">Belum ada postingan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Popular Posts & Category Overview -->
        <div>
            <!-- Popular Posts -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-fire" style="color:#ef4444;"></i> Paling Banyak Dilihat</div>
                </div>
                <div class="card-body" style="padding:16px;">
                    <ul style="list-style:none; display:flex; flex-direction:column; gap:14px;">
                        @foreach($popularPosts as $idx => $pPost)
                            <li style="display:flex; align-items:center; gap:12px;">
                                <div style="width:26px; height:26px; border-radius:50%; background:#f1f5f9; color:#0f172a; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:12px; flex-shrink:0;">
                                    {{ $idx + 1 }}
                                </div>
                                <div style="flex-grow:1; min-width:0;">
                                    <a href="{{ route('blog.show', $pPost->slug) }}" target="_blank" style="font-size:13px; font-weight:600; color:var(--text-dark); display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $pPost->title }}
                                    </a>
                                    <div style="font-size:11.5px; color:var(--text-muted);">{{ number_format($pPost->views) }} views &bull; {{ $pPost->category->name }}</div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Categories Distribution -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title"><i class="fa-solid fa-pie-chart" style="color:#10b981;"></i> Sebaran Kategori</div>
                </div>
                <div class="card-body" style="padding:16px;">
                    <ul style="list-style:none; display:flex; flex-direction:column; gap:10px;">
                        @foreach($categories as $catItem)
                            <li style="display:flex; justify-content:space-between; align-items:center; font-size:13px;">
                                <span><i class="fa-solid fa-folder" style="color:var(--primary-accent); margin-right:6px;"></i> {{ $catItem->name }}</span>
                                <span class="badge badge-info">{{ $catItem->posts_count }} post</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

@endsection
