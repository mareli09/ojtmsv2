<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    protected $fillable = [
        'student_id',
        'section_id',
        'type',
        'incident_date',
        'location',
        'description',
        'action_taken',
        'attachment',
        'faculty_status',
        'faculty_remarks',
        'faculty_reviewed_at',
    ];

    protected $casts = [
        'incident_date'       => 'date',
        'faculty_reviewed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
