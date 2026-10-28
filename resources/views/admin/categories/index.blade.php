@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('header_title', 'Kelola Kategori Software & Game')

@section('content')

    <div style="display:grid; grid-template-columns:1fr 2fr; gap:25px;">
        <!-- Left: Add New Category Form -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-folder-plus" style="color:#0284c7;"></i> Tambah Kategori Baru</div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="name">Nama Kategori <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Contoh: Antivirus, Utility, macOS" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="slug">Slug (Opsional)</label>
                        <input type="text" id="slug" name="slug" class="form-control" placeholder="antivirus">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="color">Warna Label / Aksen</label>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <input type="color" id="color" name="color" value="#26c6da" style="width:45px; height:38px; border:1px solid var(--border); border-radius:6px; cursor:pointer;">
                            <span style="font-size:12px; color:var(--text-muted);">Pilih warna badge untuk kategori ini</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Deskripsi Singkat</label>
                        <textarea id="description" name="description" class="form-control" rows="3" placeholder="Penjelasan singkat mengenai kategori ini..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-teal" style="width:100%;">
                        <i class="fa-solid fa-plus-circle"></i> Tambah Kategori
                    </button>
                </form>
            </div>
        </div>

        <!-- Right: Categories List Table -->
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="fa-solid fa-list" style="color:#0284c7;"></i> Daftar Kategori ({{ $categories->count() }})</div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Warna</th>
                            <th>Nama Kategori</th>
                            <th>Slug</th>
                            <th>Jumlah Post</th>
                            <th style="text-align:right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                            <tr>
                                <td>
                                    <span style="display:inline-block; width:22px; height:22px; border-radius:50%; background:{{ $cat->color }}; border:2px solid #fff; box-shadow:0 0 0 1px #cbd5e1;"></span>
                                </td>
                                <td>
                                    <div style="font-weight:700; color:var(--text-dark);">{{ $cat->name }}</div>
                                    @if($cat->description)
                                        <div style="font-size:11.5px; color:var(--text-muted);">{{ Str::limit($cat->description, 50) }}</div>
                                    @endif
                                </td>
                                <td>
                                    <code style="font-size:12px; background:#f1f5f9; padding:2px 6px; border-radius:4px;">{{ $cat->slug }}</code>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $cat->posts_count }} Postingan</span>
                                </td>
                                <td style="text-align:right;">
                                    <div style="display:inline-flex; gap:6px;">
                                        <a href="{{ route('blog.category', $cat->slug) }}" target="_blank" class="btn btn-sm" style="background:#f1f5f9; color:#475569;" title="Lihat di Web">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>

                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori {{ $cat->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" {{ $cat->posts_count > 0 ? 'disabled title=Tidak-dapat-dihapus-karena-ada-post' : '' }}>
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:30px; color:var(--text-muted);">Belum ada kategori.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
