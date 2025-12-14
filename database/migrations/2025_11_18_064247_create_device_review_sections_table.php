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
        Schema::create('device_review_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_review_id')->constrained()->cascadeOnDelete();

            $table->string('title');           // "Introduction, specs, unboxing", "Camera", ...
            $table->string('slug');            // introduction, camera, software...
            $table->unsignedInteger('order')->default(0);
            $table->longText('body');          // HTML from editor

            $table->timestamps();

            $table->index('device_review_id');
            $table->unique(['device_review_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_review_sections');
    }
};
