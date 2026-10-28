@extends('layouts.admin')

@section('title', 'Edit Postingan: ' . $post->title)
@section('header_title', 'Edit Postingan')

@section('content')

    <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div style="display:grid; grid-template-columns:2fr 1fr; gap:25px;">
            <!-- Left Main Column: Post Content & Software Details -->
            <div>
                <!-- General Info Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-pen-nib" style="color:#0284c7;"></i> Edit Konten Artikel</div>
                    </div>
                    <div class="card-body">
                        <!-- Judul -->
                        <div class="form-group">
                            <label class="form-label" for="title">Judul Postingan / Software <span style="color:#ef4444;">*</span></label>
                            <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
                        </div>

                        <!-- Slug -->
                        <div class="form-group">
                            <label class="form-label" for="slug">URL Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $post->slug) }}">
                        </div>

                        <!-- Excerpt -->
                        <div class="form-group">
                            <label class="form-label" for="excerpt">Ringkasan Singkat (Excerpt)</label>
                            <textarea id="excerpt" name="excerpt" class="form-control" rows="3">{{ old('excerpt', $post->excerpt) }}</textarea>
                        </div>

                        <!-- Content Editor -->
                        <div class="form-group">
                            <label class="form-label" for="content">Isi Konten Artikel <span style="color:#ef4444;">*</span></label>

                            <!-- Formatting Toolbar Helper -->
                            <div style="background:#f1f5f9; padding:8px 12px; border-radius:6px 6px 0 0; border:1px solid var(--border); border-bottom:none; display:flex; flex-wrap:wrap; gap:6px;">
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertTag('<b>', '</b>')"><b>B</b></button>
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertTag('<i>', '</i>')"><i>I</i></button>
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertTag('<h3>', '</h3>')">H3</button>
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertTag('<p>', '</p>')">P</button>
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertTag('<ul>\n  <li>', '</li>\n</ul>')">List</button>
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertTag('<blockquote>', '</blockquote>')"><i class="fa-solid fa-quote-left"></i></button>
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertLink()"><i class="fa-solid fa-link"></i> Link</button>
                            </div>

                            <textarea id="content" name="content" class="form-control" rows="12" style="border-radius:0 0 6px 6px; font-family:monospace; line-height:1.5;" required>{{ old('content', $post->content) }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Software Details & Download Box Specifications -->
                <div class="card" style="border-top:3px solid var(--primary-accent);">
                    <div class="card-header" style="background:#f8fafc;">
                        <div class="card-title">
                            <i class="fa-solid fa-cloud-arrow-down" style="color:var(--primary-accent);"></i> Spesifikasi Software & Link Unduhan
                        </div>
                    </div>
                    <div class="card-body">
                        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                            <div class="form-group">
                                <label class="form-label" for="software_version">Versi Software</label>
                                <input type="text" id="software_version" name="software_version" class="form-control" value="{{ old('software_version', $post->software_version) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="file_size">Ukuran File</label>
                                <input type="text" id="file_size" name="file_size" class="form-control" value="{{ old('file_size', $post->file_size) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="license_type">Tipe Lisensi</label>
                                <input type="text" id="license_type" name="license_type" class="form-control" value="{{ old('license_type', $post->license_type) }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="os_support">Sistem Operasi</label>
                                <input type="text" id="os_support" name="os_support" class="form-control" value="{{ old('os_support', $post->os_support) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_archive">Password Arsip RAR / ZIP</label>
                            <input type="text" id="password_archive" name="password_archive" class="form-control" value="{{ old('password_archive', $post->password_archive) }}">
                        </div>

                        <!-- Dynamic Download Mirrors -->
                        <div class="form-group" style="margin-top:20px;">
                            <label class="form-label" style="display:flex; justify-content:space-between; align-items:center;">
                                <span>Mirror Server Download:</span>
                                <button type="button" class="btn btn-sm btn-teal" onclick="addMirrorRow()">
                                    <i class="fa-solid fa-plus"></i> Tambah Server
                                </button>
                            </label>

                            <div id="mirrors-container" style="display:flex; flex-direction:column; gap:10px;">
                                @if(!empty($post->download_links) && is_array($post->download_links) && count($post->download_links) > 0)
                                    @foreach($post->download_links as $link)
                                        <div class="mirror-row" style="display:grid; grid-template-columns:1fr 2fr 40px; gap:10px; align-items:center;">
                                            <input type="text" name="download_names[]" class="form-control" placeholder="Nama Server" value="{{ $link['name'] }}">
                                            <input type="url" name="download_urls[]" class="form-control" placeholder="https://..." value="{{ $link['url'] }}">
                                            <button type="button" class="btn btn-sm btn-danger" onclick="removeMirrorRow(this)"><i class="fa-solid fa-times"></i></button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="mirror-row" style="display:grid; grid-template-columns:1fr 2fr 40px; gap:10px; align-items:center;">
                                        <input type="text" name="download_names[]" class="form-control" placeholder="Nama Server" value="Google Drive">
                                        <input type="url" name="download_urls[]" class="form-control" placeholder="https://drive.google.com/..." value="">
                                        <button type="button" class="btn btn-sm btn-danger" onclick="removeMirrorRow(this)"><i class="fa-solid fa-times"></i></button>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- System Requirements -->
                        <div class="form-group" style="margin-top:20px;">
                            <label class="form-label" for="system_requirements">Kebutuhan Sistem (System Requirements)</label>
                            <textarea id="system_requirements" name="system_requirements" class="form-control" rows="4">{{ old('system_requirements', $post->system_requirements) }}</textarea>
                        </div>

                        <!-- Installation Guide -->
                        <div class="form-group">
                            <label class="form-label" for="installation_guide">Panduan Instalasi & Aktivasi</label>
                            <textarea id="installation_guide" name="installation_guide" class="form-control" rows="4">{{ old('installation_guide', $post->installation_guide) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Publish Box -->
            <div>
                <!-- Publish Box -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-paper-plane" style="color:#10b981;"></i> Update Postingan</div>
                    </div>
                    <div class="card-body">
                        <!-- Status -->
                        <div class="form-group">
                            <label class="form-label" for="status">Status Postingan</label>
                            <select id="status" name="status" class="form-control">
                                <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Diterbitkan (Published)</option>
                                <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draf (Draft)</option>
                            </select>
                        </div>

                        <!-- Is Featured -->
                        <div class="form-group">
                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13.5px; font-weight:600; color:#334155;">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $post->is_featured) ? 'checked' : '' }}>
                                Jadikan Entri Diunggulkan (Featured)
                            </label>
                        </div>

                        <div style="font-size:12px; color:var(--text-muted); margin-bottom:14px;">
                            <div><i class="fa-regular fa-eye"></i> Views: <strong>{{ number_format($post->views) }}</strong></div>
                            <div><i class="fa-regular fa-calendar"></i> Dibuat: {{ $post->created_at->format('d/m/Y H:i') }}</div>
                        </div>

                        <hr style="border:none; border-top:1px solid var(--border); margin:18px 0;">

                        <button type="submit" class="btn btn-teal" style="width:100%; padding:12px;">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                    </div>
                </div>

                <!-- Category Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-folder" style="color:#0284c7;"></i> Kategori <span style="color:#ef4444;">*</span></div>
                    </div>
                    <div class="card-body">
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $post->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Featured Image Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-image" style="color:#f59e0b;"></i> Gambar Utama (Thumbnail)</div>
                    </div>
                    <div class="card-body">
                        <!-- Option 1: File Upload -->
                        <div class="form-group">
                            <label class="form-label">Upload Gambar Baru</label>
                            <input type="file" name="featured_image_file" class="form-control" accept="image/*" onchange="previewUploadImage(this)">
                        </div>

                        <div style="text-align:center; font-size:11.5px; color:var(--text-muted); margin:10px 0;">— ATAU —</div>

                        <!-- Option 2: Image URL -->
                        <div class="form-group">
                            <label class="form-label">URL Gambar Eksternal</label>
                            <input type="url" id="featured_image_url" name="featured_image_url" class="form-control" value="{{ old('featured_image_url', Str::startsWith($post->featured_image, ['http://', 'https://']) ? $post->featured_image : '') }}" onchange="previewUrlImage(this.value)">
                        </div>

                        <!-- Image Preview Box -->
                        <div id="image-preview-wrap" style="margin-top:12px; border:1px solid var(--border); border-radius:6px; height:150px; display:flex; align-items:center; justify-content:center; overflow:hidden; background:#f8fafc;">
                            <img id="image-preview" src="{{ $post->image_url }}" alt="Pratinjau Gambar" style="width:100%; height:100%; object-fit:cover;">
                        </div>
                    </div>
                </div>

                <!-- Tags Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-tags" style="color:#8b5cf6;"></i> Tag / Label</div>
                    </div>
                    <div class="card-body">
                        <input type="text" name="tags" class="form-control" value="{{ old('tags', $currentTags) }}">
                        <div style="font-size:11.5px; color:var(--text-muted); margin-top:6px;">Pisahkan antar tag dengan koma (,).</div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
<script>
    function insertTag(open, close) {
        var textarea = document.getElementById('content');
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var text = textarea.value;
        var selected = text.substring(start, end);
        textarea.value = text.substring(0, start) + open + selected + close + text.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + open.length, end + open.length);
    }

    function insertLink() {
        var url = prompt('Masukkan URL tautan:', 'https://');
        if (url) {
            insertTag('<a href="' + url + '">', '</a>');
        }
    }

    function addMirrorRow() {
        var container = document.getElementById('mirrors-container');
        var div = document.createElement('div');
        div.className = 'mirror-row';
        div.style.cssText = 'display:grid; grid-template-columns:1fr 2fr 40px; gap:10px; align-items:center;';
        div.innerHTML = `
            <input type="text" name="download_names[]" class="form-control" placeholder="Nama Server">
            <input type="url" name="download_urls[]" class="form-control" placeholder="https://...">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeMirrorRow(this)"><i class="fa-solid fa-times"></i></button>
        `;
        container.appendChild(div);
    }

    function removeMirrorRow(btn) {
        btn.closest('.mirror-row').remove();
    }

    function previewUploadImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('image-preview');
                preview.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewUrlImage(url) {
        if (url) {
            var preview = document.getElementById('image-preview');
            preview.src = url;
        }
    }
</script>
@endpush
