<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Student;
use App\Models\Major;
use App\Models\AcademicYear;
use App\Models\Invoice;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PdfFontTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $student;
    private $invoice;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user
        $this->admin = User::create([
            'username' => 'admin_test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'full_name' => 'Admin Test',
            'email' => 'admin@test.com',
            'is_active' => true,
        ]);

        // Create initial models
        $major = Major::create([
            'code' => 'CS',
            'name' => 'Computer Science',
            'description' => 'CS Department',
        ]);

        $academicYear = AcademicYear::create([
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
            'academic_year_id' => $academicYear->id,
            'status' => 'active',
        ]);

        $this->invoice = Invoice::create([
            'invoice_number' => 'INV-2026-0001',
            'student_id' => $this->student->id,
            'date' => '2026-06-13',
            'tuition_fee' => 1000000,
            'card_fee' => 50000,
            'photo_fee' => 20000,
            'email_fee' => 10000,
            'total_amount' => 1080000,
            'payment_status' => 'paid',
            'bank_account_number' => '160120000123456',
        ]);

        // Create a subject and grade for the student
        $subject = Subject::create([
            'subject_code' => 'SUBJ101',
            'subject_name' => 'ພາສາລາວ',
            'subject_name_en' => 'Lao Language',
            'credits' => 3,
            'major_id' => $major->id,
        ]);

        // Create an enrollment
        $enrollment = \App\Models\Enrollment::create([
            'student_id' => $this->student->id,
            'subject_id' => $subject->id,
            'academic_year_id' => $academicYear->id,
            'semester' => 1,
            'enrollment_date' => '2026-06-13',
            'status' => 'completed',
        ]);

        Grade::create([
            'enrollment_id' => $enrollment->id,
            'student_id' => $this->student->id,
            'subject_id' => $subject->id,
            'academic_year_id' => $academicYear->id,
            'semester' => 1,
            'midterm_score' => 40,
            'final_score' => 45,
            'total_score' => 85,
            'grade_point' => 4.0,
            'letter_grade' => 'A',
            'status' => 'completed',
        ]);
    }

    public function test_invoice_pdf_renders_successfully()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('invoices.pdf', $this->invoice->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_transcript_pdf_renders_successfully()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('students.transcript', $this->student->id));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    public function test_invoice_report_pdf_renders_successfully()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('invoices.report', ['status' => 'paid']));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }
}
