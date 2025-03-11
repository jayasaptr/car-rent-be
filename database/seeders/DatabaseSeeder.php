<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // Seed 500 Users
        User::factory(500)->create();

        // Seed 1000 Cars
        Car::factory(1000)->create();

        // Seed 2000 Bookings
        Booking::factory(2000)->create();
    }
}
