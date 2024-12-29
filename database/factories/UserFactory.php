<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'customer_id' => Customer::factory(),
            'registered_at' => $this->faker->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
        ];
    }
}
