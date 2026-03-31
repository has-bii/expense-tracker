<?php

namespace Database\Seeders;

use App\Enums\Period;
use App\Models\Budget;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Income;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'testexampleuser',
        ]);

        $categories = Category::factory()
            ->count(8)
            ->sequence(
                ['name' => 'Food & Dining', 'icon' => '🍔'],
                ['name' => 'Transportation', 'icon' => '🚗'],
                ['name' => 'Shopping', 'icon' => '🛍️'],
                ['name' => 'Entertainment', 'icon' => '🎬'],
                ['name' => 'Health', 'icon' => '💊'],
                ['name' => 'Utilities', 'icon' => '💡'],
                ['name' => 'Rent', 'icon' => '🏠'],
                ['name' => 'Subscriptions', 'icon' => '📱'],
            )
            ->create(['user_id' => $user->id]);

        foreach ($categories as $category) {
            Expense::factory()
                ->count(fake()->numberBetween(5, 15))
                ->create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                ]);
        }

        Income::factory()
            ->count(12)
            ->create(['user_id' => $user->id]);

        $budgetCategories = $categories->random(5);
        foreach ($budgetCategories as $category) {
            Budget::factory()->create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'period' => fake()->randomElement(Period::cases()),
            ]);
        }
    }
}
