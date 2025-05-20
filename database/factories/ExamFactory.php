<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Exam;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\exam>
 */
class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition()
    {
        $specialties = [
                'Computer Science',
                'Information Technology',
                'Engineering',
                'Mechatronics'
        ];

        return [
            'doctor_id'  => Doctor::factory(),              // لازم تكون عامل Factory للـ Doctor
            'name'       => $this->faker->sentence(3),
            'link'       => $this->faker->url,
            'date'       => $this->faker->date(),
            'start_time' => $this->faker->time('H:i:s'),
            'end_time'   => $this->faker->time('H:i:s'),
            'year'       => $this->faker->numberBetween(2018, 2025),
            'specialty'  => $this->faker->randomElement($specialties),
            'term'       => $this->faker->randomElement(['first', 'second']),
        ];
    }
}
