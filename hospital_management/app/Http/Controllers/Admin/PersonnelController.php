<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use App\Models\HospitalForce;
use App\Models\Department;
use App\Models\User; // For User ID linkage if re-enabled
use App\Models\PersonnelDepartmentHistory;
// Removed: use App\Http\Resources\PersonnelResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // For transaction
use Carbon\Carbon;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Personnel::with(['hospitalForce', 'user', 'departmentHistory.department']);

        if ($user->isMilitaryAffairsOfficer()) {
            $militaryForceIds = HospitalForce::whereJsonContains('name->ar', 'جنود')
                                ->orWhereJsonContains('name->ar', 'صف ضباط')
                                ->pluck('id');
            $query->whereIn('hospital_force_id', $militaryForceIds);
        } elseif ($user->isCivilianAffairsOfficer()) {
            $civilianForceIds = HospitalForce::whereJsonContains('name->ar', 'مدنين')->pluck('id');
            $query->whereIn('hospital_force_id', $civilianForceIds);
        }

        $personnelList = $query->latest()->paginate($request->input('per_page', 15));
        return view('admin.personnel.index', compact('personnelList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $hospitalForces = HospitalForce::all()->pluck('name', 'id'); // Assumes name attribute is correctly returning translated name
        $departments = Department::all()->pluck('name', 'id');
        // $users = User::all()->pluck('name', 'id'); // If user linkage is needed
        return view('admin.personnel.create', compact('hospitalForces', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Later, use StorePersonnelRequest
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'military_id' => 'nullable|string|max:255|unique:personnels,military_id',
            'national_id' => 'nullable|string|max:255|unique:personnels,national_id',
            'phone_number' => 'nullable|string|max:255',
            'recruitment_date' => 'nullable|date',
            'termination_date' => 'nullable|date|after_or_equal:recruitment_date',
            'job_title' => 'nullable|string|max:255',
            'rank' => 'nullable|string|max:255',
            'hospital_force_id' => 'required|exists:hospital_forces,id',
            'department_id' => 'nullable|exists:departments,id', // For current department
            'user_id' => 'nullable|exists:users,id|unique:personnels,user_id',
        ]);

        DB::transaction(function () use ($validatedData) {
            $personnel = Personnel::create($validatedData);

            if (!empty($validatedData['department_id'])) {
                PersonnelDepartmentHistory::create([
                    'personnel_id' => $personnel->id,
                    'department_id' => $validatedData['department_id'],
                    'start_date' => $validatedData['recruitment_date'] ?? Carbon::now(), // Default to now or recruitment date
                    // end_date is null for current department
                ]);
            }
        });

        return redirect()->route('admin.personnel.index')
                         ->with('success', __('app.personnel') . ' ' . __('app.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Personnel $personnel)
    {
        $personnel->loadMissing(['hospitalForce', 'user', 'departmentHistory.department', 'violations.violationType', 'leaves.leaveType']);
        // For now, show could redirect to edit or a dedicated show view if created
        return view('admin.personnel.edit', [ // temp, should be show view
            'personnel' => $personnel,
            'hospitalForces' => HospitalForce::all()->pluck('name', 'id'),
            'departments' => Department::all()->pluck('name', 'id'),
            // 'users' => User::all()->pluck('name', 'id')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Personnel $personnel)
    {
        $hospitalForces = HospitalForce::all()->pluck('name', 'id');
        $departments = Department::all()->pluck('name', 'id');
        // $users = User::all()->pluck('name', 'id');
        $personnel->load('departmentHistory'); // Eager load history for current dept
        return view('admin.personnel.edit', compact('personnel', 'hospitalForces', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Personnel $personnel) // Later, use UpdatePersonnelRequest
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'military_id' => 'nullable|string|max:255|unique:personnels,military_id,'.$personnel->id,
            'national_id' => 'nullable|string|max:255|unique:personnels,national_id,'.$personnel->id,
            'phone_number' => 'nullable|string|max:255',
            'recruitment_date' => 'nullable|date',
            'termination_date' => 'nullable|date|after_or_equal:recruitment_date',
            'job_title' => 'nullable|string|max:255',
            'rank' => 'nullable|string|max:255',
            'hospital_force_id' => 'sometimes|required|exists:hospital_forces,id',
            'department_id' => 'nullable|exists:departments,id',
            'user_id' => 'nullable|exists:users,id|unique:personnels,user_id,'.$personnel->id,
        ]);

        DB::transaction(function () use ($validatedData, $personnel) {
            $personnel->update($validatedData);

            if ($request->has('department_id') && $validatedData['department_id'] !== null) {
                $currentDepartmentHistory = $personnel->currentDepartment()->first();
                if (!$currentDepartmentHistory || $currentDepartmentHistory->department_id != $validatedData['department_id']) {
                    // End previous department history if exists
                    if ($currentDepartmentHistory) {
                        $currentDepartmentHistory->update(['end_date' => Carbon::now()]);
                    }
                    // Create new department history entry
                    PersonnelDepartmentHistory::create([
                        'personnel_id' => $personnel->id,
                        'department_id' => $validatedData['department_id'],
                        'start_date' => Carbon::now(),
                    ]);
                }
            } elseif ($request->has('department_id') && $validatedData['department_id'] === null) {
                // If department_id is explicitly set to null (e.g. "no department")
                 $personnel->departmentHistory()->whereNull('end_date')->update(['end_date' => Carbon::now()]);
            }
        });

        return redirect()->route('admin.personnel.index')
                         ->with('success', __('app.personnel') . ' ' . __('app.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personnel $personnel)
    {
        DB::transaction(function () use ($personnel) {
            // Manually delete related records if cascadeOnDelete isn't fully trusted or specific logic needed
            $personnel->departmentHistory()->delete();
            $personnel->violations()->delete();
            $personnel->leaves()->delete();
            $personnel->delete();
        });

        return redirect()->route('admin.personnel.index')
                         ->with('success', __('app.personnel') . ' ' . __('app.deleted_successfully'));
    }
}
