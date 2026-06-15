<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    protected $table = 'majors';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
    ];

    public function students()
    {
        return $this->hasMany(Student::class, 'major_id');
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'major_id');
    }

    public function curriculums()
    {
        return $this->hasMany(Curriculum::class, 'major_id');
    }
}
