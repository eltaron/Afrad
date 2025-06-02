<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use App\Models\LeaveType;
use App\Models\PersonnelLeave;
use App\Models\PersonnelViolation;
use App\Http\Requests\PeriodReportRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Report on personnel eligible for leave today.
     */
    public function dailyEligibleForLeave(Request $request)
    {
        $activePersonnel = Personnel::where(function ($query) {
            $query->whereNull('termination_date')
                  ->orWhere('termination_date', '>', Carbon::now());
        })->with('hospitalForce')->get(); // Eager load hospitalForce for display

        $allLeaveTypes = LeaveType::all();
        $eligiblePersonnelReport = [];

        foreach ($activePersonnel as $personnel) {
            $eligibleLeaves = [];
            foreach ($allLeaveTypes as $leaveType) {
                if ($leaveType->isApplicable($personnel) && !$personnel->hasTakenLeaveRecently($leaveType)) {
                    $eligibleLeaves[] = $leaveType->name; // Name will be translated by accessor/locale
                }
            }
            if (count($eligibleLeaves) > 0) {
                $eligiblePersonnelReport[] = [
                    // Pass the whole personnel object or specific fields
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

    /**
     * Data for a specific leave permit - Renamed to leavePermitView for clarity.
     */
    public function leavePermitData(PersonnelLeave $personnelLeave)
    {
        if ($personnelLeave->status !== 'approved') {
            return redirect()->back()->with('error', __('app.leave_not_approved_for_permit')); // Add to lang
        }

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
            ? $currentDepartmentHistory->department->name // Will be translated by accessor
            : null;

        // Prepare data in a simple array for the Blade view
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

    /**
     * Report on leaves within a specified period.
     */
    public function periodLeaveReport(PeriodReportRequest $request)
    {
        $validated = $request->validated();
        $query = PersonnelLeave::with(['personnel.hospitalForce', 'leaveType', 'approver'])
            ->where(function($q) use ($validated) {
                $q->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhereBetween('end_date', [$validated['start_date'], $validated['end_date']])
                  ->orWhere(function($subq) use ($validated){
                      $subq->where('start_date', '<', $validated['start_date'])
                           ->where('end_date', '>', $validated['end_date']);
                  });
            });


        if ($request->filled('personnel_id')) {
            $query->where('personnel_id', $request->input('personnel_id'));
        }
        if ($request->filled('leave_type_id')) {
            $query->where('leave_type_id', $request->input('leave_type_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // TODO: Add role-based filtering for non-admins
        $leaves = $query->orderBy('start_date')->paginate(30); // Paginate for web view
        return view('admin.reports.period_leave_report', compact('leaves'));
    }

    /**
     * Report on violations within a specified period.
     */
    public function periodViolationReport(PeriodReportRequest $request)
    {
        $validated = $request->validated();
        $query = PersonnelViolation::with(['personnel.hospitalForce', 'violationType'])
            ->whereBetween('violation_date', [$validated['start_date'], $validated['end_date']]);

        if ($request->filled('personnel_id')) {
            $query->where('personnel_id', $request->input('personnel_id'));
        }
        if ($request->filled('violation_type_id')) {
            $query->where('violation_type_id', $request->input('violation_type_id'));
        }

        // TODO: Add role-based filtering for non-admins
        $violations = $query->orderBy('violation_date')->paginate(30); // Paginate for web view
        return view('admin.reports.period_violation_report', compact('violations'));
    }
}
