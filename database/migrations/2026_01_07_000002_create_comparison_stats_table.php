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
        Schema::create('comparison_stats', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device1_id');
            $table->unsignedBigInteger('device2_id');
            $table->unsignedBigInteger('total_hits')->default(0);
            $table->timestamp('last_hit_at')->nullable();
            $table->timestamps();

            $table->foreign('device1_id')->references('id')->on('devices')->onDelete('cascade');
            $table->foreign('device2_id')->references('id')->on('devices')->onDelete('cascade');

            // Ensure unique pairs regardless of order (we'll handle sorting IDs in code)
            $table->unique(['device1_id', 'device2_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comparison_stats');
    }
};
