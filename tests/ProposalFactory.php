<?php

namespace Database\Factories;

use App\Models\Enrollment;
use App\Models\Proposal;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProposalFactory extends Factory
{
    protected $model = Proposal::class;

    public function definition(): array
    {
        return [
            'enrollment_id' => Enrollment::factory(),
            'title' => $this->faker->sentence,
            'submission_date' => $this->faker->date(),
            'deadline' => $this->faker->date(),
            'status' => 'pending',
        ];
    }
}