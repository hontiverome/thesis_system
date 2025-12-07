<?php

namespace Database\Factories;

use App\Models\Enrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EnrollmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Enrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'group_id' => \App\Models\Group::factory(),
            'course_id' => \App\Models\Course::factory(),
            'school_year' => $this->faker->year(),
            'semester' => $this->faker->randomElement(['Fall', 'Spring', 'Summer']),
        ];
    }
}
