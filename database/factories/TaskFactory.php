<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;
use App\Models\Priority;
use App\Models\Task;
use App\Models\User;
use App\Models\Step;
use App\Models\Status;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
                'title' => fake()->title(),
                'description' => fake()->paragraph(),
                'user_id' => fake()->numberBetween(1, 5),
                'category' => Category::factory(),
                'priority' => Priority::factory(),
                'status' => fake()->numberBetween(1,3),
                'due_date' => fake()->dateTimeBetween('now', '+1 month'),
                'completed_at' => fake()->dateTimeBetween('now', '+1 month'),
                'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
                'picture' => fake()->imageUrl(640, 480, 'cats', true, 'Faker')
        ];
    }
}