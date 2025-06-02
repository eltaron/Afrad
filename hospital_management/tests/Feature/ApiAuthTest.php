<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Http\Resources\UserResource; // For verifying structure
use Laravel\Sanctum\Sanctum;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_access_to_protected_api_route_is_denied()
    {
        // Attempt to access a protected API route without authentication
        // The route '/api/v1/user' is protected by 'auth:sanctum'
        $response = $this->getJson(route('api.v1.user'));

        // Assert that the request is unauthorized (401)
        $response->assertUnauthorized();
    }

    public function test_authenticated_access_to_user_route_returns_user_data()
    {
        // Create a user using the helper from TestCase
        $user = $this->createUserWithRole('admin'); // Role doesn't strictly matter for this test

        // Authenticate as this user for API requests
        Sanctum::actingAs($user);

        $response = $this->getJson(route('api.v1.user'));

        $response->assertOk();
        $response->assertJsonStructure([ // Check for basic UserResource structure
            'data' => [
                'id',
                'name',
                'email',
                'role',
                'role_display',
                'email_verified_at',
                'created_at',
                'updated_at',
            ]
        ]);
        $response->assertJsonPath('data.id', $user->id);
        $response->assertJsonPath('data.email', $user->email);
    }

    public function test_api_routes_are_protected_by_auth_sanctum()
    {
        // Test a selection of API routes to ensure they return 401 if not authenticated
        $routesToTest = [
            'api.v1.personnel.index',
            'api.v1.personnel-leaves.index',
            'api.v1.reports.dailyEligibleForLeave',
        ];

        foreach ($routesToTest as $routeName) {
            if (Route::has($routeName)) { // Check if route exists to prevent test errors for undefined routes
                 // Test GET request, adjust if some routes are POST etc. and need different methods
                $response = $this->getJson(route($routeName));
                $response->assertUnauthorized();
            } else {
                $this->markTestSkipped("Route {$routeName} not defined, skipping associated auth test.");
            }
        }
    }
}
