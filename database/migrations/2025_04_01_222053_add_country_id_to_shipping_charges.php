<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('shipping_charges', function (Blueprint $table) {
            if (!Schema::hasColumn('shipping_charges', 'country_id')) {
                $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('shipping_charges', function (Blueprint $table) {
            $table->dropForeign(['country_id']);
            $table->dropColumn('country_id');
        });
    }
};
