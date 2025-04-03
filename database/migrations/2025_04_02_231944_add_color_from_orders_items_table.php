<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('color', 255)->nullable()->after('name');
            $table->string('size', 255)->nullable()->after('color');
        });
    }

    public function down()
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->string('color', 255)->nullable()->after('name');
            $table->string('size', 255)->nullable()->after('color');
        });
    }
};
