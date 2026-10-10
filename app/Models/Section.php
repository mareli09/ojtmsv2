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

    /**
     * Days of the week this section meets, as an array.
     * Stored comma-separated; older rows separated their days with "/".
     */
    public function daysArray(): array
    {
        return collect(preg_split('/[,\/&]+/', (string) $this->day))
            ->map(fn ($day) => trim($day))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Normalise a day selection (array or string) into the stored format.
     */
    public static function formatDays($days): string
    {
        $order = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        return collect(is_array($days) ? $days : explode(',', (string) $days))
            ->map(fn ($day) => trim($day))
            ->filter(fn ($day) => in_array($day, $order, true))
            ->unique()
            ->sortBy(fn ($day) => array_search($day, $order, true))
            ->implode(', ');
    }

    public function faculty()
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    /**
     * Get enrolled students for this section
     */
    public function students()
    {
        return $this->hasMany(User::class, 'section_id')
            ->where('role', 'student')
            ->where('deleted_at', null);
    }

    /**
     * Get all users assigned to this section (including faculty and students)
     */
    public function enrolledUsers()
    {
        return $this->hasMany(User::class, 'section_id')
            ->where('deleted_at', null);
    }

    /**
     * Check if a faculty member is already assigned to another section
     */
    public static function isFacultyAssigned($facultyId, $excludeSectionId = null)
    {
        $query = self::where('deleted_at', null)
            ->where('faculty_id', $facultyId);

        if ($excludeSectionId) {
            $query->where('id', '!=', $excludeSectionId);
        }

        return $query->exists();
    }

    /**
     * Get the section assigned to a faculty member
     */
    public static function getFacultySection($facultyId)
    {
        return self::where('deleted_at', null)
            ->where('faculty_id', $facultyId)
            ->first();
    }
}
