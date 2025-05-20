<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\grades;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GradesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // For each user, create between 3 and 6 grade records
        User::all()->each(function ($user) {
            grades::factory()
                ->count(rand(3, 6))
                ->create(['user_id' => $user->id]);
        });
    }
}
