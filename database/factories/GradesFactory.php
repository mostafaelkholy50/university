<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\grades>
 */
class GradesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\grades::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $grades = ['Excellent', 'Very Good', 'Good', 'Pass', 'Fail'];

        return [
            'user_id' => \App\Models\User::factory(), // set by seeder
            'subject_id' =>1,
            'grade' => $this->faker->randomElement($grades),
        ];
    }
}
