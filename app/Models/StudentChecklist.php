<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentChecklist extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'section_id',
        'student_id',
        'item',
        'student_file',
        'student_files',
        'student_clinic_name',
        'student_clinic_address',
        'student_submission_status',
        'student_encoded_at',
        'student_submitted_at',
        'student_enrolled_at',
        'student_paid_date',
        'student_receipt_number',
        'student_guardian_name',
        'student_guardian_contact',
        'student_guardian_email',
        'student_guardian_social',
        'student_endorsement_date',
        'student_start_date',
        'student_supervisor_signed_by',
        'student_remarks',
        'student_dtr_week',
        'student_dtr_hours',
        'student_dtr_validated_by',
        'student_dtr_total_hours',
        'student_weekly_week',
        'student_weekly_task_description',
        'student_weekly_supervisor_feedback',
        'student_weekly_files',
        'student_weekly_submitted_at',
        'student_appraisal_month',
        'student_appraisal_file',
        'student_appraisal_feedback',
        'student_appraisal_grade_rating',
        'student_appraisal_evaluated_by',
        'student_appraisal_submitted_at',
        'faculty_status',
        'faculty_remarks',
        'faculty_reviewed_at',
        'faculty_dtr_target_hours',
        'faculty_dtr_reviewed_at',
        'faculty_weekly_remarks',
        'faculty_weekly_reviewed_at',
        'faculty_appraisal_remarks',
        'faculty_appraisal_reviewed_at',
        'student_supervisor_eval_file',
        'student_supervisor_eval_grade',
        'student_supervisor_eval_submitted_at',
        'faculty_supervisor_eval_remarks',
        'faculty_supervisor_eval_reviewed_at',
        'student_coc_file',
        'student_coc_signed_by',
        'student_coc_company',
        'student_coc_receive_date',
        'student_coc_date_issued',
        'student_coc_submitted_at',
        'faculty_coc_remarks',
        'faculty_coc_reviewed_at',
    ];

    protected $casts = [
        'student_submitted_at' => 'datetime',
        'student_encoded_at' => 'datetime',
        'student_enrolled_at' => 'datetime',
        'student_paid_date' => 'datetime',
        'student_endorsement_date' => 'datetime',
        'student_start_date' => 'datetime',
        'student_weekly_submitted_at' => 'datetime',
        'student_appraisal_submitted_at' => 'datetime',
        'student_supervisor_eval_submitted_at' => 'datetime',
        'student_coc_submitted_at' => 'datetime',
        'student_coc_date_issued' => 'date',
        'student_coc_receive_date' => 'date',
        'faculty_coc_reviewed_at' => 'datetime',
        'faculty_reviewed_at' => 'datetime',
        'faculty_dtr_reviewed_at' => 'datetime',
        'faculty_weekly_reviewed_at' => 'datetime',
        'faculty_appraisal_reviewed_at' => 'datetime',
        'faculty_supervisor_eval_reviewed_at' => 'datetime',
        'student_files' => 'array',
        'student_weekly_files' => 'array',
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
}
