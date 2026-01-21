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
        Schema::table('users', function (Blueprint $table) {
        $table->string('image')->nullable()->after('email');        // profile image
        $table->string('address')->nullable()->after('image');      // user location (e.g. J7Z)
       $table->string('country')->nullable()->after('image');   // optional full country
        $table->text('bio')->nullable()->after('country');          // optional description
        $table->timestamp('last_login_at')->nullable()->after('bio'); // last login time
        $table->enum('status', ['active','suspended'])->default('active')->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
