<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\subjects>
 */
class SubjectsFactory extends Factory
{
    protected $model = \App\Models\subjects::class;

    public function definition()
    {
        $subjectNames = ['Math', 'Physics', 'Chemistry', 'Biology', 'History', 'Literature', 'Art', 'Geography', 'Computer Science', 'Economics'];
        return [
            'name' => $this->faker->unique()->randomElement($subjectNames),
        ];
    }
}
