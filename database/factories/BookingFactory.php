<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $endDate = (clone $startDate)->modify('+'.rand(1, 10).' days');

        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'car_id' => Car::inRandomOrder()->first()->id,
            'start_date' => $startDate->format('Y-m-d'), // Convert to string
            'end_date' => $endDate->format('Y-m-d'),     // Convert to string
            'total_price' => function (array $attributes) {
                $car = Car::find($attributes['car_id']);
                return $car->price_per_day * ((strtotime($attributes['end_date']) - strtotime($attributes['start_date'])) / (60 * 60 * 24));
            },
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'completed', 'canceled']),
        ];
    }
}
