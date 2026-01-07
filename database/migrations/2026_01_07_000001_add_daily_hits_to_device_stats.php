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
        Schema::table('device_stats', function (Blueprint $table) {
            $table->unsignedBigInteger('daily_hits')->default(0)->after('total_hits');
            $table->date('daily_hits_date')->nullable()->after('daily_hits');

            $table->index('daily_hits');
            $table->index('daily_hits_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('device_stats', function (Blueprint $table) {
            $table->dropIndex(['daily_hits']);
            $table->dropIndex(['daily_hits_date']);
            $table->dropColumn(['daily_hits', 'daily_hits_date']);
        });
    }
};
