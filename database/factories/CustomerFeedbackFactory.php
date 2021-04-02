<?php

namespace Database\Factories;

use App\Models\CustomerFeedback;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFeedbackFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CustomerFeedback::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'customer_id'           => User::factory(),
            'reported_customer_id'  => User::factory(),
            'feedback'              => $this->faker->sentence,
            'is_bug'                => $this->faker->boolean
        ];
    }
}
