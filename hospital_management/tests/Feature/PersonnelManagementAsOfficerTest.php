<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Personnel;
use App\Models\HospitalForce;
use App\Models\Department;
use Illuminate\Support\Facades\Config;

class PersonnelManagementAsOfficerTest extends TestCase
{
    use RefreshDatabase;

    protected User $militaryOfficer;
    protected User $civilianOfficer;
    protected HospitalForce $hfMilitary1;
    protected HospitalForce $hfMilitary2;
    protected HospitalForce $hfCivilian;
    protected Department $dept1;
    protected Personnel $p1Military;
    protected Personnel $p2Civilian;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('roles', include base_path('config/roles.php'));

        $this->militaryOfficer = $this->createUserWithRole('military_affairs_officer');
        $this->civilianOfficer = $this->createUserWithRole('civilian_affairs_officer');

        $this->hfMilitary1 = HospitalForce::factory()->create(['name' => ['en' => 'Soldiers', 'ar' => 'جنود']]);
        $this->hfMilitary2 = HospitalForce::factory()->create(['name' => ['en' => 'Sergeants', 'ar' => 'صف ضباط']]);
        $this->hfCivilian = HospitalForce::factory()->create(['name' => ['en' => 'Civilians', 'ar' => 'مدنين']]);

        $this->dept1 = Department::factory()->create(['name' => ['en' => 'General Dept', 'ar' => 'قسم عام']]);

        $this->p1Military = Personnel::factory()->create([
            'name' => 'جندي أول',
            'hospital_force_id' => $this->hfMilitary1->id,
            'military_id' => 'MIL001'
        ]);
        $this->p2Civilian = Personnel::factory()->create([
            'name' => 'موظف مدني',
            'hospital_force_id' => $this->hfCivilian->id,
            'national_id' => 'CIV001'
        ]);
    }

    // --- Military Officer Tests ---

    public function test_military_officer_sees_only_military_personnel_on_index()
    {
        $response = $this->actingAs($this->militaryOfficer)->get(route('admin.personnel.index'));
        $response->assertOk();
        $response->assertViewHas('personnelList', function ($personnelList) {
            return $personnelList->contains($this->p1Military) && !$personnelList->contains($this->p2Civilian);
        });
        $response->assertSee(__('app.military_personnel'));
    }

    public function test_military_officer_sees_correct_hospital_forces_on_create_personnel()
    {
        $response = $this->actingAs($this->militaryOfficer)->get(route('admin.personnel.create'));
        $response->assertOk();
        $response->assertViewHas('hospitalForces', function ($hospitalForces) {
            return $hospitalForces->has($this->hfMilitary1->id) &&
                   $hospitalForces->has($this->hfMilitary2->id) &&
                   !$hospitalForces->has($this->hfCivilian->id);
        });
    }

    public function test_military_officer_can_store_military_personnel()
    {
        $data = [
            'name' => 'جندي جديد',
            'military_id' => 'MIL003',
            'hospital_force_id' => $this->hfMilitary1->id,
            'department_id' => $this->dept1->id,
            'rank' => 'جندي'
        ];
        $response = $this->actingAs($this->militaryOfficer)->post(route('admin.personnel.store'), $data);
        $response->assertRedirect(route('admin.personnel.index'));
        $this->assertDatabaseHas('personnels', ['military_id' => 'MIL003']);
    }

    public function test_military_officer_cannot_store_personnel_in_civilian_force()
    {
        $data = ['name' => 'محاولة خاطئة', 'hospital_force_id' => $this->hfCivilian->id, 'national_id' => 'CIV003'];
        $response = $this->actingAs($this->militaryOfficer)->post(route('admin.personnel.store'), $data);
        $response->assertSessionHasErrors('hospital_force_id');
    }

    public function test_military_officer_can_edit_military_personnel()
    {
        $response = $this->actingAs($this->militaryOfficer)->get(route('admin.personnel.edit', $this->p1Military));
        $response->assertOk();
    }

    public function test_military_officer_cannot_edit_civilian_personnel()
    {
        $response = $this->actingAs($this->militaryOfficer)->get(route('admin.personnel.edit', $this->p2Civilian));
        $response->assertStatus(403);
    }

    // --- Civilian Officer Tests ---

    public function test_civilian_officer_sees_only_civilian_personnel_on_index()
    {
        $response = $this->actingAs($this->civilianOfficer)->get(route('admin.personnel.index'));
        $response->assertOk();
        $response->assertViewHas('personnelList', function ($personnelList) {
            return !$personnelList->contains($this->p1Military) && $personnelList->contains($this->p2Civilian);
        });
        $response->assertSee(__('app.civilian_personnel'));
    }

    public function test_civilian_officer_sees_correct_hospital_forces_on_create_personnel()
    {
        $response = $this->actingAs($this->civilianOfficer)->get(route('admin.personnel.create'));
        $response->assertOk();
        $response->assertViewHas('hospitalForces', function ($hospitalForces) {
            return !$hospitalForces->has($this->hfMilitary1->id) &&
                   !$hospitalForces->has($this->hfMilitary2->id) &&
                   $hospitalForces->has($this->hfCivilian->id);
        });
    }

    public function test_civilian_officer_can_store_civilian_personnel()
    {
        $data = [
            'name' => 'مدني جديد',
            'national_id' => 'CIV004',
            'hospital_force_id' => $this->hfCivilian->id,
            'department_id' => $this->dept1->id,
            'job_title' => 'كاتب'
        ];
        $response = $this->actingAs($this->civilianOfficer)->post(route('admin.personnel.store'), $data);
        $response->assertRedirect(route('admin.personnel.index'));
        $this->assertDatabaseHas('personnels', ['national_id' => 'CIV004']);
    }

    public function test_civilian_officer_cannot_store_personnel_in_military_force()
    {
        $data = ['name' => 'محاولة خاطئة 2', 'hospital_force_id' => $this->hfMilitary1->id, 'military_id' => 'MIL004'];
        $response = $this->actingAs($this->civilianOfficer)->post(route('admin.personnel.store'), $data);
        $response->assertSessionHasErrors('hospital_force_id');
    }

    public function test_civilian_officer_can_edit_civilian_personnel()
    {
        $response = $this->actingAs($this->civilianOfficer)->get(route('admin.personnel.edit', $this->p2Civilian));
        $response->assertOk();
    }

    public function test_civilian_officer_cannot_edit_military_personnel()
    {
        $response = $this->actingAs($this->civilianOfficer)->get(route('admin.personnel.edit', $this->p1Military));
        $response->assertStatus(403);
    }
}
