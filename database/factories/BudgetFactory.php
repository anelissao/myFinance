<?php

namespace Database\Factories;

use App\Models\Budget;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class BudgetFactory extends Factory
{
    protected $model = Budget::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'category_id' => Category::factory(),
            'month' => $this->faker->dateTimeBetween('-6 months', '+6 months'),
            'planned_amount' => $this->faker->randomFloat(2, 100, 2000),
            'actual_amount' => $this->faker->randomFloat(2, 0, 2000),
        ];
    }
}
