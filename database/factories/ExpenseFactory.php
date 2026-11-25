<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'category' => fake()->randomElement(['Alat Tulis', 'Peralatan', 'Kebersihan', 'Konsumsi']),
            'amount' => fake()->randomFloat(2, 10000, 500000),
            'receipt_image' => 'expenses/receipt-'.fake()->uuid().'.jpg',
            'expense_date' => fake()->dateTimeBetween('-1 month', 'now'),
            'recorded_by' => User::factory()->bendahara(),
        ];
    }
}
