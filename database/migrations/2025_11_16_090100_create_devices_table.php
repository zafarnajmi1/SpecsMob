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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();

            // Each device belongs to a brand and a device type (Phone / Tablet / Watch / etc.)
            $table->foreignId('brand_id')->constrained('brands')->cascadeOnDelete();
            $table->foreignId('device_type_id')->constrained('device_types')->cascadeOnDelete();

            $table->string('name');            // Apple iPhone 17 Pro Max
            $table->string('slug')->unique();  // apple_iphone_17_pro_max
            $table->text('description')->nullable();
            
            $table->date('announcement_date')->nullable();
            $table->string('release_status')
                ->nullable()
                ->comment("Allowed values: rumored, announced, released, discontinued");
            $table->date('released_at')->nullable();      // 2025-09-19

            // Header summary fields
            $table->string('os_short')->nullable();          // 'iOS 26, up to iOS 26.1'
            $table->string('chipset_short')->nullable();     // 'Apple A19 Pro'
            $table->string('storage_short')->nullable();     // '256GB/512GB/2TB, no card slot'
            $table->string('main_camera_short')->nullable(); // '48 MP'
            $table->string('ram_short')->nullable();         // '12GB RAM'
            $table->string('battery_short')->nullable();     // '4823 mAh, PD3.2 25W'
            $table->string('color_short')->nullable();         
            
            $table->decimal('weight_grams', 6, 2)->nullable(); // 233.00
            $table->string('dimensions')->nullable();          // '163.4 x 78 x 8.8 mm'

            $table->string('thumbnail_url')->nullable();   // list image

            $table->boolean('allow_opinions')->default(true);
            $table->boolean('allow_fans')->default(true);
            $table->boolean('is_published')->default(false);

            $table->timestamps();

            $table->index('brand_id');
            $table->index('device_type_id');
            $table->index('is_published');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
