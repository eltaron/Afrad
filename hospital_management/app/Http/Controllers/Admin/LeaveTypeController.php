<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $leaveTypes = LeaveType::latest()->paginate($request->input('per_page', 15));
        return view('admin.leave_types.index', compact('leaveTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.leave_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Later, use StoreLeaveTypeRequest
    {
        $validatedData = $request->validate([
            'name_ar' => 'required|string|max:255',
            'default_days' => 'required|integer|min:0',
            'applies_to' => 'required|in:all,military,civilian,specific_rank,specific_job_title',
            'specific_rank_or_title' => 'nullable|string|max:255',
            // No need to validate 'is_permission' if using $request->boolean()
        ]);

        $leaveType = new LeaveType();
        $leaveType->setTranslation('name', 'ar', $validatedData['name_ar']);
        $leaveType->default_days = $validatedData['default_days'];
        $leaveType->applies_to = $validatedData['applies_to'];
        $leaveType->specific_rank_or_title = $validatedData['specific_rank_or_title'] ?? null;
        $leaveType->is_permission = $request->boolean('is_permission'); // Handles 'on', 1, true, 'true' as true, others as false
        $leaveType->save();

        return redirect()->route('admin.leave-types.index')
                         ->with('success', __('app.leave_type') . ' ' . __('app.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(LeaveType $leaveType)
    {
        return redirect()->route('admin.leave-types.edit', $leaveType);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LeaveType $leaveType)
    {
        return view('admin.leave_types.edit', compact('leaveType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveType $leaveType) // Later, use UpdateLeaveTypeRequest
    {
        $validatedData = $request->validate([
            'name_ar' => 'sometimes|required|string|max:255',
            'default_days' => 'sometimes|required|integer|min:0',
            'applies_to' => 'sometimes|required|in:all,military,civilian,specific_rank,specific_job_title',
            'specific_rank_or_title' => 'nullable|string|max:255',
            // No need to validate 'is_permission' if using $request->boolean()
        ]);

        if (isset($validatedData['name_ar'])) {
            $leaveType->setTranslation('name', 'ar', $validatedData['name_ar']);
        }
        if (isset($validatedData['default_days'])) {
            $leaveType->default_days = $validatedData['default_days'];
        }
        if (isset($validatedData['applies_to'])) {
            $leaveType->applies_to = $validatedData['applies_to'];
        }
        if (array_key_exists('specific_rank_or_title', $validatedData)) { // Use array_key_exists for nullable fields
            $leaveType->specific_rank_or_title = $validatedData['specific_rank_or_title'];
        }

        // For checkboxes, if it's not in the request, it means it's unchecked (false)
        $leaveType->is_permission = $request->boolean('is_permission');

        $leaveType->save();

        return redirect()->route('admin.leave-types.index')
                         ->with('success', __('app.leave_type') . ' ' . __('app.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();
        return redirect()->route('admin.leave-types.index')
                         ->with('success', __('app.leave_type') . ' ' . __('app.deleted_successfully'));
    }
}
