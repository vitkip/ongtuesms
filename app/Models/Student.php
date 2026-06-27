<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;

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
        'qr_token',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $student) {
            if (empty($student->qr_token)) {
                $student->qr_token = Str::random(48);
            }
        });
    }

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

    /**
     * Compute ປີຮຽນ from enrollment year vs current academic year.
     * Academic year in Laos starts around June; month >= 6 → new cycle started.
     */
    public function getYearLevelAttribute(): int
    {
        if ($this->relationLoaded('academicYear') && $this->academicYear) {
            return self::calcYearLevel($this->academicYear->year);
        }
        // Fallback to stored column (raw DB value)
        return (int) ($this->attributes['year_level'] ?? 1);
    }

    public static function calcYearLevel(string $enrollmentYear): int
    {
        $enrollStart = (int) substr($enrollmentYear, 0, 4);
        $now = now();
        // Academic cycle starts in June; if before June → still in previous cycle
        $currentStart = $now->month >= 6 ? $now->year : $now->year - 1;
        return max(1, $currentStart - $enrollStart + 1);
    }
}
