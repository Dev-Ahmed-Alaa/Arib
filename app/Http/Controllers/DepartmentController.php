<?php

namespace App\Http\Controllers;

use App\Http\Requests\Departments\StoreDepartmentRequest;
use App\Http\Requests\Departments\UpdateDepartmentRequest;
use App\Models\Department;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return View
     */
    public function index(): View
    {
        $departments = Department::get();
        return view('departments.index', compact('departments'));
    }

    /**
     * Search for a department by name.
     * @param Request $request
     * @return View
     */
    public function searchDepartment(Request $request): View
    {
        $search = $request->get('search');
        $departments = Department::where('name', 'like', '%' . $search . '%')
            ->withCount('employees')
            ->withSum('employees', 'salary')
            ->get();

        return view('departments.search', compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     */
    public function create(): View
    {
        return view('departments.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreDepartmentRequest $request
     * @return RedirectResponse
     */
    public function store(StoreDepartmentRequest $request): RedirectResponse
    {
        Department::create($request->validated());
        return redirect()->route('departments')->with('success', 'Department created successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     * @param Department $department
     * @return View
     */
    public function edit(Department $department): View
    {
        return view('departments.edit', compact('department'));
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateDepartmentRequest $request
     * @param Department $department
     * @return RedirectResponse
     */
    public function update(UpdateDepartmentRequest $request, Department $department): RedirectResponse
    {
        $department->update($request->validated());
        return redirect()->route('departments')->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     * @param Department $department
     * @return RedirectResponse
     */
    public function destroy(Department $department): RedirectResponse
    {
        if ($department->employees()->exists()) {
            return redirect()->back()->with('error', 'Department cannot be deleted because it has employees assigned to it.');
        }

        $department->delete();

        return redirect()->route('departments')->with('success', 'Department deleted successfully.');
    }
}
