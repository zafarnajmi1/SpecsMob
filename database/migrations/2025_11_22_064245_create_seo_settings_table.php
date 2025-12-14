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
        Schema::create('seo_settings', function (Blueprint $table) {
            $table->id();

            $table->string('site_name')->nullable();

            $table->string('default_meta_title')->nullable();
            $table->string('default_meta_description')->nullable();
            $table->string('default_meta_keywords')->nullable();

            $table->string('robots_default')->default('index,follow');
            $table->string('canonical_base_url')->nullable();

            // OG defaults
            $table->string('og_site_name')->nullable();
            $table->string('og_default_title')->nullable();
            $table->string('og_default_description')->nullable();
            $table->string('og_default_image')->nullable();

            // Twitter defaults
            $table->string('twitter_default_title')->nullable();
            $table->string('twitter_default_description')->nullable();
            $table->string('twitter_default_image')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_settings');
    }
};
