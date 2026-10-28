@extends('layouts.app')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
    <div style="background:#fff; border:1px solid var(--border-color); border-radius:8px; padding:60px 20px; text-align:center;">
        <div style="font-size:72px; font-family:var(--font-heading); font-weight:700; color:var(--primary-color); line-height:1; margin-bottom:12px;">
            404
        </div>
        <h2 style="font-size:22px; color:var(--text-heading); margin-bottom:10px;">Halaman Tidak Ditemukan</h2>
        <p style="color:var(--text-muted); font-size:14px; max-width:480px; margin:0 auto 25px;">
            Maaf, artikel atau halaman yang Anda cari mungkin telah dipindahkan, dihapus, atau alamat URL yang Anda masukkan salah.
        </p>
        <a href="{{ route('blog.index') }}" class="read-more-btn" style="padding:10px 24px; font-size:14px;">
            <i class="fa-solid fa-house"></i> Kembali ke Beranda
        </a>
    </div>
@endsection
