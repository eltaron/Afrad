<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use App\Models\HospitalForce;
use App\Models\Department;
use App\Models\User;
use App\Models\PersonnelDepartmentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule; // Required for conditional validation
use Illuminate\Support\Facades\Gate; // For authorization

class PersonnelController extends Controller
{
    private function getOfficerDomainQuery($user, $query)
    {
        if ($user->isMilitaryAffairsOfficer()) {
            $forceNames = ['جنود', 'صف ضباط']; // These should match exactly what's in DB, or use IDs/types
            $domainForceIds = HospitalForce::whereJsonContains('name->ar', $forceNames[0])
                                ->orWhereJsonContains('name->ar', $forceNames[1])
                                ->pluck('id');
            return $query->whereIn('hospital_force_id', $domainForceIds);
        } elseif ($user->isCivilianAffairsOfficer()) {
            $forceName = 'مدنين'; // Exact match
            $domainForceIds = HospitalForce::whereJsonContains('name->ar', $forceName)->pluck('id');
            return $query->whereIn('hospital_force_id', $domainForceIds);
        }
        return null; // Admin or other roles, no domain restriction from this method
    }

    private function checkPersonnelAccess(Personnel $personnel) {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return true;
        }

        $allowedForceIds = collect();
        if ($user->isMilitaryAffairsOfficer()) {
            $forceNames = ['جنود', 'صف ضباط'];
            $allowedForceIds = HospitalForce::whereJsonContains('name->ar', $forceNames[0])
                                ->orWhereJsonContains('name->ar', $forceNames[1])
                                ->pluck('id');
        } elseif ($user->isCivilianAffairsOfficer()) {
            $forceName = 'مدنين';
            $allowedForceIds = HospitalForce::whereJsonContains('name->ar', $forceName)->pluck('id');
        }

        if (!$allowedForceIds->contains($personnel->hospital_force_id)) {
            abort(403, __('app.unauthorized_action'));
        }
    }


    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Personnel::with(['hospitalForce', 'user', 'departmentHistory.department']);
        $pageTitle = __('app.all_personnel');

        if ($user->isMilitaryAffairsOfficer()) {
            $this->getOfficerDomainQuery($user, $query);
            $pageTitle = __('app.military_personnel'); // Add to lang
        } elseif ($user->isCivilianAffairsOfficer()) {
            $this->getOfficerDomainQuery($user, $query);
            $pageTitle = __('app.civilian_personnel'); // Add to lang
        }

        $personnelList = $query->latest()->paginate($request->input('per_page', 15));
        return view('admin.personnel.index', compact('personnelList', 'pageTitle'));
    }

    public function create()
    {
        $user = Auth::user();
        $hospitalForcesQuery = HospitalForce::query();

        if ($user->isMilitaryAffairsOfficer()) {
            $forceNames = ['جنود', 'صف ضباط'];
             $hospitalForcesQuery->whereJsonContains('name->ar', $forceNames[0])
                                 ->orWhereJsonContains('name->ar', $forceNames[1]);
        } elseif ($user->isCivilianAffairsOfficer()) {
            $forceName = 'مدنين';
            $hospitalForcesQuery->whereJsonContains('name->ar', $forceName);
        }
        // Admin sees all
        $hospitalForces = $hospitalForcesQuery->get()->pluck('name', 'id');
        $departments = Department::all()->pluck('name', 'id');
        return view('admin.personnel.create', compact('hospitalForces', 'departments'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'military_id' => 'nullable|string|max:255|unique:personnels,military_id',
            'national_id' => 'nullable|string|max:255|unique:personnels,national_id',
            'phone_number' => 'nullable|string|max:255',
            'recruitment_date' => 'nullable|date',
            'termination_date' => 'nullable|date|after_or_equal:recruitment_date',
            'job_title' => 'nullable|string|max:255',
            'rank' => 'nullable|string|max:255',
            'hospital_force_id' => ['required', 'exists:hospital_forces,id', function ($attribute, $value, $fail) use ($user) {
                if (!$user->isAdmin()) {
                    $allowedForceIds = collect();
                     if ($user->isMilitaryAffairsOfficer()) {
                        $forceNames = ['جنود', 'صف ضباط'];
                        $allowedForceIds = HospitalForce::whereJsonContains('name->ar', $forceNames[0])->orWhereJsonContains('name->ar', $forceNames[1])->pluck('id');
                    } elseif ($user->isCivilianAffairsOfficer()) {
                        $forceName = 'مدنين';
                        $allowedForceIds = HospitalForce::whereJsonContains('name->ar', $forceName)->pluck('id');
                    }
                    if (!$allowedForceIds->contains($value)) {
                        $fail(__('app.invalid_hospital_force_for_role')); // Add to lang
                    }
                }
            }],
            'department_id' => 'nullable|exists:departments,id',
            'user_id' => 'nullable|exists:users,id|unique:personnels,user_id',
        ]);

        DB::transaction(function () use ($validatedData) {
            $personnel = Personnel::create($validatedData);
            if (!empty($validatedData['department_id'])) {
                PersonnelDepartmentHistory::create([
                    'personnel_id' => $personnel->id,
                    'department_id' => $validatedData['department_id'],
                    'start_date' => $validatedData['recruitment_date'] ?? Carbon::now(),
                ]);
            }
        });

        return redirect()->route('admin.personnel.index')->with('success', __('app.personnel') . ' ' . __('app.created_successfully'));
    }

    public function show(Personnel $personnel)
    {
        $this->checkPersonnelAccess($personnel); // Authorization
        $personnel->loadMissing(['hospitalForce', 'user', 'departmentHistory.department', 'violations.violationType', 'leaves.leaveType']);

        $hospitalForces = HospitalForce::all()->pluck('name', 'id'); // For consistency if edit view is used for show
        $departments = Department::all()->pluck('name', 'id');

        return view('admin.personnel.edit', [ // Using edit view for show for now
            'personnel' => $personnel,
            'hospitalForces' => $hospitalForces,
            'departments' => $departments,
            'pageTitle' => __('app.personnel') . ' ' . __('app.details') // Dynamic title
        ]);
    }

    public function edit(Personnel $personnel)
    {
        $this->checkPersonnelAccess($personnel); // Authorization
        $user = Auth::user();
        $hospitalForcesQuery = HospitalForce::query();

        if ($user->isMilitaryAffairsOfficer()) {
             $forceNames = ['جنود', 'صف ضباط'];
             $hospitalForcesQuery->whereJsonContains('name->ar', $forceNames[0])
                                 ->orWhereJsonContains('name->ar', $forceNames[1]);
        } elseif ($user->isCivilianAffairsOfficer()) {
            $forceName = 'مدنين';
            $hospitalForcesQuery->whereJsonContains('name->ar', $forceName);
        }
        $hospitalForces = $hospitalForcesQuery->get()->pluck('name', 'id');
        $departments = Department::all()->pluck('name', 'id');
        $personnel->load('departmentHistory');
        return view('admin.personnel.edit', compact('personnel', 'hospitalForces', 'departments'));
    }

    public function update(Request $request, Personnel $personnel)
    {
        $this->checkPersonnelAccess($personnel); // Authorization
        $user = Auth::user();

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'military_id' => 'nullable|string|max:255|unique:personnels,military_id,'.$personnel->id,
            'national_id' => 'nullable|string|max:255|unique:personnels,national_id,'.$personnel->id,
            'phone_number' => 'nullable|string|max:255',
            'recruitment_date' => 'nullable|date',
            'termination_date' => 'nullable|date|after_or_equal:recruitment_date',
            'job_title' => 'nullable|string|max:255',
            'rank' => 'nullable|string|max:255',
            'hospital_force_id' => ['sometimes','required','exists:hospital_forces,id', function ($attribute, $value, $fail) use ($user) {
                if (!$user->isAdmin()) {
                    $allowedForceIds = collect();
                     if ($user->isMilitaryAffairsOfficer()) {
                        $forceNames = ['جنود', 'صف ضباط'];
                        $allowedForceIds = HospitalForce::whereJsonContains('name->ar', $forceNames[0])->orWhereJsonContains('name->ar', $forceNames[1])->pluck('id');
                    } elseif ($user->isCivilianAffairsOfficer()) {
                        $forceName = 'مدنين';
                        $allowedForceIds = HospitalForce::whereJsonContains('name->ar', $forceName)->pluck('id');
                    }
                    if (!$allowedForceIds->contains($value)) {
                        $fail(__('app.invalid_hospital_force_for_role'));
                    }
                }
            }],
            'department_id' => 'nullable|exists:departments,id',
            'user_id' => 'nullable|exists:users,id|unique:personnels,user_id,'.$personnel->id,
        ]);

        DB::transaction(function () use ($validatedData, $request, $personnel) {
            $personnel->update($validatedData);
            if ($request->has('department_id')) { // Check if department_id was part of the request
                $newDepartmentId = $validatedData['department_id'];
                $currentDepartmentHistory = $personnel->currentDepartment()->first();
                $currentDepartmentId = $currentDepartmentHistory ? $currentDepartmentHistory->department_id : null;

                if ($currentDepartmentId != $newDepartmentId) {
                    if ($currentDepartmentHistory) {
                        $currentDepartmentHistory->update(['end_date' => Carbon::now()->subSecond()]); // End just before new one starts
                    }
                    if ($newDepartmentId !== null) { // Only create new if a department is actually selected
                        PersonnelDepartmentHistory::create([
                            'personnel_id' => $personnel->id,
                            'department_id' => $newDepartmentId,
                            'start_date' => Carbon::now(),
                        ]);
                    }
                }
            }
        });

        return redirect()->route('admin.personnel.index')->with('success', __('app.personnel') . ' ' . __('app.updated_successfully'));
    }

    public function destroy(Personnel $personnel)
    {
        $this->checkPersonnelAccess($personnel); // Authorization
        DB::transaction(function () use ($personnel) {
            $personnel->departmentHistory()->delete();
            $personnel->violations()->delete();
            $personnel->leaves()->delete();
            $personnel->delete();
        });

        return redirect()->route('admin.personnel.index')->with('success', __('app.personnel') . ' ' . __('app.deleted_successfully'));
    }
}
