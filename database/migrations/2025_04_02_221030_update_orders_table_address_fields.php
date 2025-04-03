<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateOrdersTableAddressFields extends Migration
{
    public function up()
    {
        Schema::table('order', function (Blueprint $table) {
            // Thêm các trường mới (nếu chưa có)
            if (!Schema::hasColumn('order', 'district')) {
                $table->string('district', 255)->nullable()->after('city');
            }
            if (!Schema::hasColumn('order', 'ward')) {
                $table->string('ward', 255)->nullable()->after('district');
            }
            if (!Schema::hasColumn('order', 'street')) {
                $table->string('street', 255)->nullable()->after('ward');
            }
            if (!Schema::hasColumn('order', 'house_number')) {
                $table->string('house_number', 255)->nullable()->after('street');
            }
        });

        // Di chuyển dữ liệu từ các trường cũ sang trường mới (nếu cần)
        DB::statement("UPDATE `order` SET street = address WHERE address IS NOT NULL");
        DB::statement("UPDATE `order` SET house_number = apartment WHERE apartment IS NOT NULL");
        DB::statement("UPDATE `order` SET district = state WHERE state IS NOT NULL");

        // Xóa các trường cũ
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('apartment');
            $table->dropColumn('state');
        });
    }

    public function down()
    {
        Schema::table('order', function (Blueprint $table) {
            // Khôi phục các trường cũ
            $table->text('address')->nullable()->after('country_id');
            $table->string('apartment', 255)->nullable()->after('address');
            $table->string('state', 255)->nullable()->after('city');
        });

        // Di chuyển dữ liệu ngược lại (nếu cần)
        DB::statement("UPDATE `order` SET address = street WHERE street IS NOT NULL");
        DB::statement("UPDATE `order` SET apartment = house_number WHERE house_number IS NOT NULL");
        DB::statement("UPDATE `order` SET state = district WHERE district IS NOT NULL");

        // Xóa các trường mới
        Schema::table('order', function (Blueprint $table) {
            $table->dropColumn('district');
            $table->dropColumn('ward');
            $table->dropColumn('street');
            $table->dropColumn('house_number');
        });
    }
}
