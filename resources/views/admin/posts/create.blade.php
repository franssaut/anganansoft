@extends('layouts.admin')

@section('title', 'Tambah Postingan Baru')
@section('header_title', 'Buat Postingan Baru')

@section('content')

    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div style="display:grid; grid-template-columns:2fr 1fr; gap:25px;">
            <!-- Left Main Column: Post Content & Software Details -->
            <div>
                <!-- General Info Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-pen-nib" style="color:#0284c7;"></i> Konten Artikel</div>
                    </div>
                    <div class="card-body">
                        <!-- Judul -->
                        <div class="form-group">
                            <label class="form-label" for="title">Judul Postingan / Software <span style="color:#ef4444;">*</span></label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="Contoh: Samurai Warrior 2 [PC] atau Internet Download Manager 6.35..." value="{{ old('title') }}" required>
                        </div>

                        <!-- Slug -->
                        <div class="form-group">
                            <label class="form-label" for="slug">URL Slug (Otomatis dibuat jika kosong)</label>
                            <input type="text" id="slug" name="slug" class="form-control" placeholder="samurai-warrior-2-pc" value="{{ old('slug') }}">
                        </div>

                        <!-- Excerpt -->
                        <div class="form-group">
                            <label class="form-label" for="excerpt">Ringkasan Singkat (Excerpt)</label>
                            <textarea id="excerpt" name="excerpt" class="form-control" rows="3" placeholder="Ringkasan singkat yang menarik untuk tampilan daftar artikel...">{{ old('excerpt') }}</textarea>
                        </div>

                        <!-- Content Editor -->
                        <div class="form-group">
                            <label class="form-label" for="content">Isi Konten Artikel <span style="color:#ef4444;">*</span></label>

                            <!-- Formatting Toolbar Helper -->
                            <div style="background:#f1f5f9; padding:8px 12px; border-radius:6px 6px 0 0; border:1px solid var(--border); border-bottom:none; display:flex; flex-wrap:wrap; gap:6px; align-items:center;">
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertTag('<b>', '</b>')"><b>B</b></button>
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertTag('<i>', '</i>')"><i>I</i></button>
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertTag('<h3>', '</h3>')">H3</button>
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertTag('<p>', '</p>')">P</button>
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertTag('<ul>\n  <li>', '</li>\n</ul>')">List</button>
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertTag('<blockquote>', '</blockquote>')"><i class="fa-solid fa-quote-left"></i></button>
                                <button type="button" class="btn btn-sm" style="background:#fff;" onclick="insertLink()"><i class="fa-solid fa-link"></i> Link</button>
                                <div style="width:1px; height:20px; background:var(--border); margin:0 4px;"></div>
                                <button type="button" class="btn btn-sm" style="background:#e0f2fe; color:#0369a1; font-weight:600;" onclick="openInsertImageModal()"><i class="fa-solid fa-image"></i> Sisipkan Gambar URL</button>
                                <button type="button" class="btn btn-sm" style="background:#dcfce7; color:#15803d; font-weight:600;" onclick="triggerContentImageUpload()"><i class="fa-solid fa-cloud-arrow-up"></i> Upload Gambar Artikel</button>
                                <input type="file" id="content-image-input" accept="image/*" style="display:none;" onchange="handleContentImageFile(this)">
                            </div>

                            <textarea id="content" name="content" class="form-control" rows="12" style="border-radius:0 0 6px 6px; font-family:monospace; line-height:1.5;" placeholder="Tuliskan ulasan software, fitur-fitur, dan deskripsi lengkap di sini..." required>{{ old('content') }}</textarea>
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
                                <input type="text" id="software_version" name="software_version" class="form-control" placeholder="Contoh: v6.35 Build 18" value="{{ old('software_version') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="file_size">Ukuran File</label>
                                <input type="text" id="file_size" name="file_size" class="form-control" placeholder="Contoh: 14.8 MB atau 1.2 GB" value="{{ old('file_size') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="license_type">Tipe Lisensi</label>
                                <input type="text" id="license_type" name="license_type" class="form-control" placeholder="Contoh: Full Version / Pre-activated" value="{{ old('license_type', 'Full Version') }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="os_support">Sistem Operasi</label>
                                <input type="text" id="os_support" name="os_support" class="form-control" placeholder="Contoh: Windows 11 / 10 / 8 / 7" value="{{ old('os_support', 'Windows 11 / 10 / 8 / 7') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_archive">Password Arsip RAR / ZIP</label>
                            <input type="text" id="password_archive" name="password_archive" class="form-control" placeholder="Contoh: anganansoft" value="{{ old('password_archive', 'anganansoft') }}">
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
                                <div class="mirror-row" style="display:grid; grid-template-columns:1fr 2fr 40px; gap:10px; align-items:center;">
                                    <input type="text" name="download_names[]" class="form-control" placeholder="Nama Server (e.g. Google Drive)" value="Google Drive">
                                    <input type="url" name="download_urls[]" class="form-control" placeholder="https://drive.google.com/..." value="">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeMirrorRow(this)"><i class="fa-solid fa-times"></i></button>
                                </div>
                                <div class="mirror-row" style="display:grid; grid-template-columns:1fr 2fr 40px; gap:10px; align-items:center;">
                                    <input type="text" name="download_names[]" class="form-control" placeholder="Nama Server (e.g. Mega.nz)" value="Mega.nz">
                                    <input type="url" name="download_urls[]" class="form-control" placeholder="https://mega.nz/..." value="">
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeMirrorRow(this)"><i class="fa-solid fa-times"></i></button>
                                </div>
                            </div>
                        </div>

                        <!-- System Requirements -->
                        <div class="form-group" style="margin-top:20px;">
                            <label class="form-label" for="system_requirements">Kebutuhan Sistem (System Requirements)</label>
                            <textarea id="system_requirements" name="system_requirements" class="form-control" rows="4" placeholder="OS: Windows 10 / 11&#10;CPU: Intel Core i3 atau lebih tinggi&#10;RAM: 4 GB&#10;Storage: 2 GB ruang kosong">{{ old('system_requirements') }}</textarea>
                        </div>

                        <!-- Installation Guide -->
                        <div class="form-group">
                            <label class="form-label" for="installation_guide">Panduan Instalasi & Aktivasi</label>
                            <textarea id="installation_guide" name="installation_guide" class="form-control" rows="4" placeholder="1. Ekstrak file dengan WinRAR.&#10;2. Jalankan setup.exe hingga selesai.&#10;3. Buka folder Crack dan copy ke folder instalasi.&#10;4. Nikmati full version!">{{ old('installation_guide') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings & Publish Box -->
            <div>
                <!-- Publish Box -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-paper-plane" style="color:#10b981;"></i> Publikasi</div>
                    </div>
                    <div class="card-body">
                        <!-- Status -->
                        <div class="form-group">
                            <label class="form-label" for="status">Status Postingan</label>
                            <select id="status" name="status" class="form-control">
                                <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Diterbitkan (Published)</option>
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draf (Draft)</option>
                            </select>
                        </div>

                        <!-- Is Featured -->
                        <div class="form-group">
                            <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-size:13.5px; font-weight:600; color:#334155;">
                                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                Jadikan Entri Diunggulkan (Featured)
                            </label>
                        </div>

                        <hr style="border:none; border-top:1px solid var(--border); margin:18px 0;">

                        <button type="submit" class="btn btn-teal" style="width:100%; padding:12px;">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan & Publikasikan
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
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <div style="margin-top:10px; font-size:12px;">
                            <a href="{{ route('admin.categories.index') }}" target="_blank" style="color:#0284c7;">+ Buat Kategori Baru</a>
                        </div>
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
                            <label class="form-label">Upload Gambar dari Komputer</label>
                            <input type="file" name="featured_image_file" class="form-control" accept="image/*" onchange="previewUploadImage(this)">
                            <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">Format: JPG, PNG, WebP, GIF (Maks. 2MB). Otomatis dioptimalkan.</div>
                        </div>

                        <div style="text-align:center; font-size:11.5px; color:var(--text-muted); margin:10px 0;">— ATAU —</div>

                        <!-- Option 2: Image URL -->
                        <div class="form-group">
                            <label class="form-label">URL Gambar Eksternal</label>
                            <input type="url" id="featured_image_url" name="featured_image_url" class="form-control" placeholder="https://..." value="{{ old('featured_image_url') }}" onchange="previewUrlImage(this.value)">
                            <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">Contoh: link dari Blogger, Google Drive, PostImages, Unsplash, dll.</div>
                        </div>

                        <!-- Image Preview Box -->
                        <div id="image-preview-wrap" style="margin-top:12px; border:1px dashed var(--border); border-radius:6px; height:150px; display:flex; align-items:center; justify-content:center; overflow:hidden; background:#f8fafc;">
                            <img id="image-preview" src="" alt="Pratinjau Gambar" style="display:none; width:100%; height:100%; object-fit:cover;">
                            <span id="image-preview-placeholder" style="color:var(--text-muted); font-size:12px;"><i class="fa-regular fa-image"></i> Belum ada gambar dipilih</span>
                        </div>
                    </div>
                </div>

                <!-- Tags Card -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-tags" style="color:#8b5cf6;"></i> Tag / Label</div>
                    </div>
                    <div class="card-body">
                        <input type="text" name="tags" class="form-control" placeholder="PC Games, Full Version, Crack" value="{{ old('tags') }}">
                        <div style="font-size:11.5px; color:var(--text-muted); margin-top:6px;">Pisahkan antar tag dengan koma (,).</div>
                    </div>
                </div>
            </div>
        </div>
    </form>

@endsection

@push('scripts')
<script>
    // Auto generate slug from title
    document.getElementById('title').addEventListener('input', function() {
        var slugInput = document.getElementById('slug');
        if (!slugInput.value || slugInput.dataset.manual !== 'true') {
            slugInput.value = this.value
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });

    document.getElementById('slug').addEventListener('change', function() {
        this.dataset.manual = 'true';
    });

    // Formatting Tag inserter
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

    function insertRawHtml(html) {
        var textarea = document.getElementById('content');
        var start = textarea.selectionStart;
        var end = textarea.selectionEnd;
        var text = textarea.value;
        textarea.value = text.substring(0, start) + html + text.substring(end);
        textarea.focus();
        textarea.setSelectionRange(start + html.length, start + html.length);
    }

    function openInsertImageModal() {
        var url = prompt('Masukkan URL gambar eksternal (https://...):', 'https://');
        if (url && url !== 'https://' && url.trim().length > 0) {
            var alt = prompt('Keterangan / Alt text gambar (opsional):', 'Screenshot');
            var cleanAlt = (alt || 'Screenshot').replace(/"/g, '&quot;');
            var imgTag = '\n<p style="text-align:center;"><img src="' + url.trim() + '" alt="' + cleanAlt + '" style="max-width:100%; height:auto; border-radius:6px; margin:15px auto; display:block; box-shadow:0 2px 8px rgba(0,0,0,0.08);"></p>\n';
            insertRawHtml(imgTag);
        }
    }

    function triggerContentImageUpload() {
        document.getElementById('content-image-input').click();
    }

    function handleContentImageFile(input) {
        if (!input.files || !input.files[0]) return;
        var file = input.files[0];
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = new Image();
            img.onload = function() {
                var canvas = document.createElement('canvas');
                var maxW = 900;
                var width = img.width;
                var height = img.height;
                if (width > maxW) {
                    height = Math.round(height * (maxW / width));
                    width = maxW;
                }
                canvas.width = width;
                canvas.height = height;
                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                var dataUrl = canvas.toDataURL('image/webp', 0.85);
                var altName = file.name.replace(/\.[^/.]+$/, "");
                var imgTag = '\n<p style="text-align:center;"><img src="' + dataUrl + '" alt="' + altName + '" style="max-width:100%; height:auto; border-radius:6px; margin:15px auto; display:block; box-shadow:0 2px 8px rgba(0,0,0,0.08);"></p>\n';
                insertRawHtml(imgTag);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
        input.value = '';
    }

    // Mirror Server Row Repeater
    function addMirrorRow() {
        var container = document.getElementById('mirrors-container');
        var div = document.createElement('div');
        div.className = 'mirror-row';
        div.style.cssText = 'display:grid; grid-template-columns:1fr 2fr 40px; gap:10px; align-items:center;';
        div.innerHTML = `
            <input type="text" name="download_names[]" class="form-control" placeholder="Nama Server (e.g. MediaFire)">
            <input type="url" name="download_urls[]" class="form-control" placeholder="https://...">
            <button type="button" class="btn btn-sm btn-danger" onclick="removeMirrorRow(this)"><i class="fa-solid fa-times"></i></button>
        `;
        container.appendChild(div);
    }

    function removeMirrorRow(btn) {
        btn.closest('.mirror-row').remove();
    }

    // Image preview helper
    function previewUploadImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var preview = document.getElementById('image-preview');
                var placeholder = document.getElementById('image-preview-placeholder');
                preview.src = e.target.result;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewUrlImage(url) {
        if (url) {
            var preview = document.getElementById('image-preview');
            var placeholder = document.getElementById('image-preview-placeholder');
            preview.src = url;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
        }
    }
</script>
@endpush
