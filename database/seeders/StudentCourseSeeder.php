<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Student;
use App\Traits\TruncateTable;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class StudentCourseSeeder extends Seeder
{
    use TruncateTable;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->truncate('student_course');

        Schema::enableForeignKeyConstraints();

        $students = Student::all();
        $courses = Course::pluck('id')->shuffle()->toArray();

        $students->each(function ($student) use ($courses) {
            $student->courses()->attach(
                collect($courses)->random(rand(1, 5))->toArray()
            );
        });
    }
}
