<?php

namespace Database\Factories;

use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use App\Models\KasPeriod;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kas_period_id' => KasPeriod::factory(),
            'user_id' => User::factory(),
            'payment_method' => fake()->randomElement(PaymentMethod::cases()),
            'proof_image' => null,
            'status' => PaymentStatus::Pending,
            'verified_at' => null,
        ];
    }

    /**
     * Indicate that the payment has been verified.
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Approved,
            'verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the payment has been rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Rejected,
            'verified_at' => now(),
        ]);
    }

    /**
     * Indicate that the payment was made by bank transfer.
     */
    public function transfer(): static
    {
        return $this->state(fn (array $attributes) => [
            'payment_method' => PaymentMethod::Transfer,
            'proof_image' => 'payments/proof-'.fake()->uuid().'.jpg',
        ]);
    }
}
