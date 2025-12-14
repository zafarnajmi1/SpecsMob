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
        Schema::create('device_opinions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('device_opinions')
                ->nullOnDelete(); // replies

            $table->string('title')->nullable();  // optional subject
            $table->text('body');                // opinion text
            $table->unsignedTinyInteger('rating')->nullable(); // 1–10 or 1–5
            $table->boolean('is_approved')->default(true);
            $table->unsignedInteger('likes_count')->default(0);

            $table->timestamps();

            $table->index('device_id');
            $table->index('user_id');
            $table->index('parent_id');
            $table->index('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_opinions');
    }
};
