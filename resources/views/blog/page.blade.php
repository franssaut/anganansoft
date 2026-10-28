@extends('layouts.app')

@section('title', $pageTitle . ' - AngananSoft')

@section('content')

    <article class="single-post-card">
        <nav class="breadcrumb">
            <a href="{{ route('blog.index') }}"><i class="fa-solid fa-house"></i> Beranda</a>
            <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
            <span>{{ $pageTitle }}</span>
        </nav>

        <h1 class="single-title">{{ $pageTitle }}</h1>

        <div class="post-content-body" style="margin-top:20px;">
            @if($slug === 'tentang-kami')
                <p>Selamat datang di <strong>AngananSoft</strong>, pusat referensi dan penyedia unduhan berbagai macam software, aplikasi komputer, sistem operasi Windows, tools utilitas, serta PC Game terlengkap dan terpercaya.</p>

                <h3>Visi Kami</h3>
                <p>Menjadi media informasi teknologi yang memudahkan pengguna komputer di Indonesia dalam mendapatkan software berkualitas secara gratis, aman, dan mudah dipahami langkah instalasinya.</p>

                <h3>Apa yang Kami Sediakan?</h3>
                <ul>
                    <li><strong>Software & Utility:</strong> Koleksi software kebutuhan kantor, statistik, keamanan, dan optimasi PC.</li>
                    <li><strong>Multimedia:</strong> Software video editing, audio, fotografi, dan desain grafis profesional.</li>
                    <li><strong>PC Games:</strong> Berbagai genre game seru yang siap dimainkan dengan panduan instalasi step-by-step.</li>
                    <li><strong>Sistem Operasi & Tools:</strong> Installer Windows terpercaya, file image ISO, aktivator teruji, dan utilitas penting lainnya.</li>
                </ul>

                <p>Jika Anda memiliki pertanyaan, saran, atau permintaan software tertentu, jangan ragu untuk menghubungi kami melalui halaman <a href="{{ route('blog.page', 'kontak') }}">Kontak</a>.</p>

            @elseif($slug === 'kontak')
                <p>Ada kendala terkait link unduhan yang rusak, permintaan software baru, atau penawaran kerjasama periklanan? Silakan hubungi kami melalui saluran berikut:</p>

                <div class="download-box" style="margin:20px 0;">
                    <h3 style="font-size:16px; margin-bottom:12px; color:#1e293b;"><i class="fa-solid fa-envelope" style="color:var(--primary-color);"></i> Kontak Resmi AngananSoft</h3>
                    <ul style="list-style:none; margin-left:0; line-height:2.2;">
                        <li><strong>Email:</strong> <a href="mailto:{{ \App\Models\Setting::get('contact_email', 'contact@anganansoft.com') }}">{{ \App\Models\Setting::get('contact_email', 'contact@anganansoft.com') }}</a></li>
                        <li><strong>Telegram:</strong> <a href="{{ \App\Models\Setting::get('telegram_url', '#') }}" target="_blank">Channel Resmi AngananSoft</a></li>
                        <li><strong>Facebook:</strong> <a href="{{ \App\Models\Setting::get('facebook_url', '#') }}" target="_blank">AngananSoft Fanpage</a></li>
                    </ul>
                </div>

                <p>Kami akan berusaha merespons setiap pesan dan masukan Anda sesegera mungkin.</p>

            @elseif($slug === 'kebijakan-privasi')
                <p>Di <strong>AngananSoft</strong>, privasi pengunjung kami adalah salah satu prioritas utama kami. Dokumen Kebijakan Privasi ini berisi jenis informasi yang dikumpulkan dan dicatat oleh AngananSoft serta bagaimana kami menggunakannya.</p>

                <h3>File Log</h3>
                <p>AngananSoft mengikuti prosedur standar dalam menggunakan file log. File-file ini mencatat pengunjung saat mereka mengunjungi situs web. Informasi yang dikumpulkan mencakup alamat protokol internet (IP), jenis browser, Penyedia Layanan Internet (ISP), tanggal dan waktu, halaman perujuk/keluar, dan kemungkinan jumlah klik.</p>

                <h3>Cookies</h3>
                <p>Seperti situs web lainnya, AngananSoft menggunakan 'cookies'. Cookies ini digunakan untuk menyimpan informasi termasuk preferensi pengunjung dan halaman-halaman di situs web yang diakses atau dikunjungi pengunjung untuk mengoptimalkan pengalaman pengguna.</p>

            @else
                <p>Semua informasi dan file yang dibagikan di situs web <strong>AngananSoft</strong> ini diterbitkan dengan itikad baik dan hanya untuk tujuan edukasi serta referensi umum.</p>
                <p>AngananSoft tidak bertanggung jawab atas kerugian atau kerusakan sehubungan dengan penggunaan materi yang ada di blog ini. Kami sangat menyarankan pengguna untuk membeli lisensi resmi dari developer terkait jika merasa terbantu dengan software tersebut.</p>
            @endif
        </div>
    </article>

@endsection
