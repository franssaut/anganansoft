<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class BlogAndAdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Category $category;

    protected Post $post;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name' => 'AngananSoft Admin',
            'email' => 'admin@anganansoft.com',
            'password' => Hash::make('frans2341'),
        ]);

        $this->category = Category::create([
            'name' => 'Game',
            'slug' => 'game',
            'description' => 'Game PC Full Version',
            'color' => '#e91e63',
        ]);

        $this->post = Post::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Samurai Warrior 2 [PC]',
            'slug' => 'samurai-warrior-2-pc',
            'excerpt' => 'Download Samurai Warrior 2 untuk PC full version rip gratis.',
            'content' => '<p>Game hack and slash legendaris.</p>',
            'software_version' => 'v1.0',
            'file_size' => '1.2 GB',
            'license_type' => 'Full Version',
            'os_support' => 'Windows 10 / 11',
            'password_archive' => 'anganansoft',
            'download_links' => [
                ['name' => 'Google Drive', 'url' => 'https://drive.google.com/test'],
            ],
            'system_requirements' => 'RAM 1 GB',
            'installation_guide' => 'Ekstrak dan mainkan',
            'views' => 100,
            'is_featured' => true,
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function test_homepage_loads_successfully_with_posts_and_title(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('AngananSoft');
        $response->assertSee('Samurai Warrior 2 [PC]');
        $response->assertSee('Game');
    }

    public function test_search_functionality(): void
    {
        $response = $this->get('/?q=Samurai');

        $response->assertStatus(200);
        $response->assertSee('Samurai Warrior 2 [PC]');
    }

    public function test_single_post_detail_page_loads_with_specs_and_download_box(): void
    {
        $response = $this->get('/post/'.$this->post->slug);

        $response->assertStatus(200);
        $response->assertSee('Samurai Warrior 2 [PC]');
        $response->assertSee('Informasi & Link Download', false);
        $response->assertSee('1.2 GB');
        $response->assertSee('anganansoft');
        $response->assertSee('Google Drive');
    }

    public function test_category_page_loads_successfully(): void
    {
        $response = $this->get('/category/'.$this->category->slug);

        $response->assertStatus(200);
        $response->assertSee('Samurai Warrior 2 [PC]');
    }

    public function test_submitting_comment_stores_and_displays(): void
    {
        $response = $this->post('/post/'.$this->post->slug.'/comment', [
            'author_name' => 'Gamer Sejati',
            'author_email' => 'gamer@example.com',
            'content' => 'Linknya lancar banget min, terima kasih banyak!',
        ]);

        $response->assertRedirect('/post/'.$this->post->slug.'#comments');
        $this->assertDatabaseHas('comments', [
            'author_name' => 'Gamer Sejati',
            'content' => 'Linknya lancar banget min, terima kasih banyak!',
        ]);
    }

    public function test_admin_guest_redirected_to_login(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_admin_can_login_successfully(): void
    {
        $response = $this->post('/admin/login', [
            'email' => 'admin@anganansoft.com',
            'password' => 'frans2341',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($this->admin);
    }

    public function test_admin_can_create_new_post(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/posts', [
            'title' => 'Internet Download Manager 6.42 Full Crack',
            'slug' => 'idm-6-42-full-crack',
            'category_id' => $this->category->id,
            'excerpt' => 'IDM versi terbaru full crack.',
            'content' => '<p>Ulasan IDM terbaru dengan kecepatan tinggi.</p>',
            'software_version' => 'v6.42 Build 3',
            'file_size' => '15 MB',
            'license_type' => 'Full Patch',
            'os_support' => 'Windows 11 / 10',
            'password_archive' => 'anganansoft',
            'download_names' => ['Google Drive', 'Mega'],
            'download_urls' => ['https://drive.google.com/idm', 'https://mega.nz/idm'],
            'system_requirements' => 'RAM 512 MB',
            'installation_guide' => 'Jalankan patch as admin',
            'status' => 'published',
            'tags' => 'IDM, Crack, Utilities',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseHas('posts', [
            'slug' => 'idm-6-42-full-crack',
            'software_version' => 'v6.42 Build 3',
        ]);
    }

    public function test_admin_can_edit_post(): void
    {
        $response = $this->actingAs($this->admin)->put('/admin/posts/'.$this->post->id, [
            'title' => 'Samurai Warrior 2 [PC] [UPDATED]',
            'slug' => $this->post->slug,
            'category_id' => $this->category->id,
            'excerpt' => 'Updated version excerpt',
            'content' => '<p>Konten yang diperbarui</p>',
            'software_version' => 'v1.1 Patch',
            'file_size' => '1.2 GB',
            'license_type' => 'Full Version',
            'os_support' => 'Windows 10 / 11',
            'password_archive' => 'anganansoft',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseHas('posts', [
            'id' => $this->post->id,
            'title' => 'Samurai Warrior 2 [PC] [UPDATED]',
            'software_version' => 'v1.1 Patch',
        ]);
    }

    public function test_admin_can_delete_post(): void
    {
        $response = $this->actingAs($this->admin)->delete('/admin/posts/'.$this->post->id);

        $response->assertRedirect(route('admin.posts.index'));
        $this->assertDatabaseMissing('posts', ['id' => $this->post->id]);
    }

    public function test_admin_can_create_category(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/categories', [
            'name' => 'Antivirus',
            'slug' => 'antivirus',
            'color' => '#10b981',
            'description' => 'Software antivirus terbaik',
        ]);

        $response->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['slug' => 'antivirus']);
    }

    public function test_admin_can_update_settings(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/settings', [
            'site_name' => 'AngananSoft Pro',
            'site_tagline' => 'Pusat Download Software PC No 1',
            'archive_password' => '12345',
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        $this->assertEquals('AngananSoft Pro', Setting::get('site_name'));
        $this->assertEquals('12345', Setting::get('archive_password'));
    }

    public function test_security_headers_are_present(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_comment_honeypot_silently_ignores_spam_bots(): void
    {
        $response = $this->post('/post/'.$this->post->slug.'/comment', [
            'author_name' => 'Spam Bot',
            'author_email' => 'spambot@example.com',
            'content' => 'Visit my phishing site now!',
            'website_url_hp' => 'http://spam-link.com', // Filled honeypot
        ]);

        $response->assertRedirect('/post/'.$this->post->slug.'#comments');
        $this->assertDatabaseMissing('comments', [
            'author_name' => 'Spam Bot',
        ]);
    }

    public function test_post_content_is_sanitized_against_xss(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/posts', [
            'title' => 'XSS Test Software',
            'slug' => 'xss-test-software',
            'category_id' => $this->category->id,
            'content' => '<p>Deskripsi aman</p><script>alert("hacked")</script><iframe src="malicious.html"></iframe>',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $created = Post::where('slug', 'xss-test-software')->first();
        $this->assertNotNull($created);
        $this->assertStringNotContainsString('<script>', $created->content);
        $this->assertStringNotContainsString('alert("hacked")', $created->content);
        $this->assertStringNotContainsString('<iframe', $created->content);
    }

    public function test_admin_can_update_password_securely(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/settings/password', [
            'current_password' => 'frans2341',
            'password' => 'newSecretPass123',
            'password_confirmation' => 'newSecretPass123',
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        $this->assertTrue(Hash::check('newSecretPass123', $this->admin->fresh()->password));
    }

    public function test_admin_can_create_post_with_uploaded_image(): void
    {
        $fakeImage = UploadedFile::fake()->image('thumbnail.jpg', 600, 400);

        $response = $this->actingAs($this->admin)->post('/admin/posts', [
            'title' => 'Post with Uploaded Image',
            'slug' => 'post-with-uploaded-image',
            'category_id' => $this->category->id,
            'content' => '<p>Artikel dengan gambar terunggah.</p>',
            'featured_image_file' => $fakeImage,
            'status' => 'published',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $created = Post::where('slug', 'post-with-uploaded-image')->first();
        $this->assertNotNull($created);
        $this->assertStringStartsWith('data:image/', $created->featured_image);
        $this->assertStringStartsWith('data:image/', $created->image_url);
    }

    public function test_admin_can_create_post_with_image_url_and_inline_data_image(): void
    {
        $inlineImage = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==';

        $response = $this->actingAs($this->admin)->post('/admin/posts', [
            'title' => 'Post with Image URL and Inline Screenshot',
            'slug' => 'post-with-image-url-and-inline-screenshot',
            'category_id' => $this->category->id,
            'content' => '<p>Gambar screenshot berikut: <img src="'.$inlineImage.'" alt="Test Image"></p>',
            'featured_image_url' => 'https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800',
            'status' => 'published',
        ]);

        $response->assertRedirect(route('admin.posts.index'));
        $created = Post::where('slug', 'post-with-image-url-and-inline-screenshot')->first();
        $this->assertNotNull($created);
        $this->assertEquals('https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=800', $created->featured_image);
        $this->assertStringContainsString('data:image/png;base64,', $created->content);
        $this->assertStringContainsString('<img src="data:image/png;base64,', $created->content);
    }
}
