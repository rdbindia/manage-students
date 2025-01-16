<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Student;
use App\Models\StudentCourse;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentCourse>
 */
class StudentCourseFactory extends Factory
{
    protected $model = StudentCourse::class;
    public function definition(): array
    {
        return [
            'student_id' => Student::inRandomOrder()->first()->id,
            'course_id' => Course::inRandomOrder()->first()->id,
        ];
    }
}
