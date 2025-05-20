<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
  /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Models\User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $sections = ['Computer Science', 'Information Technology', 'Engineering', 'Mechatronics'];
        $specialties = ['Computer Science', 'Information Technology', 'Engineering', 'Mechatronics'];

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // default password
            'Phone' => $this->faker->phoneNumber(),
            'specialty' => $this->faker->randomElement($specialties),
            'section' => $this->faker->numberBetween(1, 5),
            'years' => $this->faker->numberBetween(1, 4),
            // Provide a fake image URL instead of null to satisfy non-null constraint
            'image' => $this->faker->imageUrl(400, 400, 'people'),
            'code' => Str::upper(Str::random(6)),
            'code_created_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
