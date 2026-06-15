<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicYear extends Model
{
    protected $table = 'academic_years';

    public $timestamps = false;

    protected $fillable = [
        'year',
        'status',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'academic_year_id');
    }

    public function curriculums()
    {
        return $this->hasMany(Curriculum::class, 'academic_year_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'academic_year_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'academic_year_id');
    }
}
