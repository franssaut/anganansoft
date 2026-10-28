@extends('layouts.admin')

@section('title', 'Kelola Postingan')
@section('header_title', 'Kelola Semua Postingan')

@section('content')

    <!-- Filter & Action Bar -->
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="padding:18px 24px;">
            <form action="{{ route('admin.posts.index') }}" method="GET" style="display:flex; flex-wrap:wrap; gap:14px; align-items:center; justify-content:space-between;">
                <div style="display:flex; flex-wrap:wrap; gap:12px; flex-grow:1; max-width:700px;">
                    <div style="flex-grow:1; min-width:200px;">
                        <input type="text" name="search" class="form-control" placeholder="Cari judul postingan..." value="{{ request('search') }}">
                    </div>

                    <div style="width:160px;">
                        <select name="category_id" class="form-control">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="width:140px;">
                        <select name="status" class="form-control">
                            <option value="">Semua Status</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>

                    @if(request()->hasAny(['search', 'category_id', 'status']))
                        <a href="{{ route('admin.posts.index') }}" class="btn" style="background:#e2e8f0; color:#475569;">Reset</a>
                    @endif
                </div>

                <div>
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-teal">
                        <i class="fa-solid fa-plus-circle"></i> Tambah Post Baru
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Posts Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width:70px;">Media</th>
                        <th>Judul & Info Software</th>
                        <th>Kategori</th>
                        <th>Versi & Ukuran</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Tanggal</th>
                        <th style="text-align:right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                        <tr>
                            <td>
                                <img src="{{ $post->image_url }}" alt="" style="width:55px; height:42px; object-fit:cover; border-radius:4px; border:1px solid #e2e8f0; display:block;">
                            </td>
                            <td>
                                <div style="font-weight:700; color:var(--text-dark); margin-bottom:2px;">
                                    {{ $post->title }}
                                    @if($post->is_featured)
                                        <span class="badge" style="background:#fef3c7; color:#d97706; font-size:10px;"><i class="fa-solid fa-star"></i> Featured</span>
                                    @endif
                                </div>
                                <div style="font-size:11.5px; color:var(--text-muted);">
                                    Slug: <code>/post/{{ $post->slug }}</code>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background:#e0f2fe; color:#0284c7;">{{ $post->category->name }}</span>
                            </td>
                            <td style="font-size:12.5px; color:#475569;">
                                <div>{{ $post->software_version ?? '-' }}</div>
                                <div style="color:var(--text-muted); font-size:11px;">{{ $post->file_size ?? '-' }}</div>
                            </td>
                            <td>
                                @if($post->status === 'published')
                                    <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Published</span>
                                @else
                                    <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Draft</span>
                                @endif
                            </td>
                            <td style="font-weight:700; color:#334155;">
                                {{ number_format($post->views) }}
                            </td>
                            <td style="font-size:12px; color:var(--text-muted);">
                                {{ $post->created_at->format('d/m/Y') }}
                            </td>
                            <td style="text-align:right;">
                                <div style="display:inline-flex; gap:6px;">
                                    <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-sm" style="background:#f1f5f9; color:#475569;" title="Lihat di Web">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-sm btn-primary" title="Edit Post">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus postingan ini? Tindakan ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Post">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" style="text-align:center; padding:40px; color:var(--text-muted);">
                                <i class="fa-solid fa-newspaper" style="font-size:32px; color:#cbd5e1; margin-bottom:10px; display:block;"></i>
                                Tidak ada postingan yang sesuai dengan pencarian Anda.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
            <div style="padding:18px 24px; border-top:1px solid var(--border); display:flex; justify-content:center;">
                {{ $posts->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>

@endsection
