<?php

namespace Database\Seeders;

use App\Models\subjects;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubjectsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       subjects::factory()->count(50)->create();
    }
}
