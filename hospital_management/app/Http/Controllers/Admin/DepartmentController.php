<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
// Removed: use App\Http\Resources\DepartmentResource;
// Removed: use Illuminate\Support\Facades\App; (if not used elsewhere)

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $departments = Department::latest()->paginate(15);
        return view('admin.departments.index', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.departments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Later, use StoreDepartmentRequest
    {
        $validatedData = $request->validate([
            'name_ar' => 'required|string|max:255',
            // 'name_en' => 'nullable|string|max:255',
        ]);

        $department = new Department();
        $department->setTranslation('name', 'ar', $validatedData['name_ar']);
        // if (isset($validatedData['name_en'])) {
        //     $department->setTranslation('name', 'en', $validatedData['name_en']);
        // }
        $department->save();

        return redirect()->route('admin.departments.index')
                         ->with('success', __('app.department') . ' ' . __('app.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        // return view('admin.departments.show', compact('department'));
        return redirect()->route('admin.departments.edit', $department);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department) // Later, use UpdateDepartmentRequest
    {
        $validatedData = $request->validate([
            'name_ar' => 'sometimes|required|string|max:255',
            // 'name_en' => 'nullable|string|max:255',
        ]);

        if (isset($validatedData['name_ar'])) {
            $department->setTranslation('name', 'ar', $validatedData['name_ar']);
        }
        // if (isset($validatedData['name_en'])) {
        //     $department->setTranslation('name', 'en', $validatedData['name_en']);
        // }
        $department->save();

        return redirect()->route('admin.departments.index')
                         ->with('success', __('app.department') . ' ' . __('app.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('admin.departments.index')
                         ->with('success', __('app.department') . ' ' . __('app.deleted_successfully'));
    }
}
