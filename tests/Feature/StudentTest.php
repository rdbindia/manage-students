<?php

namespace Tests\Feature;

use App\Models\Advisor;
use App\Models\Student;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(Authenticate::class);

        Advisor::factory()->create();
    }

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function a_student_can_be_created_via_filament()
    {
        $response = $this->post(route('filament.admin.resources.students.create'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'bio' => 'A sample bio',
            'date_of_birth' => '2000-01-01',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('students', ['email' => 'johndoe@example.com']);
    }

    /** @test */
    public function students_can_be_listed_via_filament()
    {
        $student = Student::factory()->create();

        $response = $this->get(route('filament.admin.resources.students.index'));

        $response->assertStatus(200);
        $response->assertSee($student->name);
    }

    /** @test */
    public function a_student_can_be_updated()
    {
        $student = Student::factory()->create();


        // Simulate form submission
        $this->actingAs($this->userWithPermission())
            ->editResource(Student::class, $student->id)
            ->fill([
                'name' => 'Jane Doe',
                'email' => 'janedoe@example.com',
                'bio' => 'Updated bio content',
                'date_of_birth' => '2000-01-01',
            ])
            ->call('save')
            ->assertRedirect('/admin/students');

        // Assert the database has the updated data
        $this->assertDatabaseHas('students', [
            'id' => $student->id,
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
        ]);
    }

    /** @test */
    public function a_student_can_be_deleted_via_filament()
    {
        $student = Student::factory()->create();

        $response = $this->delete(route('filament.admin.resources.students.destroy', $student->id));

        $response->assertStatus(302); // Filament redirects after deletion
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }

    /** @test */
    public function validation_checks_for_student_creation()
    {
        $response = $this->post(route('students.store'), [
            'name' => '',
            'email' => '',
        ]);

        $response->assertSessionHasErrors(['name', 'email']);
    }
}
