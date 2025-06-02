<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LeaveType extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'default_days',
        'applies_to',
        'specific_rank_or_title',
        'is_permission',
    ];

    public $translatable = [
        'name',
    ];

    protected $casts = [
        'is_permission' => 'boolean',
    ];

    /**
     * Get the personnel leaves for the leave type.
     */
    public function personnelLeaves(): HasMany
    {
        return $this->hasMany(PersonnelLeave::class);
    }

    /**
     * Check if the leave type is applicable to the given personnel.
     *
     * @param Personnel $personnel
     * @return bool
     */
    public function isApplicable(Personnel $personnel): bool
    {
        switch ($this->applies_to) {
            case 'all':
                return true;
            case 'military':
                // This assumes HospitalForce model has a way to distinguish military forces
                // For example, by checking its name or a hypothetical 'type' attribute.
                // This logic would need refinement based on actual HospitalForce data structure.
                $militaryForceNames = ['جنود', 'صف ضباط']; // Example names
                return in_array($personnel->hospitalForce->getTranslation('name', 'ar'), $militaryForceNames);
            case 'civilian':
                // Similar assumption for civilian forces
                return $personnel->hospitalForce->getTranslation('name', 'ar') === 'مدنين'; // Example name
            case 'specific_rank':
                return $personnel->rank === $this->specific_rank_or_title;
            case 'specific_job_title':
                return $personnel->job_title === $this->specific_rank_or_title;
            default:
                return false;
        }
    }
}
