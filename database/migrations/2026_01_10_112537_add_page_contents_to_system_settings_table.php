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
        Schema::table('system_settings', function (Blueprint $table) {
            $table->string('contact_page_image')->nullable();
            $table->string('contact_page_title')->nullable();
            $table->longText('contact_page_content')->nullable();
            $table->string('tip_us_page_image')->nullable();
            $table->string('tip_us_page_title')->nullable();
            $table->longText('tip_us_page_content')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_settings', function (Blueprint $table) {
            $table->dropColumn([
                'contact_page_image',
                'contact_page_title',
                'contact_page_content',
                'tip_us_page_image',
                'tip_us_page_title',
                'tip_us_page_content'
            ]);
        });
    }
};
