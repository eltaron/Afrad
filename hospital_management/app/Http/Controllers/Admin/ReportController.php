<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use App\Models\LeaveType;
use App\Models\PersonnelLeave;
use App\Models\PersonnelViolation;
use App\Models\HospitalForce; // Added for domain filtering
use App\Http\Requests\PeriodReportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
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
            return null; // Not an officer, or admin (admin implies no restriction from this helper)
        }
        $domainForceIds = $forceQuery->pluck('id');
        return Personnel::whereIn('hospital_force_id', $domainForceIds)->pluck('id');
    }

    // Authorization check for accessing a specific leave permit
    private function authorizeLeavePermitAccess(PersonnelLeave $personnelLeave)
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

    public function dailyEligibleForLeave(Request $request)
    {
        $user = Auth::user();
        $personnelQuery = Personnel::where(function ($query) {
            $query->whereNull('termination_date')
                  ->orWhere('termination_date', '>', Carbon::now());
        })->with('hospitalForce');

        if (!$user->isAdmin()) {
            $officerPersonnelIds = $this->getOfficerDomainPersonnelIds($user);
            if ($officerPersonnelIds !== null) {
                $personnelQuery->whereIn('id', $officerPersonnelIds);
            } else {
                 $personnelQuery->whereRaw('1 = 0'); // No results if not admin and not a recognized officer role
            }
        }
        $activePersonnel = $personnelQuery->get();
        // ... rest of the logic remains the same
        $allLeaveTypes = LeaveType::all();
        $eligiblePersonnelReport = [];

        foreach ($activePersonnel as $personnel) {
            $eligibleLeaves = [];
            foreach ($allLeaveTypes as $leaveType) {
                if ($leaveType->isApplicable($personnel) && !$personnel->hasTakenLeaveRecently($leaveType)) {
                    $eligibleLeaves[] = $leaveType->name;
                }
            }
            if (count($eligibleLeaves) > 0) {
                $eligiblePersonnelReport[] = [
                    'personnel_name' => $personnel->name,
                    'military_id' => $personnel->military_id,
                    'rank' => $personnel->rank,
                    'job_title' => $personnel->job_title,
                    'hospital_force_name' => $personnel->hospitalForce ? $personnel->hospitalForce->name : null,
                    'eligible_for_leave_types' => $eligibleLeaves,
                ];
            }
        }
        return view('admin.reports.daily_eligible_for_leave', compact('eligiblePersonnelReport'));
    }

    public function leavePermitData(PersonnelLeave $personnelLeave)
    {
        $this->authorizeLeavePermitAccess($personnelLeave); // Authorization check

        if ($personnelLeave->status !== 'approved') {
            return redirect()->back()->with('error', __('app.leave_not_approved_for_permit'));
        }
        // ... rest of the method remains the same
        $personnelLeave->load([
            'personnel.hospitalForce',
            'personnel.departmentHistory' => function ($query) {
                $query->where('start_date', '<=', Carbon::now())
                      ->where(function ($q) {
                          $q->whereNull('end_date')->orWhere('end_date', '>=', Carbon::now());
                      })->with('department');
            },
            'leaveType',
            'approver'
        ]);

        $currentDepartmentHistory = $personnelLeave->personnel->departmentHistory->first();
        $currentDepartmentName = $currentDepartmentHistory && $currentDepartmentHistory->department
            ? $currentDepartmentHistory->department->name
            : null;

        $leaveData = [
            'permit_id' => $personnelLeave->id,
            'personnel_name' => $personnelLeave->personnel->name,
            'military_id' => $personnelLeave->personnel->military_id,
            'rank' => $personnelLeave->personnel->rank,
            'job_title' => $personnelLeave->personnel->job_title,
            'hospital_force' => $personnelLeave->personnel->hospitalForce ? $personnelLeave->personnel->hospitalForce->name : null,
            'current_department' => $currentDepartmentName,
            'leave_type' => $personnelLeave->leaveType->name,
            'start_date' => $personnelLeave->start_date->format('Y-m-d'),
            'end_date' => $personnelLeave->end_date->format('Y-m-d'),
            'days_taken' => $personnelLeave->days_taken,
            'approved_by' => $personnelLeave->approver ? $personnelLeave->approver->name : null,
            'request_date' => $personnelLeave->created_at->format('Y-m-d H:i:s'),
            'notes' => $personnelLeave->notes,
        ];

        return view('admin.reports.leave_permit', compact('leaveData'));
    }

    public function periodLeaveReport(PeriodReportRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $query = PersonnelLeave::with(['personnel.hospitalForce', 'leaveType', 'approver'])
            ->where(function($q) use ($validated) {
                $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhere(function($subq) use ($validated){
                      $subq->where('start_date', '<', $validated['start_date'])
                           ->where('end_date', '>', $validated['end_date']);
                  });
            });

        if (!$user->isAdmin()) {
            $officerPersonnelIds = $this->getOfficerDomainPersonnelIds($user);
             if ($officerPersonnelIds !== null) {
                $query->whereIn('personnel_id', $officerPersonnelIds);
            } else {
                 $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('personnel_id')) {
            $query->where('personnel_id', $request->input('personnel_id'));
        }
        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->input('leave_type_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $leaves = $query->orderBy('start_date')->paginate(30);
        return view('admin.reports.period_leave_report', compact('leaves'));
    }

    public function periodViolationReport(PeriodReportRequest $request)
    {
        $validated = $request->validated();
        $user = Auth::user();
        $query = PersonnelViolation::with(['personnel.hospitalForce', 'violationType'])
            ->whereBetween('violation_date', [$validated['start_date'], $validated['end_date']]);

        if (!$user->isAdmin()) {
            $officerPersonnelIds = $this->getOfficerDomainPersonnelIds($user);
             if ($officerPersonnelIds !== null) {
                $query->whereIn('personnel_id', $officerPersonnelIds);
            } else {
                 $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('personnel_id')) {
            $query->where('personnel_id', $request->input('personnel_id'));
        }
        if ($request->filled('violation_type_id')) {
            $query->where('violation_type_id', $request->input('violation_type_id'));
        }

        $violations = $query->orderBy('violation_date')->paginate(30);
        return view('admin.reports.period_violation_report', compact('violations'));
    }
}
