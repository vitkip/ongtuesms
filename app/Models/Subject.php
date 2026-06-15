<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'subject_code',
        'subject_name',
        'subject_name_en',
        'credits',
        'theory_hours',
        'practical_hours',
        'major_id',
        'semester',
        'year_level',
        'prerequisite_subject_id',
        'description',
        'learning_outcomes',
        'status',
    ];

    public function teachers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'user_subjects', 'subject_id', 'user_id');
    }

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function prerequisite()
    {
        return $this->belongsTo(Subject::class, 'prerequisite_subject_id');
    }

    public function dependentSubjects()
    {
        return $this->hasMany(Subject::class, 'prerequisite_subject_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'subject_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'subject_id');
    }

    public function curriculums()
    {
        return $this->belongsToMany(Curriculum::class, 'curriculum_subjects', 'subject_id', 'curriculum_id')
            ->withPivot('id', 'subject_type', 'year_level', 'semester', 'sort_order');
    }
}
