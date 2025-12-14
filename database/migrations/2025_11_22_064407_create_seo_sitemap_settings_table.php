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
        Schema::create('seo_sitemap_settings', function (Blueprint $table) {
            $table->id();

            $table->string('sitemap_url')->nullable();

            // Full robots.txt content
            $table->longText('robots_content')->nullable();

            // Hreflang base URLs
            $table->string('hreflang_en')->nullable();
            $table->string('hreflang_en_pk')->nullable();
            $table->string('hreflang_en_in')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo_sitemap_settings');
    }
};
