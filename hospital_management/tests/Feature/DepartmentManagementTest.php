<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Department;
use Illuminate\Support\Facades\Config;

class DepartmentManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        // Ensure roles config is loaded for tests, and create admin user
        Config::set('roles', include base_path('config/roles.php'));
        $this->adminUser = $this->createUserWithRole('admin');
    }

    public function test_admin_can_view_departments_index()
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.departments.index'));
        $response->assertOk();
        $response->assertSee(__('app.departments')); // Check for page title or relevant text
    }

    public function test_admin_can_view_create_department_page()
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.departments.create'));
        $response->assertOk();
        $response->assertSee(__('app.create_new') . ' ' . __('app.department'));
    }

    public function test_admin_can_store_new_department()
    {
        $departmentData = [
            'name_ar' => 'قسم جديد',
            // 'name_en' => 'New Department', // If supporting multiple languages
        ];

        $response = $this->actingAs($this->adminUser)->post(route('admin.departments.store'), $departmentData);

        $response->assertRedirect(route('admin.departments.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('departments', [
            'name->ar' => 'قسم جديد',
        ]);
    }

    public function test_store_department_validation_error_for_missing_name()
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.departments.store'), ['name_ar' => '']);
        $response->assertSessionHasErrors('name_ar');
    }

    public function test_admin_can_view_edit_department_page()
    {
        $department = Department::factory()->create(['name' => ['en' => 'Old Name', 'ar' => 'اسم قديم']]);
        $response = $this->actingAs($this->adminUser)->get(route('admin.departments.edit', $department));
        $response->assertOk();
        $response->assertSee($department->getTranslation('name', 'ar'));
    }

    public function test_admin_can_update_department()
    {
        $department = Department::factory()->create(['name' => ['en' => 'Old Name', 'ar' => 'اسم قديم']]);
        $updatedData = [
            'name_ar' => 'اسم محدث',
        ];

        $response = $this->actingAs($this->adminUser)->put(route('admin.departments.update', $department), $updatedData);

        $response->assertRedirect(route('admin.departments.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('departments', [
            'id' => $department->id,
            'name->ar' => 'اسم محدث',
        ]);
    }

    public function test_update_department_validation_error_for_empty_name()
    {
        $department = Department::factory()->create(['name' => ['en' => 'Old Name', 'ar' => 'اسم قديم']]);
        $response = $this->actingAs($this->adminUser)->put(route('admin.departments.update', $department), ['name_ar' => '']);
        $response->assertSessionHasErrors('name_ar');
    }

    public function test_admin_can_destroy_department()
    {
        $department = Department::factory()->create();
        $response = $this->actingAs($this->adminUser)->delete(route('admin.departments.destroy', $department));

        $response->assertRedirect(route('admin.departments.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('departments', ['id' => $department->id]);
    }
}
