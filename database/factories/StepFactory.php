<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Step>
 */
class StepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'step' => fake()->realText(),
            'step_index' => fake()->numberBetween(1, 5),
            'task_id' => fake()-> numberBetween(6, 12)
        ];
    }
}