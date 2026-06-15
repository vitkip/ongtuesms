<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Curriculum extends Model
{
    protected $table = 'curriculum';

    protected $fillable = [
        'major_id',
        'academic_year_id',
        'curriculum_name',
        'curriculum_code',
        'total_credits',
        'minimum_gpa',
        'duration_years',
        'status',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'curriculum_subjects', 'curriculum_id', 'subject_id')
            ->withPivot('id', 'subject_type', 'year_level', 'semester', 'sort_order');
    }
}
