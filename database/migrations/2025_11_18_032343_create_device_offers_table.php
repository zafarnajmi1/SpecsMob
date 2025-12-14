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
        Schema::create('device_offers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('device_variant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained()->cascadeOnDelete();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();

            $table->string('url')->nullable();         // affiliate link
            $table->decimal('price', 12, 2);           // 1419.00
            $table->boolean('in_stock')->default(true);
            $table->boolean('is_featured')->default(false); // for small box under specs
            $table->boolean('status')->default(true);

            $table->timestamps();

            $table->index(['device_variant_id', 'country_id', 'store_id']);
            $table->index('country_id');
            $table->index('currency_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_offers');
    }
};
