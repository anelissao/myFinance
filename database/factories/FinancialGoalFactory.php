<?php

namespace Database\Factories;

use App\Models\FinancialGoal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinancialGoalFactory extends Factory
{
    protected $model = FinancialGoal::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->words(2, true),
            'target_amount' => $this->faker->randomFloat(2, 500, 10000),
            'current_amount' => $this->faker->randomFloat(2, 0, 5000),
            'due_date' => $this->faker->dateTimeBetween('now', '+2 years'),
        ];
    }
}
