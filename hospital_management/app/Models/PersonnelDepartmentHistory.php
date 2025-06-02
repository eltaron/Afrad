<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersonnelDepartmentHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'personnel_id',
        'department_id',
        'start_date',
        'end_date',
    ];

    /**
     * Get the personnel associated with this history record.
     */
    public function personnel(): BelongsTo
    {
        return $this->belongsTo(Personnel::class);
    }

    /**
     * Get the department associated with this history record.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
