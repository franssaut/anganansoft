<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedMeta();
        $this->seedPosts(0, 10);
    }

    public function seedMeta(): void
    {
        // 1. Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@anganansoft.com'],
            [
                'name' => 'AngananSoft Admin',
                'password' => Hash::make('frans2341'),
            ]
        );
        $admin->update(['password' => Hash::make('frans2341')]);

        // 2. Settings
        $settings = [
            'site_name' => 'AngananSoft',
            'site_tagline' => 'Download software multimedia, tools, game, windows, IDM untuk PC|Android|Iphone. Full Version|Premium.',
            'site_description' => 'Download software multimedia, tools, game, windows, IDM untuk PC|Komputer Full Version gratis dan terpercaya.',
            'site_keywords' => 'Download Software PC Komputer, Game, android, IPhone Full Version, Crack, Patch, Serial Number',
            'contact_email' => 'contact@anganansoft.com',
            'facebook_url' => 'https://facebook.com/anganansoft',
            'twitter_url' => 'https://twitter.com/anganansoft',
            'telegram_url' => 'https://t.me/anganansoft',
            'archive_password' => 'anganansoft',
            'footer_about' => 'AngananSoft adalah blog penyedia download gratis software multimedia, tools utilities, PC games, sistem operasi Windows, dan tutorial seputar komputer terlengkap dengan lisensi full version.',
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }

        // 3. Categories
        $categoriesData = [
            ['name' => 'Game', 'slug' => 'game', 'color' => '#e91e63', 'description' => 'Koleksi PC Game full version, rip, repack, dan highly compressed.'],
            ['name' => 'Software', 'slug' => 'software', 'color' => '#26c6da', 'description' => 'Software utilitas, office, antivirus, dan tools produktivitas.'],
            ['name' => 'Multimedia', 'slug' => 'multimedia', 'color' => '#ff9800', 'description' => 'Aplikasi editing audio, video, gambar, dan grafis desain.'],
            ['name' => 'Windows', 'slug' => 'windows', 'color' => '#2196f3', 'description' => 'Sistem operasi Windows, aktivator, driver, dan tools ISO.'],
            ['name' => 'Android', 'slug' => 'android', 'color' => '#4caf50', 'description' => 'Aplikasi dan game Android APK premium mod gratis.'],
            ['name' => 'Tutorial', 'slug' => 'tutorial', 'color' => '#9c27b0', 'description' => 'Panduan dan tutorial seputar komputer, instalasi, dan troubleshooting.'],
        ];

        foreach ($categoriesData as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // 4. Tags
        $tagsData = ['PC Games', 'Full Version', 'Crack', 'Patch', 'Serial Number', 'Video Editing', 'Adobe', 'Statistik', 'ISO Tools', 'Utilities', 'Graphic Design', 'Multimedia'];
        foreach ($tagsData as $tagName) {
            Tag::firstOrCreate(
                ['slug' => Str::slug($tagName)],
                ['name' => $tagName, 'slug' => Str::slug($tagName)]
            );
        }
    }

    public function seedPosts(int $offset = 0, int $limit = 10): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@anganansoft.com'],
            ['name' => 'AngananSoft Admin', 'password' => Hash::make('frans2341')]
        );

        $categories = Category::all()->keyBy('slug');
        $tags = Tag::all()->keyBy('name');

        // 5. Seed Real Posts from AngananSoft
        $postsData = [
            [
                'title' => 'Samurai Warrior 2 [PC]',
                'slug' => 'samurai-warrior-2-pc',
                'category_slug' => 'game',
                'featured_image' => 'https://images.unsplash.com/photo-1579373903781-fd5c0c30c4cd?w=800&q=80',
                'software_version' => 'v1.0 Rip',
                'file_size' => '1.2 GB',
                'license_type' => 'Full Version Game',
                'os_support' => 'Windows 11 / 10 / 8.1 / 7 (32-bit & 64-bit)',
                'password_archive' => 'anganansoft',
                'views' => 12540,
                'is_featured' => true,
                'created_at' => now()->subDays(15),
                'excerpt' => 'Download Samurai Warrior 2 untuk PC full version rip gratis highly compressed. Rasakan aksi pertempuran samurai legendaris di era Sengoku Jepang.',
                'content' => '<p><strong>Samurai Warriors 2 (Sengoku Musou 2)</strong> adalah permainan video hack and slash aksi yang dikembangkan oleh Omega Force dan dipublikasikan oleh Koei. Game ini merupakan sekuel langsung dari Samurai Warriors pertama yang sangat populer di platform PlayStation 2 dan PC.</p>
<h3>Gameplay dan Fitur Utama:</h3>
<p>Dalam Samurai Warrior 2, pemain dapat memilih berbagai karakter jenderal dan pendekar samurai ternama seperti Sanada Yukimura, Oda Nobunaga, Honda Tadakatsu, dan Miyamoto Musashi. Setiap karakter memiliki jurus spesial "Special Ability" dan "Musou Attack" dengan efek visual yang spektakuler.</p>
<ul>
<li>Lebih dari 26 karakter playable dengan alur cerita unik (Story Mode).</li>
<li>Survival Mode menantang dengan menara tingkat tak terbatas.</li>
<li>Sugoroku mini-game papan strategi yang seru dimainkan bersama.</li>
<li>Grafis 3D ringan yang berjalan lancar di spesifikasi laptop maupun PC standar.</li>
</ul>',
                'system_requirements' => "OS: Windows XP / Vista / 7 / 8 / 10 / 11\nProcessor: Pentium 4 1.6 GHz atau lebih tinggi\nRAM: 512 MB (Rekomendasi 1 GB+)\nGraphics: 64 MB DirectX 9.0c compatible video card\nStorage: 3 GB free hard drive space\nSound: DirectX 9.0c compatible sound card",
                'installation_guide' => "1. Ekstrak file arsip yang telah diunduh menggunakan WinRAR atau 7-Zip.\n2. Buka folder hasil ekstrak lalu jalankan file 'Setup.exe' atau 'SW2.exe'.\n3. Ikuti petunjuk instalasi sampai selesai.\n4. Jika diperlukan crack, copy semua file di dalam folder Crack ke direktori instalasi game.\n5. Jalankan game sebagai Administrator. Selamat bermain!",
                'download_links' => [
                    ['name' => 'Google Drive (High Speed)', 'url' => 'https://drive.google.com/'],
                    ['name' => 'Mega.nz', 'url' => 'https://mega.nz/'],
                    ['name' => 'MediaFire Server 1', 'url' => 'https://mediafire.com/'],
                ],
                'tags' => ['PC Games', 'Full Version'],
            ],
            [
                'title' => 'Internet Download Manager 6.35 build18 crack+patch',
                'slug' => 'internet-download-manager-6-35-build18-crack-patch',
                'category_slug' => 'software',
                'featured_image' => 'https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=800&q=80',
                'software_version' => 'v6.35 Build 18',
                'file_size' => '14.8 MB',
                'license_type' => 'Full Version (Crack + Patch)',
                'os_support' => 'Windows 11 / 10 / 8.1 / 8 / 7 (32-bit & 64-bit)',
                'password_archive' => 'anganansoft',
                'views' => 28910,
                'is_featured' => true,
                'created_at' => now()->subDays(12),
                'excerpt' => 'Download Internet Download Manager 6.35 build 18 full version crack and patch gratis terbaru. Tingkatkan kecepatan download file hingga 5 kali lipat.',
                'content' => '<p><strong>Internet Download Manager (IDM) 6.35 build 18</strong> merupakan software download manager terbaik di dunia yang dirancang untuk mempercepat proses download hingga 5x lipat, memulihkan unduhan yang terputus, dan menjadwalkan download otomatis.</p>
<h3>Kelebihan IDM 6.35 Build 18:</h3>
<ul>
<li>Dukungan penuh untuk browser Google Chrome, Mozilla Firefox, Microsoft Edge, dan Opera terbaru.</li>
<li>Teknologi segmentasi file dinamis cerdas tanpa perlu koneksi ulang.</li>
<li>Download video otomatis dari YouTube, Facebook, Vimeo dengan satu klik.</li>
<li>Bebas dari pop-up "Fake Serial Number" berkat patch terbaru yang disertakan.</li>
</ul>',
                'system_requirements' => "OS: Windows XP / Vista / 7 / 8 / 8.1 / 10 / 11\nProcessor: Intel Pentium 4 atau setara\nRAM: 512 MB RAM\nStorage: 50 MB ruang kosong hardisk",
                'installation_guide' => "1. Matikan koneksi internet dan antivirus sementara.\n2. Hapus versi IDM lama secara bersih (Clean Uninstall jika ada).\n3. Install 'idman635build18.exe' hingga selesai, lalu tutup IDM (pastikan tidak berjalan di taskbar icon).\n4. Buka folder Patch, jalankan Patch.exe dengan Klik Kanan -> Run as Administrator.\n5. Klik tombol 'Patch' atau 'Crack', masukkan nama bebas jika diminta.\n6. Selesai! IDM sudah full version permanen.",
                'download_links' => [
                    ['name' => 'Google Drive', 'url' => 'https://drive.google.com/'],
                    ['name' => 'MediaFire Direct', 'url' => 'https://mediafire.com/'],
                    ['name' => 'Zippyshare Mirror', 'url' => 'https://zippyshare.com/'],
                ],
                'tags' => ['Full Version', 'Crack', 'Patch', 'Utilities'],
            ],
            [
                'title' => 'Plague Inc evolved [PC]',
                'slug' => 'plague-inc-evolved-pc',
                'category_slug' => 'game',
                'featured_image' => 'https://images.unsplash.com/photo-1584483766114-2cea6facdf57?w=800&q=80',
                'software_version' => 'v1.16.6 Full DLC',
                'file_size' => '840 MB',
                'license_type' => 'Full Version Standalone',
                'os_support' => 'Windows 11 / 10 / 8 / 7',
                'password_archive' => 'anganansoft',
                'views' => 9450,
                'is_featured' => false,
                'created_at' => now()->subDays(10),
                'excerpt' => 'Download Plague Inc Evolved PC Full Version gratis. Game strategi simulasi unik di mana Anda mengembangkan patogen untuk menginfeksi dan menguji respon dunia.',
                'content' => '<p><strong>Plague Inc: Evolved</strong> memadukan gameplay strategi tinggi dan simulasi ilmiah yang realistis. Patogen Anda baru saja menginfeksi Pasien Nol. Sekarang Anda harus mengakhiri sejarah manusia dengan mengembangkan wabah mematikan sambil beradaptasi melawan pertahanan umat manusia.</p>
<p>Edisi PC ini mencakup grafis 3D resolusi tinggi, statistik mendalam, skenario buatan komunitas, dan mode multiplayer kompetitif maupun kooperatif.</p>',
                'system_requirements' => "OS: Windows 8 / 10 / 11 (64-bit)\nProcessor: 2.0 GHz Dual Core\nRAM: 2 GB RAM\nGraphics: Integrated Graphics (512MB)\nStorage: 1 GB available space",
                'installation_guide' => "1. Download file game dari link di bawah.\n2. Ekstrak file .rar dengan password: anganansoft\n3. Buka folder hasil ekstrak, jalankan 'PlagueIncEvolved.exe'\n4. Mainkan langsung tanpa perlu instalasi rumit (Portable version).",
                'download_links' => [
                    ['name' => 'Google Drive Link', 'url' => 'https://drive.google.com/'],
                    ['name' => 'Mega Server', 'url' => 'https://mega.nz/'],
                ],
                'tags' => ['PC Games', 'Full Version'],
            ],
            [
                'title' => 'Adobe Illustrator CC 2019 v23.0.1',
                'slug' => 'adobe-illustrator-cc-2019-v23-0-1',
                'category_slug' => 'multimedia',
                'featured_image' => 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=800&q=80',
                'software_version' => 'CC 2019 (v23.0.1.540)',
                'file_size' => '1.85 GB',
                'license_type' => 'Pre-Activated (Repack)',
                'os_support' => 'Windows 10 / 11 (Hanya 64-bit)',
                'password_archive' => 'anganansoft',
                'views' => 18400,
                'is_featured' => true,
                'created_at' => now()->subDays(8),
                'excerpt' => 'Download Adobe Illustrator CC 2019 full version pre-activated untuk PC Windows 64-bit. Buat desain vektor, logo, ilustrasi, dan tipografi dengan kualitas profesional.',
                'content' => '<p><strong>Adobe Illustrator CC 2019</strong> adalah software desain grafis vektor standar industri yang memungkinkan Anda membuat logo, ikon, gambar, tipografi, dan ilustrasi kompleks untuk cetak, web, video, dan mobile.</p>
<h3>Fitur Baru Illustrator CC 2019:</h3>
<ul>
<li>Freeform Gradients: Pewarnaan gradien organik baru dengan kontrol titik yang sangat fleksibel.</li>
<li>Global Edit: Mengubah semua objek serupa secara bersamaan di seluruh artboard.</li>
<li>Visual Font Browsing: Melihat pratinjau font secara langsung di kanvas kerja.</li>
<li>Toolbar yang Dapat Disesuaikan: Menambah atau menghapus tool sesuai preferensi alur kerja Anda.</li>
</ul>',
                'system_requirements' => "OS: Microsoft Windows 10 (64-bit) Version 1809 atau lebih baru\nProcessor: Multicore Intel processor with 64-bit support\nRAM: 8 GB RAM (Direkomendasikan 16 GB)\nStorage: 4 GB ruang hard disk kosong untuk instalasi\nResolution: Layar 1024 x 768 display (1920 x 1080 direkomendasikan)",
                'installation_guide' => "1. Matikan koneksi internet Anda terlebih dahulu.\n2. Ekstrak file ISO atau part RAR.\n3. Jalankan 'Set-up.exe' di folder instalasi.\n4. Tunggu hingga proses instalasi mencapai 100%.\n5. Software sudah pre-activated, langsung bisa digunakan tanpa perlu patch tambahan!",
                'download_links' => [
                    ['name' => 'Google Drive Part 1', 'url' => 'https://drive.google.com/'],
                    ['name' => 'Google Drive Part 2', 'url' => 'https://drive.google.com/'],
                    ['name' => 'Mega Server Mirror', 'url' => 'https://mega.nz/'],
                ],
                'tags' => ['Adobe', 'Full Version', 'Graphic Design'],
            ],
            [
                'title' => 'SPSS 20 full version',
                'slug' => 'spss-20-full-version',
                'category_slug' => 'software',
                'featured_image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800&q=80',
                'software_version' => 'v20.0 (32-bit & 64-bit)',
                'file_size' => '910 MB',
                'license_type' => 'Full Version + Serial Crack',
                'os_support' => 'Windows XP / 7 / 8 / 10 / 11',
                'password_archive' => 'anganansoft',
                'views' => 15200,
                'is_featured' => false,
                'created_at' => now()->subDays(6),
                'excerpt' => 'Download IBM SPSS Statistics 20 full version gratis untuk PC. Aplikasi terpercaya untuk analisis data statistik, uji hipotesis, skripsi, dan riset akademik.',
                'content' => '<p><strong>IBM SPSS Statistics 20</strong> adalah perangkat lunak analisis data komprehensif yang dirancang untuk memecahkan masalah bisnis dan penelitian. Software ini sangat banyak digunakan oleh mahasiswa, dosen, dan peneliti untuk mengolah data kuesioner, regresi linier, uji validitas, dan reliabilitas.</p>',
                'system_requirements' => "OS: Windows 7 / 8 / 10 / 11 (32-bit & 64-bit)\nProcessor: 1 GHz atau lebih cepat\nRAM: 1 GB RAM\nStorage: 1.5 GB hard disk space",
                'installation_guide' => "1. Jalankan file setup SPSS 20.\n2. Pilih 'Single user license'.\n3. Saat diminta serial number atau lisensi, pilih 'License my product now'.\n4. Buka folder Crack, salin file 'lservrc' lalu paste dan timpa (overwrite) ke direktori instalasi C:\\Program Files\\IBM\\SPSS\\Statistics\\20.\n5. Buka SPSS 20, program kini aktif penuh.",
                'download_links' => [
                    ['name' => 'Google Drive Link', 'url' => 'https://drive.google.com/'],
                    ['name' => 'MediaFire Link', 'url' => 'https://mediafire.com/'],
                ],
                'tags' => ['Full Version', 'Statistik', 'Utilities'],
            ],
            [
                'title' => 'Vegas Pro 14',
                'slug' => 'vegas-pro-14',
                'category_slug' => 'multimedia',
                'featured_image' => 'https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=800&q=80',
                'software_version' => 'v14.0 Build 270',
                'file_size' => '440 MB',
                'license_type' => 'Full Version Patch',
                'os_support' => 'Windows 7 / 8 / 10 / 11 (64-bit)',
                'password_archive' => 'anganansoft',
                'views' => 11200,
                'is_featured' => false,
                'created_at' => now()->subDays(4),
                'excerpt' => 'Download MAGIX Vegas Pro 14 Full Patch gratis. Software video editing profesional untuk YouTuber, videografer, dan kreator konten digital.',
                'content' => '<p><strong>MAGIX Vegas Pro 14</strong> hadir dengan stabilitas dan fitur penyuntingan video canggih seperti dukungan resolusi 4K Ultra HD, stabilisasi gambar otomatis (Smart Zoom), audio multi-channel, dan akselerasi GPU rendering.</p>',
                'system_requirements' => "OS: 64-bit Windows 7, 8, 10, 11\nProcessor: 2 GHz multiprocessor\nRAM: 4 GB RAM (8 GB direkomendasikan)\nStorage: 1 GB hard-disk space untuk program installation\nGraphics: NVIDIA, AMD/ATI, atau Intel GPU dengan minimal 512 MB memory",
                'installation_guide' => "1. Install MAGIX Vegas Pro 14 sampai tuntas.\n2. Jangan jalankan program setelah instalasi selesai.\n3. Salin file Patch ke folder instalasi program Vegas Pro 14.\n4. Jalankan Patch sebagai Administrator lalu klik Patch.\n5. Buka software dan siap digunakan.",
                'download_links' => [
                    ['name' => 'Google Drive', 'url' => 'https://drive.google.com/'],
                    ['name' => 'Mega Server', 'url' => 'https://mega.nz/'],
                ],
                'tags' => ['Video Editing', 'Full Version', 'Multimedia'],
            ],
            [
                'title' => 'Power ISO v5.6 full serial number',
                'slug' => 'power-iso-v5-6-full-serial-number',
                'category_slug' => 'windows',
                'featured_image' => 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=800&q=80',
                'software_version' => 'v5.6',
                'file_size' => '6.8 MB',
                'license_type' => 'Full Version Serial Key',
                'os_support' => 'Windows All (32-bit & 64-bit)',
                'password_archive' => 'anganansoft',
                'views' => 8700,
                'is_featured' => false,
                'created_at' => now()->subDays(2),
                'excerpt' => 'Download PowerISO v5.6 full version serial number gratis. Tools serbaguna untuk membuka, mengekstrak, membakar (burn), dan membuat file ISO CD/DVD/BD.',
                'content' => '<p><strong>PowerISO v5.6</strong> adalah alat pengolah file citra disk CD/DVD/BD yang hebat. Memungkinkan Anda membuka, mengekstrak, membuat, mengedit, mengompres, mengenkripsi, membagi, dan mengonversi file ISO, serta memasang file ISO dengan virtual drive internal.</p>',
                'system_requirements' => "OS: Windows 98 / Me / 2000 / XP / 2003 / Vista / 7 / 8 / 10 / 11\nProcessor: Intel Pentium 166MHz atau lebih baik\nRAM: 64MB memory\nStorage: Minimal 10MB free disk space",
                'installation_guide' => "1. Install PowerISO dengan menjalankan file installer.\n2. Buka PowerISO, pilih menu Register/Enter Registration Code.\n3. Buka file Serial.txt yang disertakan, salin Name dan Registration Code.\n4. Masukkan ke PowerISO lalu klik OK.\n5. Program berhasil diaktivasi selamanya.",
                'download_links' => [
                    ['name' => 'Google Drive', 'url' => 'https://drive.google.com/'],
                    ['name' => 'Zippyshare Mirror', 'url' => 'https://zippyshare.com/'],
                ],
                'tags' => ['ISO Tools', 'Serial Number', 'Full Version'],
            ],
        ];

        $slicedPosts = array_slice($postsData, $offset, $limit);

        foreach ($slicedPosts as $pData) {
            $cat = $categories->get($pData['category_slug']) ?? Category::firstOrCreate(['slug' => $pData['category_slug']], ['name' => ucfirst($pData['category_slug']), 'slug' => $pData['category_slug']]);
            $post = Post::updateOrCreate(
                ['slug' => $pData['slug']],
                [
                    'user_id' => $admin->id,
                    'category_id' => $cat->id,
                    'title' => $pData['title'],
                    'excerpt' => $pData['excerpt'],
                    'content' => $pData['content'],
                    'featured_image' => $pData['featured_image'],
                    'software_version' => $pData['software_version'],
                    'file_size' => $pData['file_size'],
                    'license_type' => $pData['license_type'],
                    'os_support' => $pData['os_support'],
                    'password_archive' => $pData['password_archive'],
                    'download_links' => $pData['download_links'],
                    'system_requirements' => $pData['system_requirements'],
                    'installation_guide' => $pData['installation_guide'],
                    'views' => $pData['views'],
                    'is_featured' => $pData['is_featured'],
                    'status' => 'published',
                    'published_at' => $pData['created_at'],
                    'created_at' => $pData['created_at'],
                    'updated_at' => $pData['created_at'],
                ]
            );

            // Attach tags
            $postTagIds = [];
            foreach ($pData['tags'] as $tName) {
                $tagModel = $tags->get($tName) ?? Tag::firstOrCreate(['slug' => Str::slug($tName)], ['name' => $tName, 'slug' => Str::slug($tName)]);
                $postTagIds[] = $tagModel->id;
            }
            $post->tags()->sync($postTagIds);

            // Add sample comments
            Comment::firstOrCreate(
                ['post_id' => $post->id, 'author_name' => 'Budi Santoso'],
                [
                    'author_email' => 'budi@example.com',
                    'content' => 'Terima kasih banyak admin, linknya sangat lancar dan work 100%! Ditunggu update selanjutnya.',
                    'status' => 'approved',
                ]
            );
        }
    }
}
