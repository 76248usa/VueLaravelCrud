<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $tasks = Auth::user()->tasks;

       return response()->json([
        'tasks' => $tasks
       ]);
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'body' => 'required',
        ]);

        $task = $request->user()->tasks()->create([
            'name' => $request->name,
            'body' => $request->body,
        ]);

        return response()->json([
            'task' => $task,
            'message' => 'Task created successfully',

        ]);
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //

        $request->validate([
            'name'=> 'required',
            'body'=> 'required'
        ]);

        $task = $request->user()->tasks()->whereId($id)->update($request->all());

        return response()->json([
            'task'=> $task,
            'message'=> 'task has been Updated!'
        ]);

    }

    public function destroy($id)
    {
        $task = Task::find($id)->delete();

        return response()->json([
            'task' => $task,
            'message' => 'Deleted successfully'
        ]);
    }
}
