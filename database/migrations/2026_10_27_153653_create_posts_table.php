<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->longText('featured_image')->nullable();
            $table->string('software_version')->nullable();
            $table->string('file_size')->nullable();
            $table->string('license_type')->nullable()->default('Full Version');
            $table->string('os_support')->nullable()->default('Windows 11 / 10 / 8 / 7');
            $table->string('password_archive')->nullable()->default('anganansoft');
            $table->json('download_links')->nullable();
            $table->text('system_requirements')->nullable();
            $table->text('installation_guide')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('published');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
