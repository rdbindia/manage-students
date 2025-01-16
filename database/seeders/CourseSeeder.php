<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Traits\TruncateTable;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CourseSeeder extends Seeder
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

        $this->truncate('courses');

        Schema::enableForeignKeyConstraints();

        Course::factory(10)->create();
    }
}
