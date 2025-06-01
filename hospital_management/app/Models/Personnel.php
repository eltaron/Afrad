<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon; // For date calculations

class Personnel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'military_id',
        'national_id',
        'phone_number',
        'recruitment_date',
        'termination_date',
        'job_title',
        'rank',
        'hospital_force_id',
        'user_id',
    ];

    protected $casts = [
        'recruitment_date' => 'date',
        'termination_date' => 'date',
    ];

    /**
     * Get the hospital force that the personnel belongs to.
     */
    public function hospitalForce(): BelongsTo
    {
        return $this->belongsTo(HospitalForce::class);
    }

    /**
     * Get the user account associated with the personnel (if any).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department history for the personnel.
     */
    public function departmentHistory(): HasMany
    {
        return $this->hasMany(PersonnelDepartmentHistory::class)->orderBy('start_date', 'desc');
    }

    /**
     * Get the violations for the personnel.
     */
    public function violations(): HasMany
    {
        return $this->hasMany(PersonnelViolation::class);
    }

    /**
     * Get the leaves for the personnel.
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(PersonnelLeave::class);
    }

    /**
     * Check if the personnel has taken a specific type of leave recently.
     */
    public function hasTakenLeaveRecently(LeaveType $leaveType, int $days = 30): bool
    {
        return $this->leaves()
            ->where('leave_type_id', $leaveType->id)
            ->where('start_date', '>=', Carbon::now()->subDays($days))
            ->whereIn('status', ['approved', 'taken']) // Consider approved or already taken leaves
            ->exists();
    }

    /**
     * Get the current department of the personnel.
     */
    public function currentDepartment()
    {
        return $this->departmentHistory()
            ->where('start_date', '<=', Carbon::now())
            ->where(function ($query) {
                $query->whereNull('end_date')
                      ->orWhere('end_date', '>=', Carbon::now());
            })
            ->first(); // Returns a PersonnelDepartmentHistory model
    }

    // Accessor for the current department model itself (if needed)
    public function getCurrentDepartmentAttribute()
    {
        $history = $this->currentDepartment();
        return $history ? $history->department : null;
    }
}
