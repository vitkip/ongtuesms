<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasFactory;

    protected $table = 'students';

    const UPDATED_AT = null;

    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'email',
        'phone',
        'village',
        'district',
        'province',
        'accommodation_type',
        'photo',
        'registered_at',
        'major_id',
        'academic_year_id',
        'previous_school',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'dob' => 'date',
        'registered_at' => 'datetime',
    ];

    public function major()
    {
        return $this->belongsTo(Major::class, 'major_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'student_id');
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getGenderedNameAttribute(): string
    {
        if (in_array($this->gender, ['ພຣະ', 'ສ.ນ'])) {
            return "{$this->gender} {$this->first_name} {$this->last_name}";
        }
        return "{$this->first_name} {$this->last_name}";
    }
}
