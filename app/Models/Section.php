<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'school_year',
        'term',
        'day',
        'start_time',
        'end_time',
        'days_count',
        'room',
        'description',
        'status',
        'faculty_id',
        'capacity',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
