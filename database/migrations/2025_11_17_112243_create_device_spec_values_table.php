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
        Schema::create('device_spec_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained()->cascadeOnDelete();
            $table->foreignId('spec_field_id')->constrained()->cascadeOnDelete();

            $table->text('value_string')->nullable();
            $table->decimal('value_number', 12, 3)->nullable();
            $table->json('value_json')->nullable();
            $table->string('unit')->nullable();

            $table->timestamps();

            $table->unique(['device_id', 'spec_field_id']);
            $table->index('spec_field_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_spec_values');
    }
};
