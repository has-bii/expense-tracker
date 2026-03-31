<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    private const DESCRIPTIONS = [
        'Grocery shopping',
        'Uber ride',
        'Netflix subscription',
        'Coffee at cafe',
        'Lunch with colleagues',
        'Gym membership',
        'Phone bill',
        'Internet bill',
        'Gas station',
        'Online purchase',
        'Doctor visit',
        'Pharmacy',
        'Movie tickets',
        'Restaurant dinner',
        'Book purchase',
    ];

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'amount' => fake()->randomFloat(0, 10000, 5000000),
            'description' => fake()->randomElement(self::DESCRIPTIONS),
            'expense_date' => fake()->dateTimeBetween('-6 months', 'now'),
        ];
    }
}
