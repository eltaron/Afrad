<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ViolationType;
use Illuminate\Http\Request;
// Removed: use App\Http\Resources\ViolationTypeResource;

class ViolationTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $violationTypes = ViolationType::latest()->paginate($request->input('per_page', 15));
        return view('admin.violation_types.index', compact('violationTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.violation_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Later, use StoreViolationTypeRequest
    {
        $validatedData = $request->validate([
            'name_ar' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
        ]);

        $violationType = new ViolationType();
        $violationType->setTranslation('name', 'ar', $validatedData['name_ar']);
        if (isset($validatedData['description_ar'])) {
            $violationType->setTranslation('description', 'ar', $validatedData['description_ar']);
        }
        $violationType->save();

        return redirect()->route('admin.violation-types.index')
                         ->with('success', __('app.violation_type') . ' ' . __('app.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(ViolationType $violationType)
    {
        // return view('admin.violation_types.show', compact('violationType'));
         return redirect()->route('admin.violation-types.edit', $violationType);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ViolationType $violationType)
    {
        return view('admin.violation_types.edit', compact('violationType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ViolationType $violationType) // Later, use UpdateViolationTypeRequest
    {
        $validatedData = $request->validate([
            'name_ar' => 'sometimes|required|string|max:255',
            'description_ar' => 'nullable|string',
        ]);

        if (isset($validatedData['name_ar'])) {
            $violationType->setTranslation('name', 'ar', $validatedData['name_ar']);
        }
        if (array_key_exists('description_ar', $validatedData)) {
            $violationType->setTranslation('description', 'ar', $validatedData['description_ar']);
        } else {
            // If it's not in the request, it means we might want to set it to null
            // or ensure it's not accidentally cleared if other locales exist.
            // For only 'ar', this effectively clears it if not provided.
            $violationType->setTranslation('description', 'ar', null);
        }
        $violationType->save();

        return redirect()->route('admin.violation-types.index')
                         ->with('success', __('app.violation_type') . ' ' . __('app.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ViolationType $violationType)
    {
        $violationType->delete();
        return redirect()->route('admin.violation-types.index')
                         ->with('success', __('app.violation_type') . ' ' . __('app.deleted_successfully'));
    }
}
