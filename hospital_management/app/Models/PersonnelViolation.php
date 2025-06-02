<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonnelViolation extends Model
{
    use HasFactory;

    protected $fillable = [
        'personnel_id',
        'violation_type_id',
        'violation_date',
        'penalty_type',
        'penalty_days',
        'leave_deduction_days',
        'notes',
    ];

    /**
     * Get the personnel associated with this violation.
     */
    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    /**
     * Get the type of violation.
     */
    public function violationType(): BelongsTo
    {
        return $this->belongsTo(ViolationType::class);
    }
}
