<?php

namespace Tests\Feature;

use App\Filament\Resources\StudentResource\Pages\CreateStudent;
use App\Filament\Resources\StudentResource\Pages\EditStudent;
use App\Filament\Resources\StudentResource\Pages\ListStudents;
use App\Models\Advisor;
use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    protected Advisor $advisor;
    protected Course|Collection $courses;

    protected function setUp(): void
    {
        parent::setUp();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin);

        $this->advisor = Advisor::factory()->create();
        $this->courses = Course::factory()->count(3)->create();
    }

    public function testCanCreateAStudentViaFilament(): void
    {
        Livewire::test(CreateStudent::class)
            ->set('data.name', 'John Doe')
            ->set('data.email', 'johndoe@example.com')
            ->set('data.bio', 'A sample bio')
            ->set('data.date_of_birth', '2000-01-01')
            ->set('data.advisor_id', $this->advisor->id)
            ->set('data.courses', $this->courses->pluck('id')->toArray())
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('students', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);
    }

    public function testStudentsCanBeListed()
    {

        $student = Student::factory()->create([
            'advisor_id' => $this->advisor->id,
        ]);

        $student->courses()->attach($this->courses->pluck('id')->toArray());

        Livewire::test(ListStudents::class)
            ->assertSee($student->name)
            ->assertSee($student->email)
            ->assertSee($this->advisor->name);
    }

    /** @test */
    public function testStudentCanBeUpdated()
    {
        $student = Student::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'advisor_id' => $this->advisor->id,
        ]);

        $student->courses()->attach($this->courses->pluck('id')->toArray());

        Livewire::test(EditStudent::class, ['record' => $student->id])
            ->set('data.name', 'Updated Name')
            ->set('data.email', 'updated@example.com')
            ->set('data.courses', $this->courses->pluck('id')->toArray())
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    /** @test */
    public function testStudentCanBeDeleted()
    {
        $student = Student::factory()->create([
            'advisor_id' => $this->advisor->id,
        ]);

        $student->courses()->attach($this->courses->pluck('id')->toArray());

        Livewire::test(ListStudents::class)
            ->callTableAction('delete', $student)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('students', [
            'id' => $student->id,
        ]);
    }

    public function testValidationChecksForStudentCreation()
    {
        Livewire::test(CreateStudent::class)
            ->set('data.name', '')
            ->set('data.email', '')
            ->call('create')
            ->assertHasErrors(['data.name', 'data.email']);
    }
}
