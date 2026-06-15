<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $table = 'grades';

    protected $fillable = [
        'enrollment_id',
        'student_id',
        'subject_id',
        'academic_year_id',
        'semester',
        'midterm_score',
        'final_score',
        'assignment_score',
        'participation_score',
        'practical_score',
        'project_score',
        'total_score',
        'percentage',
        'letter_grade',
        'grade_point',
        'credits_earned',
        'status',
        'remarks',
        'graded_by',
        'graded_at',
    ];

    protected $casts = [
        'graded_at' => 'datetime',
        'midterm_score' => 'float',
        'final_score' => 'float',
        'assignment_score' => 'float',
        'participation_score' => 'float',
        'practical_score' => 'float',
        'project_score' => 'float',
        'total_score' => 'float',
        'percentage' => 'float',
        'grade_point' => 'float',
        'credits_earned' => 'integer',
    ];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function grader()
    {
        return $this->belongsTo(User::class, 'graded_by');
    }
}
