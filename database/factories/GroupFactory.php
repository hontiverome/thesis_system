<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Group::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'group_code' => $this->faker->unique()->regexify('[A-Z]{3}[0-9]{3}'),
            'year_level' => $this->faker->numberBetween(1, 5),
        ];
    }
}
