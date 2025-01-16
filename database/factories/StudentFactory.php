<?php

namespace Database\Factories;

use App\Models\Advisor;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'bio' => $this->faker->sentence,
            'date_of_birth' => Carbon::now()->subYears(rand(20, 30))->format('Y-m-d'),
            'advisor_id' => Advisor::inRandomOrder()->first()->id,
        ];
    }
}
