<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class BookingSummary extends Command
{
    protected $signature = 'booking:summary';
    protected $description = 'Generate daily summary of bookings';

    public function handle()
    {
        $today = Carbon::today();

        $totalBookings = Booking::whereDate('created_at', $today)->count();
        $bookingsByStatus = Booking::whereDate('created_at', $today)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');
        $totalRevenue = Booking::whereDate('created_at', $today)
            ->where('status', 'completed')
            ->sum('total_price');

        $this->info("Total bookings today: $totalBookings");
        foreach ($bookingsByStatus as $status => $count) {
            $this->info("$status: $count");
        }
        $this->info("Total revenue today: $totalRevenue");
    }
}
