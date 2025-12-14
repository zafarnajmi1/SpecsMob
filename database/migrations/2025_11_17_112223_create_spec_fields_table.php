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
        Schema::create('spec_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spec_category_id')->constrained()->cascadeOnDelete();

            $table->string('key')->unique();   // network_technology, launch_status, etc.
            $table->string('label');           // Technology, Status, Dimensions, ...
            $table->string('type')->default('string'); // string,text,number,boolean,json
            $table->boolean('is_filterable')->default(false);
            $table->unsignedInteger('order')->default(0);
            
            $table->timestamps();
            $table->index('spec_category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spec_fields');
    }
};
