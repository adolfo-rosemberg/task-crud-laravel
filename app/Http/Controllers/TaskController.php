<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $tasks = Task::latest()->paginate(8);

        foreach ($tasks as $task) {
            if ($task->due_date !== null) {
                $task->due_date = Carbon::parse($task->due_date)->format('d-m-Y');
            }

            if ($task->finish_date !== null) {
                $task->finish_date = Carbon::parse($task->finish_date)->format('d-m-Y');
            } else {
                $task->finish_date = 'Sin completar';
            }
        }

        return view('index', ['tasks' => $tasks]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($request->status === null) {
            $request->merge(['status' => 'Pendiente']);
        }



        if ($request->status == 'Completada') {
            $request->merge(['finish_date' => date('Y-m-d H:i:s')]);
        }


        Task::create($request->all());
        return redirect()->route('tasks.index')->with('success', '¡Tarea creada exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        return view('edit', ['task' => $task]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($request->status === null) {
            $request->merge(['status' => 'Pendiente']);
        }

        if ($request->status === 'Pendiente' || $request->status == 'En progreso') {
            $request->merge(['finish_date' => null]);
        }

        if ($request->status == 'Completada') {
            $request->merge(['finish_date' => date('Y-m-d H:i:s')]);
        }

        $task->update($request->all());
        return redirect()->route('tasks.index')->with('success', '¡Tarea actualizada exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();
        return redirect()->route('tasks.index')->with('success', '¡Tarea eliminada exitosamente!');
    }
}
