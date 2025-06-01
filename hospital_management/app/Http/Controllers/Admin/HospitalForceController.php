<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HospitalForce;
use Illuminate\Http\Request;
// No need for App facade if we use app()->getLocale() or rely on middleware for locale
// No need for HospitalForceResource if returning views

class HospitalForceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Paginate for web view as well, if desired
        $hospitalForces = HospitalForce::latest()->paginate(15);
        return view('admin.hospital_forces.index', compact('hospitalForces'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hospital_forces.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Later, replace Request with StoreHospitalForceRequest
    {
        $validatedData = $request->validate([
            'name_ar' => 'required|string|max:255',
            // 'name_en' => 'nullable|string|max:255',
        ]);

        $hospitalForce = new HospitalForce();
        // Set translation for Arabic
        $hospitalForce->setTranslation('name', 'ar', $validatedData['name_ar']);

        // If other languages are submitted, set them too
        // if (!empty($validatedData['name_en'])) {
        //     $hospitalForce->setTranslation('name', 'en', $validatedData['name_en']);
        // }
        $hospitalForce->save();

        return redirect()->route('admin.hospital-forces.index')
                         ->with('success', __('app.hospital_force') . ' ' . __('app.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(HospitalForce $hospitalForce)
    {
        // Optional: Create a show.blade.php or redirect to index/edit
        // For now, redirecting to edit for simplicity or just show index
        // return view('admin.hospital_forces.show', compact('hospitalForce'));
        return redirect()->route('admin.hospital-forces.edit', $hospitalForce);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HospitalForce $hospitalForce)
    {
        return view('admin.hospital_forces.edit', compact('hospitalForce'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HospitalForce $hospitalForce) // Later, use UpdateHospitalForceRequest
    {
        $validatedData = $request->validate([
            'name_ar' => 'sometimes|required|string|max:255',
            // 'name_en' => 'nullable|string|max:255',
        ]);

        if (isset($validatedData['name_ar'])) {
            $hospitalForce->setTranslation('name', 'ar', $validatedData['name_ar']);
        }
        // if (isset($validatedData['name_en'])) {
        //    $hospitalForce->setTranslation('name', 'en', $validatedData['name_en']);
        // }
        $hospitalForce->save();

        return redirect()->route('admin.hospital-forces.index')
                         ->with('success', __('app.hospital_force') . ' ' . __('app.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HospitalForce $hospitalForce)
    {
        $hospitalForce->delete();
        return redirect()->route('admin.hospital-forces.index')
                         ->with('success', __('app.hospital_force') . ' ' . __('app.deleted_successfully'));
    }
}
