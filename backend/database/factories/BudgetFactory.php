<?php

namespace Database\Factories;

use App\Enums\Period;
use App\Models\Budget;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Budget>
 */
class BudgetFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'limit_amount' => fake()->randomFloat(0, 100000, 3000000),
            'period' => fake()->randomElement(Period::cases()),
        ];
    }
}
