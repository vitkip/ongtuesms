<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Student;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Major;
use App\Models\SystemLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradesManagement extends Component
{
    use WithPagination;

    public $activeTab = 'enrollments'; // enrollments, grading

    // Enrollment State & Search
    public $searchEnrollment = '';
    public $filterMajor = '';
    public $filterYear = '';
    public $filterSubject = '';

    // Modals
    public $showEnrollModal = false;
    public $showBulkEnrollModal = false;

    // Single Enrollment Fields
    public $enroll_student_id = '';
    public $enroll_subject_id = '';
    public $enroll_academic_year_id = '';
    public $enroll_semester = 1;

    // Bulk Enrollment Fields
    public $bulk_major_id = '';
    public $bulk_academic_year_id = '';   // year used to filter students (their entry year)
    public $bulk_enroll_year_id = '';     // year the course/enrollment belongs to
    public $bulk_semester = 1;
    public $bulk_subject_id = '';

    // Grading State & Filters
    public $selectedSubject = '';
    public $selectedAcademicYear = '';
    public $selectedSemester = 1;
    
    // Scores array: [student_id => [midterm, final, assignment, participation, practical, project, remarks]]
    public $scores = [];

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        // Load default academic year if available
        $activeYear = AcademicYear::where('status', 'active')->first();
        if ($activeYear) {
            $this->filterYear = $activeYear->id;
            $this->enroll_academic_year_id = $activeYear->id;
            $this->bulk_academic_year_id = $activeYear->id;
            $this->bulk_enroll_year_id = $activeYear->id;
            $this->selectedAcademicYear = $activeYear->id;
        }
    }

    public function switchTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetValidation();
    }

    // Single Enrollment Actions
    public function openEnrollModal()
    {
        $this->resetValidation();
        $this->enroll_student_id = '';
        $this->enroll_subject_id = '';
        $this->showEnrollModal = true;
    }

    public function enrollStudent()
    {
        $this->validate([
            'enroll_student_id' => 'required|exists:students,id',
            'enroll_subject_id' => 'required|exists:subjects,id',
            'enroll_academic_year_id' => 'required|exists:academic_years,id',
            'enroll_semester' => 'required|integer|between:1,8',
        ], [
            'enroll_student_id.required' => 'ກະລຸນາເລືອກນັກສຶກສາ.',
            'enroll_subject_id.required' => 'ກະລຸນາເລືອກວິຊາຮຽນ.',
            'enroll_academic_year_id.required' => 'ກະລຸນາເລືອກສົກຮຽນ.',
        ]);

        // Check if already enrolled
        $exists = Enrollment::where('student_id', $this->enroll_student_id)
            ->where('subject_id', $this->enroll_subject_id)
            ->where('academic_year_id', $this->enroll_academic_year_id)
            ->exists();

        if ($exists) {
            session()->flash('error', 'ນັກສຶກສາຄົນນີ້ໄດ້ລົງທະບຽນວິຊານີ້ໃນສົກຮຽນນີ້ແລ້ວ.');
            return;
        }

        DB::transaction(function () {
            $enrollment = Enrollment::create([
                'student_id' => $this->enroll_student_id,
                'subject_id' => $this->enroll_subject_id,
                'academic_year_id' => $this->enroll_academic_year_id,
                'semester' => $this->enroll_semester,
                'enrollment_date' => now(),
                'status' => 'enrolled'
            ]);

            // Create initial grade record
            Grade::create([
                'enrollment_id' => $enrollment->id,
                'student_id' => $this->enroll_student_id,
                'subject_id' => $this->enroll_subject_id,
                'academic_year_id' => $this->enroll_academic_year_id,
                'semester' => $this->enroll_semester,
                'status' => 'in_progress'
            ]);

            $student = Student::find($this->enroll_student_id);
            $subject = Subject::find($this->enroll_subject_id);

            SystemLog::create([
                'level' => 'info',
                'message' => "ລົງທະບຽນຮຽນ: ນັກສຶກສາ {$student->full_name} ({$student->student_id}) ວິຊາ {$subject->subject_name}",
                'user_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'context' => ['action' => 'enroll_student', 'student_id' => $student->student_id, 'subject_code' => $subject->subject_code]
            ]);
        });

        session()->flash('message', 'ລົງທະບຽນນັກສຶກສາຮຽບຮ້ອຍແລ້ວ.');
        $this->showEnrollModal = false;
    }

    // Bulk Enrollment Actions
    public function openBulkEnrollModal()
    {
        $this->resetValidation();
        $this->bulk_major_id = '';
        $this->bulk_subject_id = '';
        $activeYear = AcademicYear::where('status', 'active')->first();
        if ($activeYear) {
            $this->bulk_enroll_year_id = $activeYear->id;
        }
        $this->showBulkEnrollModal = true;
    }

    public function bulkEnroll()
    {
        $this->validate([
            'bulk_major_id' => 'required|exists:majors,id',
            'bulk_academic_year_id' => 'required|exists:academic_years,id',
            'bulk_enroll_year_id' => 'required|exists:academic_years,id',
            'bulk_semester' => 'required|integer|between:1,8',
            'bulk_subject_id' => 'required|exists:subjects,id',
        ], [
            'bulk_major_id.required' => 'ກະລຸນາເລືອກສາຂາວິຊາ.',
            'bulk_academic_year_id.required' => 'ກະລຸນາເລືອກສົກຮຽນທີ່ນັກສຶກສາເຂົ້າຮຽນ.',
            'bulk_enroll_year_id.required' => 'ກະລຸນາເລືອກສົກຮຽນທີ່ຈະລົງທະບຽນ.',
            'bulk_subject_id.required' => 'ກະລຸນາເລືອກວິຊາຮຽນ.',
        ]);

        // Get all students of this major and their entry academic year
        $students = Student::where('major_id', $this->bulk_major_id)
            ->where('academic_year_id', $this->bulk_academic_year_id)
            ->get();

        if ($students->isEmpty()) {
            session()->flash('error', 'ບໍ່ພົບນັກສຶກສາໃນສາຂາ ແລະ ສົກຮຽນທີ່ເລືອກ.');
            return;
        }

        $count = 0;
        DB::transaction(function () use ($students, &$count) {
            foreach ($students as $student) {
                // Check if already enrolled (use enrollment year, not student entry year)
                $exists = Enrollment::where('student_id', $student->id)
                    ->where('subject_id', $this->bulk_subject_id)
                    ->where('academic_year_id', $this->bulk_enroll_year_id)
                    ->exists();

                if (!$exists) {
                    $enrollment = Enrollment::create([
                        'student_id' => $student->id,
                        'subject_id' => $this->bulk_subject_id,
                        'academic_year_id' => $this->bulk_enroll_year_id,
                        'semester' => $this->bulk_semester,
                        'enrollment_date' => now(),
                        'status' => 'enrolled'
                    ]);

                    // Create grade sheet using enrollment year
                    Grade::create([
                        'enrollment_id' => $enrollment->id,
                        'student_id' => $student->id,
                        'subject_id' => $this->bulk_subject_id,
                        'academic_year_id' => $this->bulk_enroll_year_id,
                        'semester' => $this->bulk_semester,
                        'status' => 'in_progress'
                    ]);

                    $count++;
                }
            }

            $major = Major::find($this->bulk_major_id);
            $subject = Subject::find($this->bulk_subject_id);

            SystemLog::create([
                'level' => 'info',
                'message' => "ລົງທະບຽນກຸ່ມ: ລົງທະບຽນວິຊາ {$subject->subject_name} ໃຫ້ກັບນັກສຶກສາສາຂາ {$major->name} ທັງໝົດ {$count} ຄົນ",
                'user_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'context' => ['action' => 'bulk_enroll', 'major_id' => $this->bulk_major_id, 'subject_id' => $this->bulk_subject_id, 'count' => $count]
            ]);
        });

        session()->flash('message', "ລົງທະບຽນກຸ່ມສຳເລັດ {$count} ຄົນ.");
        $this->showBulkEnrollModal = false;
    }

    public function deleteEnrollment($id)
    {
        try {
            DB::transaction(function () use ($id) {
                $enrollment = Enrollment::with(['student', 'subject'])->findOrFail($id);
                $student = $enrollment->student;
                $subject = $enrollment->subject;

                Grade::where('enrollment_id', $enrollment->id)->delete();
                $enrollment->delete();

                SystemLog::create([
                    'level' => 'warning',
                    'message' => "ຍົກເລີກລົງທະບຽນ: ນັກສຶກສາ {$student->full_name} ({$student->student_id}) ວິຊາ {$subject->subject_name}",
                    'user_id' => Auth::id(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'context' => [
                        'action' => 'delete_enrollment',
                        'student_id' => $student->student_id,
                        'subject_id' => $subject->id,
                        'subject_code' => $subject->subject_code,
                    ]
                ]);
            });

            // Remove from in-memory scores to prevent orphaned grade creation on next save
            unset($this->scores[$id]);

            session()->flash('message', 'ຍົກເລີກການລົງທະບຽນຮຽນສຳເລັດ.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            session()->flash('error', 'ບໍ່ພົບຂໍ້ມູນການລົງທະບຽນ.');
        } catch (\Exception $e) {
            session()->flash('error', 'ເກີດຂໍ້ຜິດພາດ: ບໍ່ສາມາດຍົກເລີກການລົງທະບຽນໄດ້.');
        }
    }

    // Grading actions
    public function loadGradingSheet()
    {
        $this->validate([
            'selectedSubject' => 'required|exists:subjects,id',
            'selectedAcademicYear' => 'required|exists:academic_years,id',
            'selectedSemester' => 'required|integer|between:1,8',
        ], [
            'selectedSubject.required' => 'ກະລຸນາເລືອກວິຊາຮຽນ.',
            'selectedAcademicYear.required' => 'ກະລຸນາເລືອກສົກຮຽນ.',
        ]);

        $enrollments = Enrollment::where('subject_id', $this->selectedSubject)
            ->where('academic_year_id', $this->selectedAcademicYear)
            ->where('semester', $this->selectedSemester)
            ->with(['student', 'grade'])
            ->get();

        $this->scores = [];

        foreach ($enrollments as $enrollment) {
            $grade = $enrollment->grade;
            $this->scores[$enrollment->id] = [
                'student_id' => $enrollment->student_id,
                'student_name' => $enrollment->student->gendered_name,
                'student_code' => $enrollment->student->student_id,
                'midterm' => $grade ? $grade->midterm_score : '',
                'final' => $grade ? $grade->final_score : '',
                'assignment' => $grade ? $grade->assignment_score : '',
                'participation' => $grade ? $grade->participation_score : '',
                'practical' => $grade ? $grade->practical_score : '',
                'project' => $grade ? $grade->project_score : '',
                'remarks' => $grade ? $grade->remarks : '',
            ];
        }

        session()->flash('message_grading', 'ໂຫຼດຕາຕະລາງປ້ອນຄະແນນສຳເລັດແລ້ວ. ມີນັກສຶກສາທັງໝົດ ' . count($this->scores) . ' ຄົນ.');
    }

    private function calculateGradeDetails($total, $credits)
    {
        // Letters and grade points
        if ($total >= 85) {
            $letter = 'A';
            $point = 4.00;
        } elseif ($total >= 80) {
            $letter = 'B+';
            $point = 3.50;
        } elseif ($total >= 75) {
            $letter = 'B';
            $point = 3.00;
        } elseif ($total >= 70) {
            $letter = 'C+';
            $point = 2.50;
        } elseif ($total >= 65) {
            $letter = 'C';
            $point = 2.00;
        } elseif ($total >= 60) {
            $letter = 'D+';
            $point = 1.50;
        } elseif ($total >= 55) {
            $letter = 'D';
            $point = 1.00;
        } else {
            $letter = 'F';
            $point = 0.00;
        }

        return [
            'letter' => $letter,
            'point' => $point,
            'credits_earned' => ($point > 0) ? $credits : 0,
            'status' => ($point > 0) ? 'completed' : 'failed'
        ];
    }

    public function saveGrades()
    {
        if (empty($this->scores)) {
            session()->flash('error_grading', 'ບໍ່ມີຂໍ້ມູນຄະແນນທີ່ຈະບັນທຶກ.');
            return;
        }

        $subject = Subject::find($this->selectedSubject);
        $credits = $subject ? $subject->credits : 3;

        DB::transaction(function () use ($credits, $subject) {
            foreach ($this->scores as $enrollmentId => $scoreData) {
                // Skip if enrollment was deleted (e.g. from another tab/user) to avoid orphaned grades
                $enrollment = Enrollment::find($enrollmentId);
                if (!$enrollment) {
                    continue;
                }

                // Parse scores into floats (enforce maximum score limits)
                $mid = $scoreData['midterm'] !== '' ? min(20.0, floatval($scoreData['midterm'])) : 0;
                $fin = $scoreData['final'] !== '' ? min(40.0, floatval($scoreData['final'])) : 0;
                $ass = $scoreData['assignment'] !== '' ? min(15.0, floatval($scoreData['assignment'])) : 0;
                $part = $scoreData['participation'] !== '' ? min(10.0, floatval($scoreData['participation'])) : 0;
                $proj = $scoreData['project'] !== '' ? min(15.0, floatval($scoreData['project'])) : 0;

                $total = $mid + $fin + $ass + $part + $proj;
                // Clip total to 100 max
                if ($total > 100) $total = 100;

                $gradeDetails = $this->calculateGradeDetails($total, $credits);

                $grade = Grade::updateOrCreate([
                    'enrollment_id' => $enrollmentId,
                ], [
                    'student_id' => $scoreData['student_id'],
                    'subject_id' => $this->selectedSubject,
                    'academic_year_id' => $this->selectedAcademicYear,
                    'semester' => $this->selectedSemester,
                    'midterm_score' => $scoreData['midterm'] !== '' ? $mid : null,
                    'final_score' => $scoreData['final'] !== '' ? $fin : null,
                    'assignment_score' => $scoreData['assignment'] !== '' ? $ass : null,
                    'participation_score' => $scoreData['participation'] !== '' ? $part : null,
                    'practical_score' => null,
                    'project_score' => $scoreData['project'] !== '' ? $proj : null,
                    'total_score' => $total,
                    'percentage' => $total,
                    'letter_grade' => $gradeDetails['letter'],
                    'grade_point' => $gradeDetails['point'],
                    'credits_earned' => $gradeDetails['credits_earned'],
                    'status' => $gradeDetails['status'],
                    'remarks' => $scoreData['remarks'],
                    'graded_by' => Auth::id(),
                    'graded_at' => now(),
                ]);

                // Also update the enrollment status (reuse $enrollment fetched above)
                $enrollment->update(['status' => $gradeDetails['status']]);
            }

            SystemLog::create([
                'level' => 'info',
                'message' => "ບັນທຶກຄະແນນ: ວິຊາ {$subject->subject_name} ({$subject->subject_code}), ສົກຮຽນເທີມ {$this->selectedSemester}",
                'user_id' => Auth::id(),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'context' => ['action' => 'save_grades', 'subject_id' => $this->selectedSubject, 'semester' => $this->selectedSemester]
            ]);
        });

        session()->flash('message_grading', 'ບັນທຶກ ແລະ ຄຳນວນເກຣດນັກສຶກສາທັງໝົດຮຽບຮ້ອຍແລ້ວ.');
        $this->loadGradingSheet(); // Reload to show calculated totals/grades
    }

    public function render()
    {
        // 1. Fetch data for Enrollments view
        $enrollmentsQuery = Enrollment::query()
            ->with(['student.major', 'subject', 'academicYear']);

        if ($this->searchEnrollment) {
            $enrollmentsQuery->whereHas('student', function ($q) {
                $q->where('first_name', 'LIKE', '%' . $this->searchEnrollment . '%')
                  ->orWhere('last_name', 'LIKE', '%' . $this->searchEnrollment . '%')
                  ->orWhere('student_id', 'LIKE', '%' . $this->searchEnrollment . '%');
            });
        }

        if ($this->filterMajor) {
            $enrollmentsQuery->whereHas('student', function ($q) {
                $q->where('major_id', $this->filterMajor);
            });
        }

        if ($this->filterYear) {
            $enrollmentsQuery->where('academic_year_id', $this->filterYear);
        }

        if ($this->filterSubject) {
            $enrollmentsQuery->where('subject_id', $this->filterSubject);
        }

        $enrollments = $enrollmentsQuery->orderBy('created_at', 'desc')->paginate(10);

        // 2. Fetch dropdown choices
        $students = Student::orderBy('first_name', 'asc')->get();
        $user = Auth::user();
        if ($user->isAdmin()) {
            $subjects = Subject::where('status', 'active')->orderBy('subject_name', 'asc')->get();
        } else {
            $subjects = $user->subjects()->where('status', 'active')->orderBy('subject_name', 'asc')->get();
        }
        $academicYears = AcademicYear::orderBy('year', 'desc')->get();
        $majors = Major::where('status', 'active')->orderBy('name', 'asc')->get();

        return view('livewire.grades-management', [
            'enrollments' => $enrollments,
            'students' => $students,
            'subjects' => $subjects,
            'academicYears' => $academicYears,
            'majors' => $majors,
        ])->layout('layouts.app');
    }
}
