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
        Schema::create('device_offer_price_histories', function (Blueprint $table) {
            $table->id();
        $table->foreignId('device_offer_id')->constrained('device_offers')->cascadeOnDelete();

        $table->decimal('price', 12, 2);
        $table->timestamp('recorded_at');

        $table->timestamps();

        $table->index(['device_offer_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_offer_price_histories');
    }
};
