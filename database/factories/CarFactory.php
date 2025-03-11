<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CarFactory extends Factory
{
    protected $model = Car::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'brand' => $this->faker->company(),
            'price_per_day' => $this->faker->randomFloat(2, 50, 500),
            'availability_status' => 'available',
        ];
    }
}
