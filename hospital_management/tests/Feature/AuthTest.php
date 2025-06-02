<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Config; // For accessing roles config

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure roles config is loaded for tests
        Config::set('roles', include base_path('config/roles.php'));
    }

    public function test_guest_is_redirected_from_admin_route()
    {
        // Attempt to access an admin-only route (e.g., admin dashboard or a specific admin resource)
        // Assuming 'admin.departments.index' is protected by 'auth' and 'role:admin' middleware
        $response = $this->get(route('admin.departments.index'));

        // Assert that the guest is redirected to the login page
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_user_cannot_access_admin_route()
    {
        // Create a user with a non-admin role (e.g., military_affairs_officer)
        $officer = $this->createUserWithRole('military_affairs_officer');

        // Act as this user and attempt to access an admin-only route
        $response = $this->actingAs($officer)->get(route('admin.departments.index'));

        // Assert that the user is forbidden (403) or redirected
        // If redirection is to home with an error, that's also a valid outcome depending on middleware
        $response->assertStatus(403); // Or $response->assertRedirect('/'); and check for session error
    }

    public function test_admin_user_can_access_admin_route()
    {
        $admin = $this->createUserWithRole('admin');
        $response = $this->actingAs($admin)->get(route('admin.departments.index'));
        $response->assertOk();
    }

    public function test_officer_cannot_access_other_specific_admin_management_routes()
    {
        // Example: Military officer trying to access a hypothetical user management section if it were admin-only
        $militaryOfficer = $this->createUserWithRole('military_affairs_officer');

        // Assuming 'admin.users.index' is a route only for super-admins (not yet created, but for test structure)
        // For now, let's use a high-level admin resource they shouldn't manage directly, e.g. HospitalForces
        // if their role is not supposed to manage that.

        // If military_affairs_officer is NOT supposed to manage HospitalForces:
        $response = $this->actingAs($militaryOfficer)->get(route('admin.hospital-forces.index'));
        $response->assertStatus(403); // Expect forbidden if this route is admin-only

        // If military_affairs_officer IS allowed to see some personnel but not others (tested in PersonnelManagementAsOfficerTest)
        // This test focuses on general route access based on the role middleware.
    }

    public function test_civilian_officer_cannot_access_other_specific_admin_management_routes()
    {
        $civilianOfficer = $this->createUserWithRole('civilian_affairs_officer');

        // Example: Civilian officer trying to access HospitalForces management
        $response = $this->actingAs($civilianOfficer)->get(route('admin.hospital-forces.index'));
        $response->assertStatus(403); // Expect forbidden if this route is admin-only
    }
}
