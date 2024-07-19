<?php

namespace App\Http\Controllers;

use App\Http\Requests\Employees\StoreEmployeeRequest;
use App\Http\Requests\Employees\UpdateEmployeeRequest;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $employees = Employee::whereNotNull('manager_id')->get();
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $departments = Department::with('manager')->get();
        return view('employees.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreEmployeeRequest $request
     * @return RedirectResponse
     */
    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        try {
            if ($request->hasFile('image')) {
                $imageName = time().'.'.$request->image->getClientOriginalExtension();
                $request->image->storeAs('images', $imageName);

                $validatedData = $request->validated();
                $validatedData['image'] = $imageName;

                Employee::create($validatedData);
            } else {
                Employee::create($request->validated());
            }

            return redirect()->route('employees')->with('success', 'Employee created successfully.');
        } catch (\Exception $e) {
            // If there was an error, delete the image from storage
            if (isset($imageName)) {
                Storage::delete('images/' . $imageName);
            }

            return redirect()->back()->with('error', 'There was an error processing your request.');
        }
    }

    /**
     * Search for an employee.
     *
     * @param Request $request
     * @return View
     */
    public function searchEmployee(Request $request): View
    {
        $searchTerm = $request->input('search');

        $employees = Employee::query()
            ->where('first_name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
            ->orWhereHas('manager', function ($query) use ($searchTerm) {
                $query->where('first_name', 'LIKE', "%{$searchTerm}%")
                    ->orWhere('last_name', 'LIKE', "%{$searchTerm}%");
            })
            ->get();

        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Employee $employee
     * @return View
     */
    public function edit(Employee $employee): View
    {
        $departments = Department::get();
        return view('employees.edit', compact('employee', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateEmployeeRequest $request
     * @param Employee $employee
     * @return RedirectResponse
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        try {
            if ($request->hasFile('image')) {
                // Delete old image
                if ($employee->image) {
                    Storage::delete('images/' . $employee->image);
                }

                // Store new image
                $imageName = time().'.'.$request->image->getClientOriginalExtension();
                $request->image->storeAs('images', $imageName);

                $validatedData = $request->validated();
                $validatedData['image'] = $imageName;

                $employee->update($validatedData);
            } else {
                $employee->update($request->validated());
            }

            return redirect()->route('employees')->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            // If there was an error, delete the new image from storage
            if (isset($imageName)) {
                Storage::delete('images/' . $imageName);
            }

            return redirect()->back()->with('error', 'There was an error processing your request.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Employee $employee
     * @return RedirectResponse
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();
        return redirect()->route('employees')->with('success', 'Employee deleted successfully.');
    }
}
