@extends('layouts.app')

@section('title', '419 - Sesi Kedaluwarsa')

@section('content')
    <div style="background:#fff; border:1px solid var(--border-color); border-radius:8px; padding:60px 20px; text-align:center;">
        <div style="font-size:72px; font-family:var(--font-heading); font-weight:700; color:#f59e0b; line-height:1; margin-bottom:12px;">
            419
        </div>
        <h2 style="font-size:22px; color:var(--text-heading); margin-bottom:10px;">Sesi Kedaluwarsa (Page Expired)</h2>
        <p style="color:var(--text-muted); font-size:14px; max-width:480px; margin:0 auto 25px;">
            Halaman Anda telah kedaluwarsa karena tidak ada aktivitas dalam waktu tertentu atau token keamanan CSRF telah habis masa berlakunya. Silakan muat ulang halaman.
        </p>
        <a href="javascript:location.reload();" class="read-more-btn" style="padding:10px 24px; font-size:14px;">
            <i class="fa-solid fa-rotate-right"></i> Muat Ulang Halaman
        </a>
    </div>
@endsection
