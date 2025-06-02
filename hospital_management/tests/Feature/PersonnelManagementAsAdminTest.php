<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Personnel;
use App\Models\HospitalForce;
use App\Models\Department;
use App\Models\PersonnelDepartmentHistory;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class PersonnelManagementAsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;
    protected HospitalForce $hospitalForce;
    protected Department $department;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('roles', include base_path('config/roles.php'));
        $this->adminUser = $this->createUserWithRole('admin');

        // Create common prerequisites
        $this->hospitalForce = HospitalForce::factory()->create(['name' => ['en' => 'Test Force', 'ar' => 'قوة اختبار']]);
        $this->department = Department::factory()->create(['name' => ['en' => 'Test Department', 'ar' => 'قسم اختبار']]);
    }

    public function test_admin_can_view_personnel_index()
    {
        Personnel::factory()->count(3)->create(['hospital_force_id' => $this->hospitalForce->id]);
        $response = $this->actingAs($this->adminUser)->get(route('admin.personnel.index'));
        $response->assertOk();
        $response->assertSee(__('app.all_personnel'));
        $response->assertViewHas('personnelList');
    }

    public function test_admin_can_view_create_personnel_page()
    {
        $response = $this->actingAs($this->adminUser)->get(route('admin.personnel.create'));
        $response->assertOk();
        $response->assertSee(__('app.create_new') . ' ' . __('app.personnel'));
        $response->assertViewHas('hospitalForces');
        $response->assertViewHas('departments');
    }

    public function test_admin_can_store_new_personnel()
    {
        $personnelData = [
            'name' => 'فرد جديد',
            'military_id' => 'MIL12345',
            'national_id' => 'NAT123456789',
            'phone_number' => '0123456789',
            'recruitment_date' => Carbon::now()->subYears(2)->toDateString(),
            'job_title' => null,
            'rank' => 'نقيب',
            'hospital_force_id' => $this->hospitalForce->id,
            'department_id' => $this->department->id, // Current department
        ];

        $response = $this->actingAs($this->adminUser)->post(route('admin.personnel.store'), $personnelData);

        $response->assertRedirect(route('admin.personnel.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('personnels', ['military_id' => 'MIL12345', 'name' => 'فرد جديد']);
        $this->assertDatabaseHas('personnel_department_histories', [
            'personnel_id' => Personnel::where('military_id', 'MIL12345')->first()->id,
            'department_id' => $this->department->id,
        ]);
    }

    public function test_store_personnel_validation_error_for_missing_name()
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.personnel.store'), ['name' => '']);
        $response->assertSessionHasErrors('name');
    }

    public function test_store_personnel_validation_error_for_missing_hospital_force()
    {
        $response = $this->actingAs($this->adminUser)->post(route('admin.personnel.store'), ['name' => 'Test', 'hospital_force_id' => null]);
        $response->assertSessionHasErrors('hospital_force_id');
    }


    public function test_admin_can_view_edit_personnel_page()
    {
        $personnel = Personnel::factory()->create(['hospital_force_id' => $this->hospitalForce->id]);
        PersonnelDepartmentHistory::factory()->create([
            'personnel_id' => $personnel->id,
            'department_id' => $this->department->id,
            'start_date' => Carbon::now()->subYear(),
            'end_date' => null
        ]);

        $response = $this->actingAs($this->adminUser)->get(route('admin.personnel.edit', $personnel));
        $response->assertOk();
        $response->assertSee($personnel->name);
        $response->assertViewHas('hospitalForces');
        $response->assertViewHas('departments');
    }

    public function test_admin_can_update_personnel()
    {
        $personnel = Personnel::factory()->create(['hospital_force_id' => $this->hospitalForce->id, 'name' => 'اسم قديم']);
        $currentDeptHistory = PersonnelDepartmentHistory::factory()->create([
            'personnel_id' => $personnel->id,
            'department_id' => $this->department->id,
            'start_date' => Carbon::now()->subYear(),
            'end_date' => null
        ]);

        $newDepartment = Department::factory()->create(['name' => ['en' => 'New Dept', 'ar' => 'قسم جديد جدا']]);

        $updatedData = [
            'name' => 'اسم محدث',
            'rank' => 'رائد',
            'hospital_force_id' => $this->hospitalForce->id, // Keep same force or change
            'department_id' => $newDepartment->id, // Change department
        ];

        $response = $this->actingAs($this->adminUser)->put(route('admin.personnel.update', $personnel), $updatedData);

        $response->assertRedirect(route('admin.personnel.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('personnels', ['id' => $personnel->id, 'name' => 'اسم محدث', 'rank' => 'رائد']);

        // Check old department history is ended
        $this->assertDatabaseHas('personnel_department_histories', [
            'id' => $currentDeptHistory->id,
            'end_date' => Carbon::now()->subSecond()->toDateTimeString(), // Approximately, due to test execution time
        ]);
         // Check new department history is created
        $this->assertDatabaseHas('personnel_department_histories', [
            'personnel_id' => $personnel->id,
            'department_id' => $newDepartment->id,
            'start_date' => Carbon::now()->toDateTimeString(), // Approximately
            'end_date' => null,
        ]);
    }

    public function test_admin_can_destroy_personnel()
    {
        $personnel = Personnel::factory()->create();
        PersonnelDepartmentHistory::factory()->create(['personnel_id' => $personnel->id]);
        // Add other related data like leaves, violations if needed to test cascade/manual delete

        $response = $this->actingAs($this->adminUser)->delete(route('admin.personnel.destroy', $personnel));

        $response->assertRedirect(route('admin.personnel.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('personnels', ['id' => $personnel->id]);
        $this->assertDatabaseMissing('personnel_department_histories', ['personnel_id' => $personnel->id]);
    }
}
