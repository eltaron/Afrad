<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
    ];

    public $translatable = [
        'name',
    ];

    /**
     * Get the personnel department history for the department.
     */
    public function personnelDepartmentHistory(): HasMany
    {
        return $this->hasMany(PersonnelDepartmentHistory::class);
    }
}
