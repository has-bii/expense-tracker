<?php

namespace Database\Factories;

use App\Models\Income;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Income>
 */
class IncomeFactory extends Factory
{
    private const SOURCES = [
        'Salary',
        'Freelance',
        'Investment returns',
        'Side project',
        'Rental income',
        'Bonus',
        'Gift',
        'Refund',
    ];

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'amount' => fake()->randomFloat(0, 500000, 30000000),
            'source' => fake()->randomElement(self::SOURCES),
            'description' => fake()->optional(0.7)->sentence(4),
            'income_date' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
