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
        Schema::create('tags', function (Blueprint $table) {
            $table->id();

            $table->string('name');  // Featured, Android, Samsung, Nokia, etc.
            $table->string('slug')->unique();
            $table->string('type')->default('article');  // article, device, brand
            $table->boolean('is_popular')->default(false);  // For featured page popular tags
            $table->timestamps();

            $table->index(['type']);
            $table->index('is_popular');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
