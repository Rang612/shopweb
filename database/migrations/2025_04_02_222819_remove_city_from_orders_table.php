<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('city'); // Xóa cột city
        });
    }

    public function down() {
        Schema::table('order', function (Blueprint $table) {
            $table->string('city', 255)->nullable(); // Thêm lại cột city nếu rollback
        });
    }
};
