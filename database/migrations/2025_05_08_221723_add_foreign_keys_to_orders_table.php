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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign(['payment_method_id'])->references(['id'])->on('payment_methods')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['country_id'], 'order_country_id_foreign')->references(['id'])->on('countries')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'], 'order_user_id_foreign')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign('orders_payment_method_id_foreign');
            $table->dropForeign('order_country_id_foreign');
            $table->dropForeign('order_user_id_foreign');
        });
    }
};
