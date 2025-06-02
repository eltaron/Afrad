<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ViolationType extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
    ];

    public $translatable = [
        'name',
        'description',
    ];

    /**
     * Get the personnel violations for the violation type.
     */
    public function personnelViolations(): HasMany
    {
        return $this->hasMany(PersonnelViolation::class);
    }
}
