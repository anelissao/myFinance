<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 5 users with the same password
        \App\Models\User::factory(5)->create([
            'password' => \Illuminate\Support\Facades\Hash::make('password123'),
        ])->each(function ($user) {
            // Create default bank account for each user
            $account = \App\Models\Account::factory()->create([
                'user_id' => $user->id,
                'name' => 'Bank Account',
                'type' => 'CHECKING',
                'balance' => 1000,
            ]);

            // Create 3 categories per user
            $categories = \App\Models\Category::factory(3)->create();

            // Create 5 transactions for each user, assigned to their bank account and random category
            foreach (range(1, 5) as $i) {
                \App\Models\Transaction::factory()->create([
                    'user_id' => $user->id,
                    'account_id' => $account->id,
                    'category_id' => $categories->random()->id,
                ]);
            }

            // Create 2 budgets per user
            foreach ($categories as $category) {
                \App\Models\Budget::factory()->create([
                    'user_id' => $user->id,
                    'category_id' => $category->id,
                ]);
            }

            // Create 2 financial goals per user
            \App\Models\FinancialGoal::factory(2)->create([
                'user_id' => $user->id,
            ]);
        });
    }
}
