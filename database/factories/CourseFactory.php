<?php

namespace Database\Factories;

use App\Models\Advisor;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'advisor_id' => Advisor::inRandomOrder()->first()->id,
        ];
    }
}
