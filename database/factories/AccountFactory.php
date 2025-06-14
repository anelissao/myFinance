<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountFactory extends Factory
{
    protected $model = Account::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->randomElement(['Bank Account', 'Savings', 'Cash', 'Investment']),
            'type' => $this->faker->randomElement(['CHECKING', 'SAVINGS', 'CREDIT_CARD', 'CASH', 'INVESTMENT']),
            'balance' => $this->faker->randomFloat(2, 0, 5000),
        ];
    }
}
