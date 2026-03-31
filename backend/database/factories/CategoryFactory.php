<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    private const CATEGORIES = [
        ['name' => 'Food & Dining', 'icon' => '🍔'],
        ['name' => 'Transportation', 'icon' => '🚗'],
        ['name' => 'Shopping', 'icon' => '🛍️'],
        ['name' => 'Entertainment', 'icon' => '🎬'],
        ['name' => 'Health', 'icon' => '💊'],
        ['name' => 'Utilities', 'icon' => '💡'],
        ['name' => 'Rent', 'icon' => '🏠'],
        ['name' => 'Education', 'icon' => '📚'],
        ['name' => 'Travel', 'icon' => '✈️'],
        ['name' => 'Subscriptions', 'icon' => '📱'],
    ];

    public function definition(): array
    {
        $category = fake()->randomElement(self::CATEGORIES);

        return [
            'name' => $category['name'],
            'icon' => $category['icon'],
            'user_id' => User::factory(),
        ];
    }
}
