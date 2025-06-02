<?php

namespace Tests\Unit;

use Tests\TestCase; // Ensure this points to your base TestCase in the Tests directory
use App\Models\LeaveType;
use App\Models\Personnel;
use App\Models\HospitalForce;
use Illuminate\Foundation\Testing\RefreshDatabase; // Good for model tests that might touch DB or use factories

class LeaveTypeTest extends TestCase
{
    use RefreshDatabase;

    protected HospitalForce $militaryForce1;
    protected HospitalForce $militaryForce2;
    protected HospitalForce $civilianForce;

    protected function setUp(): void
    {
        parent::setUp();
        // It's good practice to ensure configs are loaded if your models rely on them,
        // though for unit tests directly testing model logic, it might not always be needed
        // if you mock dependencies or pass direct values.
        // Config::set('roles', include base_path('config/roles.php'));

        $this->militaryForce1 = HospitalForce::factory()->create(['name' => ['en' => 'Soldiers', 'ar' => 'جنود']]);
        $this->militaryForce2 = HospitalForce::factory()->create(['name' => ['en' => 'Sergeants', 'ar' => 'صف ضباط']]);
        $this->civilianForce = HospitalForce::factory()->create(['name' => ['en' => 'Civilians', 'ar' => 'مدنين']]);
    }

    public function test_leave_type_applies_to_all()
    {
        $leaveType = LeaveType::factory()->create(['applies_to' => 'all']);
        $personnel = Personnel::factory()->make(); // Using make to not hit DB, pass necessary attributes

        $this->assertTrue($leaveType->isApplicable($personnel));
    }

    public function test_leave_type_applies_to_military()
    {
        $leaveType = LeaveType::factory()->create(['applies_to' => 'military']);

        $militaryPersonnel1 = Personnel::factory()->make(['hospital_force_id' => $this->militaryForce1->id]);
        $militaryPersonnel2 = Personnel::factory()->make(['hospital_force_id' => $this->militaryForce2->id]);
        $civilianPersonnel = Personnel::factory()->make(['hospital_force_id' => $this->civilianForce->id]);

        $this->assertTrue($leaveType->isApplicable($militaryPersonnel1));
        $this->assertTrue($leaveType->isApplicable($militaryPersonnel2));
        $this->assertFalse($leaveType->isApplicable($civilianPersonnel));
    }

    public function test_leave_type_applies_to_civilian()
    {
        $leaveType = LeaveType::factory()->create(['applies_to' => 'civilian']);

        $militaryPersonnel = Personnel::factory()->make(['hospital_force_id' => $this->militaryForce1->id]);
        $civilianPersonnel = Personnel::factory()->make(['hospital_force_id' => $this->civilianForce->id]);

        $this->assertFalse($leaveType->isApplicable($militaryPersonnel));
        $this->assertTrue($leaveType->isApplicable($civilianPersonnel));
    }

    public function test_leave_type_applies_to_specific_rank()
    {
        $specificRank = 'نقيب';
        $leaveType = LeaveType::factory()->create([
            'applies_to' => 'specific_rank',
            'specific_rank_or_title' => $specificRank,
        ]);

        $personnelWithRank = Personnel::factory()->make(['rank' => $specificRank]);
        $personnelWithoutRank = Personnel::factory()->make(['rank' => 'رائد']);

        $this->assertTrue($leaveType->isApplicable($personnelWithRank));
        $this->assertFalse($leaveType->isApplicable($personnelWithoutRank));
    }

    public function test_leave_type_applies_to_specific_job_title()
    {
        $specificTitle = 'مهندس برمجيات';
        $leaveType = LeaveType::factory()->create([
            'applies_to' => 'specific_job_title',
            'specific_rank_or_title' => $specificTitle,
        ]);

        $personnelWithTitle = Personnel::factory()->make(['job_title' => $specificTitle]);
        $personnelWithoutTitle = Personnel::factory()->make(['job_title' => 'محاسب']);

        $this->assertTrue($leaveType->isApplicable($personnelWithTitle));
        $this->assertFalse($leaveType->isApplicable($personnelWithoutTitle));
    }
}
