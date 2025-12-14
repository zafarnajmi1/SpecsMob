<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();

            // Basic content
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('body');  // full HTML/content
            $table->string('thumbnail_url')->nullable();  // list image path

            // Type: news, article (blog), featured
            $table
                ->enum('type', ['news', 'article', 'featured'])
                ->index();

            // Optional relations to brand & device
            $table
                ->foreignId('brand_id')
                ->nullable()
                ->constrained('brands')
                ->nullOnDelete();

            $table
                ->foreignId('device_id')
                ->nullable()
                ->constrained('devices')
                ->nullOnDelete();

            // Author & publishing
            $table
                ->foreignId('author_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Status & visibility
            $table->boolean('is_published')->default(false);
            $table->boolean('is_featured')->default(false);  // For featured page highlights
            $table->boolean('allow_comments')->default(true);
            $table->timestamp('published_at')->nullable();

            // Metrics
            $table->unsignedBigInteger('views_count')->default(0);
            $table->unsignedInteger('comments_count')->default(0);
            $table->unsignedInteger('share_count')->default(0);

            $table->timestamps();

            // Helpful indexes
            $table->index(['type', 'is_published', 'published_at']);
            $table->index(['brand_id', 'device_id', 'author_id']);
            $table->index('is_featured');
            $table->index('views_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
