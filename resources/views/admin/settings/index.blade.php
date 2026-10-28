@extends('layouts.admin')

@section('title', 'Pengaturan Website & Keamanan')
@section('header_title', 'Pengaturan Website & Keamanan Akun')

@section('content')

    <div style="display:grid; grid-template-columns:2fr 1fr; gap:25px;">
        <!-- Left Column: Identitas & SEO -->
        <div>
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-sliders" style="color:#0284c7;"></i> Informasi Situs & SEO</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label" for="site_name">Nama Website / Blog <span style="color:#ef4444;">*</span></label>
                            <input type="text" id="site_name" name="site_name" class="form-control" value="{{ old('site_name', $settings['site_name']) }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="site_tagline">Tagline / Slogan Header</label>
                            <input type="text" id="site_tagline" name="site_tagline" class="form-control" value="{{ old('site_tagline', $settings['site_tagline']) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="site_description">Deskripsi Meta SEO</label>
                            <textarea id="site_description" name="site_description" class="form-control" rows="3">{{ old('site_description', $settings['site_description']) }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="site_keywords">Kata Kunci Meta (Keywords)</label>
                            <input type="text" id="site_keywords" name="site_keywords" class="form-control" value="{{ old('site_keywords', $settings['site_keywords']) }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="footer_about">Teks Informasi Footer</label>
                            <textarea id="footer_about" name="footer_about" class="form-control" rows="3">{{ old('footer_about', $settings['footer_about']) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-share-nodes" style="color:#0284c7;"></i> Tautan Media Sosial</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label" for="facebook_url"><i class="fa-brands fa-facebook" style="color:#3b5998;"></i> URL Facebook Fanpage</label>
                            <input type="url" id="facebook_url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $settings['facebook_url']) }}">
                        </div>

                        <button type="submit" class="btn btn-teal" style="padding:10px 20px;">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan Website
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Right Column: Unduhan & Keamanan Akun -->
        <div>
            <!-- Download Options Card -->
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <input type="hidden" name="site_name" value="{{ $settings['site_name'] }}">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title"><i class="fa-solid fa-key" style="color:#f59e0b;"></i> Password Default & Kontak</div>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-label" for="archive_password">Password Default Arsip (RAR / ZIP)</label>
                            <input type="text" id="archive_password" name="archive_password" class="form-control" value="{{ old('archive_password', $settings['archive_password']) }}">
                            <div style="font-size:11.5px; color:var(--text-muted); margin-top:5px;">Password ini digunakan sebagai default saat posting software baru.</div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="contact_email">Email Kontak</label>
                            <input type="email" id="contact_email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings['contact_email']) }}">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width:100%;">
                            <i class="fa-solid fa-floppy-disk"></i> Update Opsi Unduhan
                        </button>
                    </div>
                </div>
            </form>

            <!-- Security: Change Admin Password -->
            <div class="card" style="border-top:3px solid #ef4444;">
                <div class="card-header" style="background:#fef2f2;">
                    <div class="card-title" style="color:#991b1b;"><i class="fa-solid fa-shield-halved"></i> Keamanan Akun Administrator</div>
                </div>
                <div class="card-body">
                    <p style="font-size:12.5px; color:var(--text-muted); margin-bottom:15px;">
                        Ganti kata sandi bawaan (<code>password</code>) dengan kata sandi kuat sebelum meluncurkan ke server production.
                    </p>

                    <form action="{{ route('admin.settings.password') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="form-label" for="current_password">Kata Sandi Saat Ini</label>
                            <input type="password" id="current_password" name="current_password" class="form-control" required autocomplete="current-password">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">Kata Sandi Baru (Min. 8 Karakter)</label>
                            <input type="password" id="password" name="password" class="form-control" required autocomplete="new-password">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required autocomplete="new-password">
                        </div>

                        <button type="submit" class="btn btn-danger" style="width:100%;">
                            <i class="fa-solid fa-lock"></i> Ganti Kata Sandi Admin
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
