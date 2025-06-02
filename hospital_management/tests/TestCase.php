<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase; // Ensure this is used
use App\Models\User; // For helper methods
use Illuminate\Support\Facades\Hash; // For password hashing
use Illuminate\Support\Facades\Config; // For roles

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase; // Added RefreshDatabase and CreatesApplication

    /**
     * Helper method to create a user with a specific role.
     *
     * @param string $roleKey The key of the role from config/roles.php (e.g., 'admin', 'military_affairs_officer')
     * @param array $overrides Override default user attributes
     * @return \App\Models\User
     */
    protected function createUserWithRole(string $roleKey, array $overrides = []): User
    {
        $roleValue = Config::get('roles.' . $roleKey);
        if (!$roleValue) {
            // Fallback for tests if roles.php might not be loaded or key is direct value
            $roleValue = $roleKey;
        }
        // Ensure roles config is loaded for tests, if not already
        if (empty(Config::get('roles'))) {
            Config::set('roles', include base_path('config/roles.php'));
            $roleValue = Config::get('roles.' . $roleKey, $roleKey); // try getting value again
        }


        return User::factory()->create(array_merge([
            'name' => ucfirst(str_replace(['_', '-'], ' ', $roleKey)) . ' User',
            'email' => strtolower(str_replace('_', '.', $roleKey)) . '@example.com',
            'password' => Hash::make('password'),
            'role' => $roleValue,
            'email_verified_at' => now(),
        ], $overrides));
    }
}
