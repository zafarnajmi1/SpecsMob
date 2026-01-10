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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->foreignId('device_id')->nullable()->constrained()->nullOnDelete();
            $table->string('link'); // External affiliate link
            $table->string('image_url')->nullable();

            // Price info
            $table->string('price'); // e.g. "$ 299.99"
            $table->string('original_price')->nullable(); // e.g. "$ 350.00"
            $table->string('discount_percent')->nullable(); // e.g. "14%"

            // Store info
            $table->string('store_name')->nullable();
            $table->string('store_logo')->nullable();

            // Filters
            $table->string('region')->default('International'); // United States, United Kingdom, etc.

            // Details
            $table->string('memory')->nullable(); // e.g. "128GB 8GB RAM"
            $table->text('description')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
