<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Project> */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $start = $this->faker->dateTimeBetween('+1 day', '+30 days');
        $due = (clone $start)->modify('+'.random_int(7, 45).' days');

        return [
            'client_name' => $this->faker->company(),
            'project_name' => $this->faker->catchPhrase(),
            'description' => $this->faker->sentence(),
            'status' => 'Planning',
            'priority' => 'Medium',
            'start_date' => $start->format('Y-m-d'),
            'due_date' => $due->format('Y-m-d'),
        ];
    }
}
