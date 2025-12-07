<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\Proposal;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProposalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Proposal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'group_id' => Group::factory(),
            'title' => $this->faker->sentence,
            'status' => 'pending',
            'submission_date' => $this->faker->date(),
            'deadline' => $this->faker->date(),
            'abstract' => $this->faker->paragraph,
            'manuscript_path' => $this->faker->word . '.pdf',
            'published_date' => $this->faker->date(),
        ];
    }
}
