<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'password',
        'role',
        'full_name',
        'email',
        'last_login',
        'is_active',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'user_subjects', 'user_id', 'subject_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'enrolled_by');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class, 'graded_by');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    public function logs()
    {
        return $this->hasMany(SystemLog::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'user';
    }

    public function isFinance(): bool
    {
        return $this->role === 'finance';
    }
}
