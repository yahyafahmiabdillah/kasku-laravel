<?php

namespace Database\Factories;

use App\Models\KasPeriod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<KasPeriod>
 */
class KasPeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'Kas '.fake()->monthName().' '.fake()->year(),
            'description' => fake()->sentence(),
            'amount' => fake()->randomFloat(2, 5000, 500000),
            'due_date' => fake()->dateTimeBetween('now', '+2 months'),
            'created_by' => User::factory()->bendahara(),
        ];
    }
}
