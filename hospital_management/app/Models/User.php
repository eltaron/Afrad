<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Config; // Added for role constants
use Laravel\Sanctum\HasApiTokens; // Import HasApiTokens

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens; // Add HasApiTokens trait

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if the user has the admin role.
     */
    public function isAdmin(): bool
    {
        return $this->role === Config::get('roles.admin');
    }

    /**
     * Check if the user has the military affairs officer role.
     */
    public function isMilitaryAffairsOfficer(): bool
    {
        return $this->role === Config::get('roles.military_affairs_officer');
    }

    /**
     * Check if the user has the civilian affairs officer role.
     */
    public function isCivilianAffairsOfficer(): bool
    {
        return $this->role === Config::get('roles.civilian_affairs_officer');
    }
}
