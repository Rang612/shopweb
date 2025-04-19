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
        Schema::table('store_locations', function (Blueprint $table) {
            $table->boolean('is_featured')->default(false)->after('longitude');
            $table->string('image')->nullable()->after('is_featured');
        });
    }

    public function down(): void
    {
        Schema::table('store_locations', function (Blueprint $table) {
            $table->dropColumn(['is_featured', 'image']);
        });
    }

};
