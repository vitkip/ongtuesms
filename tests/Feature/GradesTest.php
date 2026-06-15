<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Major;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\Grade;
use Livewire\Livewire;
use App\Livewire\GradesManagement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GradesTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $student;
    private $subject;
    private $academicYear;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'username' => 'admin_test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'full_name' => 'Admin Test',
            'email' => 'admin@test.com',
            'is_active' => true,
        ]);

        $major = Major::create([
            'code' => 'CS',
            'name' => 'Computer Science',
            'description' => 'CS Department',
        ]);

        $this->academicYear = AcademicYear::create([
            'year' => '2025-2026',
            'status' => 'active',
        ]);

        $this->student = Student::create([
            'student_id' => 'STD001',
            'first_name' => 'Somsack',
            'last_name' => 'Keo',
            'gender' => 'ຊາຍ',
            'dob' => '2000-01-01',
            'phone' => '12345678',
            'email' => 'somsack@test.com',
            'major_id' => $major->id,
            'academic_year_id' => $this->academicYear->id,
            'status' => 'active',
        ]);

        $this->subject = Subject::create([
            'subject_code' => 'SUBJ101',
            'subject_name' => 'ພາສາລາວ',
            'subject_name_en' => 'Lao Language',
            'credits' => 3,
            'status' => 'active',
            'major_id' => $major->id,
        ]);

        // Create an enrollment
        $enrollment = Enrollment::create([
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester' => 1,
            'enrollment_date' => '2026-06-13',
            'status' => 'enrolled',
        ]);

        // Create a grade record
        Grade::create([
            'enrollment_id' => $enrollment->id,
            'student_id' => $this->student->id,
            'subject_id' => $this->subject->id,
            'academic_year_id' => $this->academicYear->id,
            'semester' => 1,
            'status' => 'in_progress',
        ]);
    }

    public function test_can_load_and_save_grades()
    {
        $this->actingAs($this->admin);

        Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet')
            ->assertHasNoErrors()
            ->assertSet('scores', function($scores) {
                return count($scores) === 1;
            })
            ->set('scores.1.midterm', 20)
            ->set('scores.1.final', 40)
            ->set('scores.1.assignment', 15)
            ->set('scores.1.participation', 10)
            ->set('scores.1.project', 15)
            ->call('saveGrades')
            ->assertHasNoErrors();

        // Verify that the Grade is saved with total score 100
        $grade = Grade::where('student_id', $this->student->id)->first();
        $this->assertNotNull($grade);
        $this->assertEquals(100.0, floatval($grade->total_score));
        $this->assertEquals('A', $grade->letter_grade);
    }
}
