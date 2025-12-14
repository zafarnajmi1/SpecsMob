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
        Schema::create('device_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();

            $table->unsignedBigInteger('total_hits')->default(0);
            $table->unsignedBigInteger('total_fans')->default(0);

            // Better than float for consistent % display
            $table->decimal('popularity_score', 5, 2)->default(0); // e.g., 43.25

            $table->timestamp('last_hit_at')->nullable();
            $table->timestamp('last_fan_at')->nullable();

            $table->timestamps();

            $table->unique('device_id');          // 1 stats row per device
            $table->index('total_hits');
            $table->index('popularity_score');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_stats');
    }
};
