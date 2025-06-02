<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PersonnelLeave;
use App\Models\Personnel;
use App\Models\LeaveType;
use App\Models\HospitalForce; // Needed for domain filtering
use App\Http\Requests\StorePersonnelLeaveRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate; // For authorization

class PersonnelLeaveController extends Controller
{
    // Helper to get officer's domain personnel IDs
    private function getOfficerDomainPersonnelIds($user)
    {
        $forceQuery = HospitalForce::query();
        if ($user->isMilitaryAffairsOfficer()) {
            $forceNames = ['جنود', 'صف ضباط'];
            $forceQuery->whereJsonContains('name->ar', $forceNames[0])
                       ->orWhereJsonContains('name->ar', $forceNames[1]);
        } elseif ($user->isCivilianAffairsOfficer()) {
            $forceName = 'مدنين';
            $forceQuery->whereJsonContains('name->ar', $forceName);
        } else {
            return null; // Not an officer or admin (admin sees all)
        }
        $domainForceIds = $forceQuery->pluck('id');
        return Personnel::whereIn('hospital_force_id', $domainForceIds)->pluck('id');
    }

    // Authorization check for accessing/modifying a specific leave
    private functionauthorizeLeaveAccess(PersonnelLeave $personnelLeave)
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return;
        }
        $officerPersonnelIds = $this->getOfficerDomainPersonnelIds($user);
        if ($officerPersonnelIds === null || !$officerPersonnelIds->contains($personnelLeave->personnel_id)) {
            abort(403, __('app.unauthorized_action'));
        }
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = PersonnelLeave::with(['personnel.hospitalForce', 'leaveType', 'approver'])->latest();

        if (!$user->isAdmin()) {
            $officerPersonnelIds = $this->getOfficerDomainPersonnelIds($user);
            if ($officerPersonnelIds !== null) {
                $query->whereIn('personnel_id', $officerPersonnelIds);
            } else { // Should not happen if roles are set up correctly
                $query->whereRaw('1 = 0'); // No results
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
         if ($request->filled('personnel_id_filter')) { // Example additional filter
            $query->where('personnel_id', $request->input('personnel_id_filter'));
        }


        $personnelLeaves = $query->paginate($request->input('per_page', 15));
        return view('admin.personnel_leaves.index', compact('personnelLeaves'));
    }

    public function show(PersonnelLeave $personnelLeave)
    {
        $this->authorizeLeaveAccess($personnelLeave);
        $personnelLeave->loadMissing(['personnel.hospitalForce', 'personnel.departmentHistory.department', 'leaveType', 'approver']);
        return view('admin.personnel_leaves.show', compact('personnelLeave'));
    }

    public function create()
    {
        $user = Auth::user();
        $personnelQuery = Personnel::query();

        if (!$user->isAdmin()) {
            $officerPersonnelIds = $this->getOfficerDomainPersonnelIds($user);
             if ($officerPersonnelIds !== null) {
                $personnelQuery->whereIn('id', $officerPersonnelIds);
            } else {
                $personnelQuery->whereRaw('1 = 0'); // No results
            }
        }

        $personnelList = $personnelQuery->select('id', 'name', 'military_id', 'national_id')->get();
        $leaveTypes = LeaveType::all()->pluck('name', 'id');
        return view('admin.personnel_leaves.create', compact('personnelList', 'leaveTypes'));
    }

    public function store(StorePersonnelLeaveRequest $request)
    {
        $validatedData = $request->validated();
        $user = Auth::user();

        $personnel = Personnel::findOrFail($validatedData['personnel_id']);
        $leaveType = LeaveType::findOrFail($validatedData['leave_type_id']);

        // Authorization: Check if officer is creating leave for personnel in their domain
        if (!$user->isAdmin()) {
            $officerPersonnelIds = $this->getOfficerDomainPersonnelIds($user);
            if ($officerPersonnelIds === null || !$officerPersonnelIds->contains($personnel->id)) {
                 return redirect()->back()
                                 ->withErrors(['personnel_id' => __('app.unauthorized_personnel_selection')]) // Add to lang
                                 ->withInput();
            }
        }

        if (!$leaveType->isApplicable($personnel)) {
            return redirect()->back()
                             ->withErrors(['leave_type_id' => __('app.leave_type_not_applicable')])
                             ->withInput();
        }

        $startDate = Carbon::parse($validatedData['start_date']);
        $endDate = Carbon::parse($validatedData['end_date']);
        $daysTaken = $endDate->diffInDays($startDate) + 1;

        PersonnelLeave::create([
            'personnel_id' => $personnel->id,
            'leave_type_id' => $leaveType->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'days_taken' => $daysTaken,
            'status' => 'requested',
            'notes' => $validatedData['notes'] ?? null,
        ]);

        return redirect()->route('admin.personnel-leaves.index')->with('success', __('app.personnel_leave') . ' ' . __('app.created_successfully'));
    }

    public function approve(Request $request, PersonnelLeave $personnelLeave)
    {
        $this->authorizeLeaveAccess($personnelLeave);
        if ($personnelLeave->status !== 'requested') {
            return redirect()->route('admin.personnel-leaves.index')->with('error', __('app.leave_not_in_requested_state'));
        }
        $personnelLeave->status = 'approved';
        $personnelLeave->approved_by = Auth::id();
        $personnelLeave->save();
        return redirect()->route('admin.personnel-leaves.index')->with('success', __('app.leave_approved_successfully'));
    }

    public function reject(Request $request, PersonnelLeave $personnelLeave)
    {
        $this->authorizeLeaveAccess($personnelLeave);
        if ($personnelLeave->status !== 'requested') {
            return redirect()->route('admin.personnel-leaves.index')->with('error', __('app.leave_not_in_requested_state'));
        }
        $personnelLeave->status = 'rejected';
        $personnelLeave->save();
        return redirect()->route('admin.personnel-leaves.index')->with('success', __('app.leave_rejected_successfully'));
    }
}
