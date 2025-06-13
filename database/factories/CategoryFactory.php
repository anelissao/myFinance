<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'color' => $this->faker->safeHexColor(),
            'icon' => $this->faker->randomElement(['fa-coins', 'fa-shopping-cart', 'fa-utensils', 'fa-bolt']),
            'alert_threshold' => $this->faker->randomFloat(2, 50, 500),
        ];
    }
}
