<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\Tasks\StoreTaskRequest;
use App\Http\Requests\Tasks\UpdateTaskRequest;
use App\Models\Employee;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return View
     */
    public function index(): View
    {
        $employee = auth()->user();

        if ($employee->manager_id === null) {
            // If the user is a manager, get tasks for all their employees
            $tasks = Task::where('created_by', $employee->id)->get();
        } else {
            // If the user is not a manager, get only their tasks
            $tasks = Task::where('employee_id', $employee->id)->get();
        }

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     * @return View
     */
    public function create(): View
    {
        $employees = Employee::where('manager_id', auth()->user()->id)->get();
        $statuses = TaskStatus::cases();

        return view('tasks.create', compact(['employees', 'statuses']));
    }

    /**
     * Store a newly created resource in storage.
     * @param StoreTaskRequest $request
     * @return RedirectResponse
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {
        Task::create($request->validated());

        return redirect()->route('tasks')->with('success', 'Task created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        $employees = Employee::where('manager_id', auth()->user()->id)->get();
        $statuses = TaskStatus::cases();

        return view('tasks.edit', compact(['task', 'employees', 'statuses']));
    }

    /**
     * Update the specified resource in storage.
     * @param UpdateTaskRequest $request
     * @param Task $task
     * @return RedirectResponse
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated());

        return redirect()->route('tasks')->with('success', 'Task updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     * @param Task $task
     * @return RedirectResponse
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasks')->with('success', 'Task deleted successfully!');
    }
}
