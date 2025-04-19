<?php

namespace App\Console;

use App\Models\Order;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $expiredOrders = Order::where('payment_status', 'pending')
                ->where('order_status', 'pending')
                ->where('created_at', '<=', now()->subHours(24))
                ->get();

            foreach ($expiredOrders as $order) {
                $order->update([
                    'payment_status' => 'unpaid',
                    'order_status'   => 'cancelled',
                ]);

                Log::info("Đã huỷ đơn hàng #{$order->id} vì quá hạn thanh toán.");
            }
        })->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
