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
        Schema::table('coupon_user', function (Blueprint $table) {
            $table->foreign(['coupon_id'])->references(['id'])->on('discount_coupons')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupon_user', function (Blueprint $table) {
            $table->dropForeign('coupon_user_coupon_id_foreign');
            $table->dropForeign('coupon_user_user_id_foreign');
        });
    }
};
