<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Major;
use App\Models\AcademicYear;
use App\Models\Subject;
use App\Models\Enrollment;
use App\Models\Grade;
use Livewire\Livewire;
use App\Livewire\GradesManagement;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GradesSaveAndCalculateTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $students = [];
    private $subject;
    private $academicYear;
    private $enrollments = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::create([
            'username' => 'admin_grade_test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'full_name' => 'Admin Grade Test',
            'email' => 'admin_grade@test.com',
            'is_active' => true,
        ]);

        // Create major and academic year
        $major = Major::create([
            'code' => 'IT',
            'name' => 'ເຕັກໂນໂລຊີຂໍ້ມູນຂ່າວສານ',
            'description' => 'IT Department',
        ]);

        $this->academicYear = AcademicYear::create([
            'year' => '2025-2026',
            'status' => 'active',
        ]);

        // Create subject (3 credits)
        $this->subject = Subject::create([
            'subject_code' => 'IT101',
            'subject_name' => 'ການຂຽນໂປຣແກຣມ 1',
            'subject_name_en' => 'Programming 1',
            'credits' => 3,
            'major_id' => $major->id,
        ]);

        // Create 5 students with enrollments
        $studentData = [
            ['STD001', 'ສົມຈິດ', 'ພົມມະສອນ', 'ຊາຍ'],
            ['STD002', 'ນາງ ແກ້ວ', 'ສີສະຫວັດ', 'ຍິງ'],
            ['STD003', 'ສົມພອນ', 'ໄຊຍະວົງ', 'ຊາຍ'],
            ['STD004', 'ນາງ ຈັນທະລາ', 'ວົງພະຈັນ', 'ຍິງ'],
            ['STD005', 'ບຸນມີ', 'ສີສຸວັນ', 'ຊາຍ'],
        ];

        foreach ($studentData as $data) {
            $student = Student::create([
                'student_id' => $data[0],
                'first_name' => $data[1],
                'last_name' => $data[2],
                'gender' => $data[3],
                'dob' => '2002-01-01',
                'phone' => '20' . rand(10000000, 99999999),
                'major_id' => $major->id,
                'academic_year_id' => $this->academicYear->id,
                'status' => 'active',
            ]);

            $enrollment = Enrollment::create([
                'student_id' => $student->id,
                'subject_id' => $this->subject->id,
                'academic_year_id' => $this->academicYear->id,
                'semester' => 1,
                'enrollment_date' => now(),
                'status' => 'enrolled',
            ]);

            // Create initial empty grade record
            Grade::create([
                'enrollment_id' => $enrollment->id,
                'student_id' => $student->id,
                'subject_id' => $this->subject->id,
                'academic_year_id' => $this->academicYear->id,
                'semester' => 1,
                'status' => 'in_progress',
            ]);

            $this->students[] = $student;
            $this->enrollments[] = $enrollment;
        }
    }

    /**
     * Test 1: Loading the grading sheet populates scores array correctly
     */
    public function test_load_grading_sheet_populates_scores()
    {
        $this->actingAs($this->admin);

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // Check that 5 students were loaded
        $scores = $component->get('scores');
        $this->assertCount(5, $scores);

        // Check all enrollment IDs are present
        foreach ($this->enrollments as $enrollment) {
            $this->assertArrayHasKey($enrollment->id, $scores);
        }
    }

    /**
     * Test 2: Save grades with full scores - Grade A (85-100)
     */
    public function test_save_grades_grade_a()
    {
        $this->actingAs($this->admin);

        $enrollment = $this->enrollments[0];
        $student = $this->students[0];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // Set scores: 10 + 14 + 14 + 19 + 38 = 95 → A
        $component
            ->set("scores.{$enrollment->id}.participation", '10')
            ->set("scores.{$enrollment->id}.assignment", '14')
            ->set("scores.{$enrollment->id}.project", '14')
            ->set("scores.{$enrollment->id}.midterm", '19')
            ->set("scores.{$enrollment->id}.final", '38')
            ->set("scores.{$enrollment->id}.remarks", 'ນັກສຶກສາດີເດັ່ນ')
            ->call('saveGrades');

        // Verify database
        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertNotNull($grade);
        $this->assertEquals(95.0, $grade->total_score);
        $this->assertEquals('A', $grade->letter_grade);
        $this->assertEquals(4.00, $grade->grade_point);
        $this->assertEquals(3, $grade->credits_earned);
        $this->assertEquals('completed', $grade->status);
        $this->assertEquals('ນັກສຶກສາດີເດັ່ນ', $grade->remarks);

        // Verify enrollment status updated
        $enrollment->refresh();
        $this->assertEquals('completed', $enrollment->status);
    }

    /**
     * Test 3: Save grades - Grade B+ (80-84)
     */
    public function test_save_grades_grade_b_plus()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[1];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // 9 + 12 + 12 + 16 + 33 = 82 → B+
        $component
            ->set("scores.{$enrollment->id}.participation", '9')
            ->set("scores.{$enrollment->id}.assignment", '12')
            ->set("scores.{$enrollment->id}.project", '12')
            ->set("scores.{$enrollment->id}.midterm", '16')
            ->set("scores.{$enrollment->id}.final", '33')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(82.0, $grade->total_score);
        $this->assertEquals('B+', $grade->letter_grade);
        $this->assertEquals(3.50, $grade->grade_point);
        $this->assertEquals(3, $grade->credits_earned);
        $this->assertEquals('completed', $grade->status);
    }

    /**
     * Test 4: Save grades - Grade B (75-79)
     */
    public function test_save_grades_grade_b()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[2];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // 8 + 11 + 11 + 15 + 31 = 76 → B
        $component
            ->set("scores.{$enrollment->id}.participation", '8')
            ->set("scores.{$enrollment->id}.assignment", '11')
            ->set("scores.{$enrollment->id}.project", '11')
            ->set("scores.{$enrollment->id}.midterm", '15')
            ->set("scores.{$enrollment->id}.final", '31')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(76.0, $grade->total_score);
        $this->assertEquals('B', $grade->letter_grade);
        $this->assertEquals(3.00, $grade->grade_point);
        $this->assertEquals(3, $grade->credits_earned);
    }

    /**
     * Test 5: Save grades - Grade C+ (70-74)
     */
    public function test_save_grades_grade_c_plus()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[3];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // 7 + 10 + 10 + 14 + 30 = 71 → C+
        $component
            ->set("scores.{$enrollment->id}.participation", '7')
            ->set("scores.{$enrollment->id}.assignment", '10')
            ->set("scores.{$enrollment->id}.project", '10')
            ->set("scores.{$enrollment->id}.midterm", '14')
            ->set("scores.{$enrollment->id}.final", '30')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(71.0, $grade->total_score);
        $this->assertEquals('C+', $grade->letter_grade);
        $this->assertEquals(2.50, $grade->grade_point);
        $this->assertEquals(3, $grade->credits_earned);
    }

    /**
     * Test 6: Save grades - Grade C (65-69)
     */
    public function test_save_grades_grade_c()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[4];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // 6 + 9 + 10 + 12 + 29 = 66 → C
        $component
            ->set("scores.{$enrollment->id}.participation", '6')
            ->set("scores.{$enrollment->id}.assignment", '9')
            ->set("scores.{$enrollment->id}.project", '10')
            ->set("scores.{$enrollment->id}.midterm", '12')
            ->set("scores.{$enrollment->id}.final", '29')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(66.0, $grade->total_score);
        $this->assertEquals('C', $grade->letter_grade);
        $this->assertEquals(2.00, $grade->grade_point);
        $this->assertEquals(3, $grade->credits_earned);
    }

    /**
     * Test 7: Save grades - Grade D+ (60-64)
     */
    public function test_save_grades_grade_d_plus()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[0];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // 5 + 8 + 9 + 12 + 28 = 62 → D+
        $component
            ->set("scores.{$enrollment->id}.participation", '5')
            ->set("scores.{$enrollment->id}.assignment", '8')
            ->set("scores.{$enrollment->id}.project", '9')
            ->set("scores.{$enrollment->id}.midterm", '12')
            ->set("scores.{$enrollment->id}.final", '28')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(62.0, $grade->total_score);
        $this->assertEquals('D+', $grade->letter_grade);
        $this->assertEquals(1.50, $grade->grade_point);
        $this->assertEquals(3, $grade->credits_earned);
    }

    /**
     * Test 8: Save grades - Grade D (55-59)
     */
    public function test_save_grades_grade_d()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[0];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // 4 + 7 + 8 + 10 + 27 = 56 → D
        $component
            ->set("scores.{$enrollment->id}.participation", '4')
            ->set("scores.{$enrollment->id}.assignment", '7')
            ->set("scores.{$enrollment->id}.project", '8')
            ->set("scores.{$enrollment->id}.midterm", '10')
            ->set("scores.{$enrollment->id}.final", '27')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(56.0, $grade->total_score);
        $this->assertEquals('D', $grade->letter_grade);
        $this->assertEquals(1.00, $grade->grade_point);
        $this->assertEquals(3, $grade->credits_earned);
    }

    /**
     * Test 9: Save grades - Grade F (0-54) = failed, 0 credits
     */
    public function test_save_grades_grade_f()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[0];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // 3 + 5 + 5 + 8 + 20 = 41 → F
        $component
            ->set("scores.{$enrollment->id}.participation", '3')
            ->set("scores.{$enrollment->id}.assignment", '5')
            ->set("scores.{$enrollment->id}.project", '5')
            ->set("scores.{$enrollment->id}.midterm", '8')
            ->set("scores.{$enrollment->id}.final", '20')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(41.0, $grade->total_score);
        $this->assertEquals('F', $grade->letter_grade);
        $this->assertEquals(0.00, $grade->grade_point);
        $this->assertEquals(0, $grade->credits_earned);
        $this->assertEquals('failed', $grade->status);

        // Verify enrollment also shows failed
        $enrollment->refresh();
        $this->assertEquals('failed', $enrollment->status);
    }

    /**
     * Test 10: Score clamping - midterm max 20
     */
    public function test_score_clamping_midterm()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[0];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // Set midterm to 25 (should be clamped to 20)
        $component
            ->set("scores.{$enrollment->id}.participation", '10')
            ->set("scores.{$enrollment->id}.assignment", '15')
            ->set("scores.{$enrollment->id}.project", '15')
            ->set("scores.{$enrollment->id}.midterm", '25')
            ->set("scores.{$enrollment->id}.final", '40')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        // midterm clamped from 25 to 20, total = 10+15+15+20+40 = 100
        $this->assertEquals(20.0, $grade->midterm_score);
        $this->assertEquals(100.0, $grade->total_score);
    }

    /**
     * Test 11: Score clamping - final max 40
     */
    public function test_score_clamping_final()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[0];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // Set final to 50 (should be clamped to 40)
        $component
            ->set("scores.{$enrollment->id}.participation", '5')
            ->set("scores.{$enrollment->id}.assignment", '5')
            ->set("scores.{$enrollment->id}.project", '5')
            ->set("scores.{$enrollment->id}.midterm", '10')
            ->set("scores.{$enrollment->id}.final", '50')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(40.0, $grade->final_score);
        // Total = 5+5+5+10+40 = 65
        $this->assertEquals(65.0, $grade->total_score);
    }

    /**
     * Test 12: Perfect scores = 100, Grade A
     */
    public function test_perfect_scores()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[0];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // 10 + 15 + 15 + 20 + 40 = 100 → A
        $component
            ->set("scores.{$enrollment->id}.participation", '10')
            ->set("scores.{$enrollment->id}.assignment", '15')
            ->set("scores.{$enrollment->id}.project", '15')
            ->set("scores.{$enrollment->id}.midterm", '20')
            ->set("scores.{$enrollment->id}.final", '40')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(100.0, $grade->total_score);
        $this->assertEquals('A', $grade->letter_grade);
        $this->assertEquals(4.00, $grade->grade_point);
    }

    /**
     * Test 13: Zero scores = 0, Grade F
     */
    public function test_zero_scores()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[0];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // All 0 → 0 → F
        $component
            ->set("scores.{$enrollment->id}.participation", '0')
            ->set("scores.{$enrollment->id}.assignment", '0')
            ->set("scores.{$enrollment->id}.project", '0')
            ->set("scores.{$enrollment->id}.midterm", '0')
            ->set("scores.{$enrollment->id}.final", '0')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(0.0, $grade->total_score);
        $this->assertEquals('F', $grade->letter_grade);
        $this->assertEquals(0.00, $grade->grade_point);
        $this->assertEquals(0, $grade->credits_earned);
        $this->assertEquals('failed', $grade->status);
    }

    /**
     * Test 14: Empty scores are treated as 0
     */
    public function test_empty_scores_treated_as_zero()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[0];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // Leave all fields empty (default '')
        $component->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(0.0, $grade->total_score);
        $this->assertEquals('F', $grade->letter_grade);
    }

    /**
     * Test 15: Boundary test - exactly 85 should be A
     */
    public function test_boundary_85_is_grade_a()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[0];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // 10 + 15 + 15 + 10 + 35 = 85 → A
        $component
            ->set("scores.{$enrollment->id}.participation", '10')
            ->set("scores.{$enrollment->id}.assignment", '15')
            ->set("scores.{$enrollment->id}.project", '15')
            ->set("scores.{$enrollment->id}.midterm", '10')
            ->set("scores.{$enrollment->id}.final", '35')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(85.0, $grade->total_score);
        $this->assertEquals('A', $grade->letter_grade);
    }

    /**
     * Test 16: Boundary test - 84 should be B+ (not A)
     */
    public function test_boundary_84_is_grade_b_plus()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[0];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // 10 + 15 + 15 + 10 + 34 = 84 → B+
        $component
            ->set("scores.{$enrollment->id}.participation", '10')
            ->set("scores.{$enrollment->id}.assignment", '15')
            ->set("scores.{$enrollment->id}.project", '15')
            ->set("scores.{$enrollment->id}.midterm", '10')
            ->set("scores.{$enrollment->id}.final", '34')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals(84.0, $grade->total_score);
        $this->assertEquals('B+', $grade->letter_grade);
    }

    /**
     * Test 17: Boundary test - 55 = D, 54 = F
     */
    public function test_boundary_55_is_d_and_54_is_f()
    {
        $this->actingAs($this->admin);

        // Test 55 = D
        $enrollment1 = $this->enrollments[0];
        $enrollment2 = $this->enrollments[1];

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // Student 1: total = 55 → D
        $component
            ->set("scores.{$enrollment1->id}.participation", '5')
            ->set("scores.{$enrollment1->id}.assignment", '7')
            ->set("scores.{$enrollment1->id}.project", '8')
            ->set("scores.{$enrollment1->id}.midterm", '10')
            ->set("scores.{$enrollment1->id}.final", '25');

        // Student 2: total = 54 → F
        $component
            ->set("scores.{$enrollment2->id}.participation", '5')
            ->set("scores.{$enrollment2->id}.assignment", '7')
            ->set("scores.{$enrollment2->id}.project", '7')
            ->set("scores.{$enrollment2->id}.midterm", '10')
            ->set("scores.{$enrollment2->id}.final", '25')
            ->call('saveGrades');

        $grade1 = Grade::where('enrollment_id', $enrollment1->id)->first();
        $grade2 = Grade::where('enrollment_id', $enrollment2->id)->first();

        $this->assertEquals(55.0, $grade1->total_score);
        $this->assertEquals('D', $grade1->letter_grade);
        $this->assertEquals('completed', $grade1->status);

        $this->assertEquals(54.0, $grade2->total_score);
        $this->assertEquals('F', $grade2->letter_grade);
        $this->assertEquals('failed', $grade2->status);
    }

    /**
     * Test 18: Save all 5 students at once
     */
    public function test_save_all_students_grades_at_once()
    {
        $this->actingAs($this->admin);

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // Set different scores for each student
        $testScores = [
            // [participation, assignment, project, midterm, final, expectedTotal, expectedGrade]
            [10, 15, 15, 20, 40, 100, 'A'],    // Student 1: Perfect
            [9,  12, 12, 16, 33,  82, 'B+'],   // Student 2
            [8,  11, 11, 15, 31,  76, 'B'],    // Student 3
            [7,  10, 10, 14, 25,  66, 'C'],    // Student 4
            [3,   5,  5,  8, 20,  41, 'F'],    // Student 5: Fail
        ];

        foreach ($this->enrollments as $i => $enrollment) {
            $s = $testScores[$i];
            $component
                ->set("scores.{$enrollment->id}.participation", (string)$s[0])
                ->set("scores.{$enrollment->id}.assignment", (string)$s[1])
                ->set("scores.{$enrollment->id}.project", (string)$s[2])
                ->set("scores.{$enrollment->id}.midterm", (string)$s[3])
                ->set("scores.{$enrollment->id}.final", (string)$s[4]);
        }

        $component->call('saveGrades');

        // Verify all 5 students were saved correctly
        foreach ($this->enrollments as $i => $enrollment) {
            $grade = Grade::where('enrollment_id', $enrollment->id)->first();
            $expected = $testScores[$i];

            $this->assertNotNull($grade, "Grade for student {$i} should exist");
            $this->assertEquals($expected[5], $grade->total_score, "Total for student {$i}");
            $this->assertEquals($expected[6], $grade->letter_grade, "Letter grade for student {$i}");
            $this->assertNotNull($grade->graded_by, "graded_by for student {$i} should be set");
            $this->assertNotNull($grade->graded_at, "graded_at for student {$i} should be set");
        }
    }

    /**
     * Test 19: Grades can be updated (overwritten)
     */
    public function test_grades_can_be_updated()
    {
        $this->actingAs($this->admin);
        $enrollment = $this->enrollments[0];

        // First save: low scores → F
        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        $component
            ->set("scores.{$enrollment->id}.participation", '3')
            ->set("scores.{$enrollment->id}.assignment", '5')
            ->set("scores.{$enrollment->id}.project", '5')
            ->set("scores.{$enrollment->id}.midterm", '8')
            ->set("scores.{$enrollment->id}.final", '20')
            ->call('saveGrades');

        $grade = Grade::where('enrollment_id', $enrollment->id)->first();
        $this->assertEquals('F', $grade->letter_grade);
        $this->assertEquals(41.0, $grade->total_score);

        // Second save: higher scores → A
        $component2 = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        $component2
            ->set("scores.{$enrollment->id}.participation", '10')
            ->set("scores.{$enrollment->id}.assignment", '15')
            ->set("scores.{$enrollment->id}.project", '15')
            ->set("scores.{$enrollment->id}.midterm", '20')
            ->set("scores.{$enrollment->id}.final", '40')
            ->call('saveGrades');

        $grade->refresh();
        $this->assertEquals('A', $grade->letter_grade);
        $this->assertEquals(100.0, $grade->total_score);
        $this->assertEquals('completed', $grade->status);
    }

    /**
     * Test 20: Save empty scores does nothing (scores array is empty without loading)
     */
    public function test_save_empty_scores_array_does_nothing()
    {
        $this->actingAs($this->admin);

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1);

        // Don't load grading sheet, scores is empty
        $component->call('saveGrades');

        // Should NOT have updated any grades (all should still be in_progress)
        $gradesUpdated = Grade::whereNotNull('graded_at')->count();
        $this->assertEquals(0, $gradesUpdated, 'No grades should have been updated when scores array is empty');
    }

    /**
     * Test 21: SystemLog is created when grades are saved
     */
    public function test_system_log_created_on_save()
    {
        $this->actingAs($this->admin);

        $component = Livewire::test(GradesManagement::class)
            ->set('selectedSubject', $this->subject->id)
            ->set('selectedAcademicYear', $this->academicYear->id)
            ->set('selectedSemester', 1)
            ->call('loadGradingSheet');

        // Set scores and save
        $enrollment = $this->enrollments[0];
        $component
            ->set("scores.{$enrollment->id}.participation", '10')
            ->set("scores.{$enrollment->id}.assignment", '15')
            ->set("scores.{$enrollment->id}.project", '15')
            ->set("scores.{$enrollment->id}.midterm", '20')
            ->set("scores.{$enrollment->id}.final", '40')
            ->call('saveGrades');

        // Check system log was created
        $this->assertDatabaseHas('system_logs', [
            'level' => 'info',
            'user_id' => $this->admin->id,
        ]);
    }
}
