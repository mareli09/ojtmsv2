<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncidentReport extends Model
{
    protected $fillable = [
        'student_id',
        'type',
        'incident_date',
        'location',
        'description',
        'action_taken',
    ];

    protected $casts = [
        'incident_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
