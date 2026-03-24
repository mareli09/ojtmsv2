<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentMOAMilestone extends Model
{
    use SoftDeletes;

    protected $table = 'student_moa_milestones';

    protected $fillable = [
        'section_id',
        'student_id',
        'milestone_number',
        'student_files',
        'student_remarks',
        'student_submitted_at',
        'faculty_status',
        'faculty_remarks',
        'faculty_reviewed_at',
        'is_final_signed',
    ];

    protected $casts = [
        'student_files' => 'array',
        'student_submitted_at' => 'datetime',
        'faculty_reviewed_at' => 'datetime',
        'is_final_signed' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public static function getOrCreateMilestone($sectionId, $studentId, $milestoneNumber = 1)
    {
        return self::firstOrCreate(
            [
                'section_id' => $sectionId,
                'student_id' => $studentId,
                'milestone_number' => $milestoneNumber,
            ],
            [
                'faculty_status' => 'pending',
                'is_final_signed' => false,
            ]
        );
    }

    public static function getLatestMilestone($sectionId, $studentId)
    {
        return self::where('section_id', $sectionId)
            ->where('student_id', $studentId)
            ->where('deleted_at', null)
            ->orderByDesc('milestone_number')
            ->first();
    }
}
