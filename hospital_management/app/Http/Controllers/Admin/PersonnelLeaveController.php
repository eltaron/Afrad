<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PersonnelLeave;
use App\Models\Personnel;
use App\Models\LeaveType;
use App\Http\Requests\StorePersonnelLeaveRequest;
// Removed: use App\Http\Resources\PersonnelLeaveResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PersonnelLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Basic filtering example, can be expanded
        $query = PersonnelLeave::with(['personnel', 'leaveType', 'approver'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        // TODO: Add filtering for personnel (e.g., based on officer's scope)

        $personnelLeaves = $query->paginate($request->input('per_page', 15));
        return view('admin.personnel_leaves.index', compact('personnelLeaves'));
    }

    /**
     * Display the specified resource.
     */
    public function show(PersonnelLeave $personnelLeave)
    {
        $personnelLeave->loadMissing(['personnel.hospitalForce', 'personnel.departmentHistory.department', 'leaveType', 'approver']);
        return view('admin.personnel_leaves.show', compact('personnelLeave'));
    }

    /**
     * Show the form for creating a new leave request.
     */
    public function create()
    {
        $personnelList = Personnel::select('id', 'name', 'military_id', 'national_id')->get(); // Optimize data fetched
        $leaveTypes = LeaveType::all()->pluck('name', 'id'); // Assumes name attribute is correctly returning translated name
        return view('admin.personnel_leaves.create', compact('personnelList', 'leaveTypes'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePersonnelLeaveRequest $request)
    {
        $validatedData = $request->validated();

        $personnel = Personnel::findOrFail($validatedData['personnel_id']);
        $leaveType = LeaveType::findOrFail($validatedData['leave_type_id']);

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
            'status' => 'requested', // Default status
            'notes' => $validatedData['notes'] ?? null,
            // 'approved_by' will be set upon approval if a non-admin user creates it and it's auto-approved based on some rule
            // For admin creating on behalf, it might be directly set to 'approved' or 'taken'
        ]);

        return redirect()->route('admin.personnel-leaves.index')
                         ->with('success', __('app.personnel_leave') . ' ' . __('app.created_successfully'));
    }

    /**
     * Approve the specified leave request.
     */
    public function approve(Request $request, PersonnelLeave $personnelLeave)
    {
        if ($personnelLeave->status !== 'requested') {
            return redirect()->route('admin.personnel-leaves.index')
                             ->with('error', __('app.leave_not_in_requested_state'));
        }

        $personnelLeave->status = 'approved';
        $personnelLeave->approved_by = Auth::id();
        $personnelLeave->save();

        return redirect()->route('admin.personnel-leaves.index')
                         ->with('success', __('app.leave_approved_successfully'));
    }

    /**
     * Reject the specified leave request.
     */
    public function reject(Request $request, PersonnelLeave $personnelLeave)
    {
        if ($personnelLeave->status !== 'requested') {
            return redirect()->route('admin.personnel-leaves.index')
                             ->with('error', __('app.leave_not_in_requested_state'));
        }

        // Optionally, add a reason for rejection from $request->input('rejection_reason')
        // $personnelLeave->notes = ($personnelLeave->notes ? $personnelLeave->notes . "\n" : '') . "Rejected: " . $request->input('rejection_reason');

        $personnelLeave->status = 'rejected';
        $personnelLeave->save();

        return redirect()->route('admin.personnel-leaves.index')
                         ->with('success', __('app.leave_rejected_successfully'));
    }
}
