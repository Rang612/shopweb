<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameOrderTableToOrders extends Migration
{
    public function up()
    {
        Schema::rename('order', 'orders'); // Đổi tên bảng từ 'order' thành 'orders'
    }

    public function down()
    {
        Schema::rename('orders', 'order'); // Hoàn tác bằng cách đổi lại tên bảng
    }
}
