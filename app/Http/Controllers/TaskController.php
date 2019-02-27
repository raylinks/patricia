<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $user = $request->user();

        $tasks = $user->tasks;

        return response()->json($tasks);
    }

    public function show(Task $task)
    {
        return response()->json([
            'task' => $task
        ], 200);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $validation = validator($request->all(), [
            'task_description' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'error' => 'Task description is required!'
            ]);
        }

        $task = new Task();

        $task->task_description = $request->task_description;
        $task->user_id = $user['id'];
        $task->save();

        return response()->json([
            'message' => 'Task has been created.',
            'task' => $task
        ], 201);
    }

    public function update(Request $request, Task $task)
    {
        $validation = validator($request->all(), [
            'task_description' => 'required'
        ]);

        if ($validation->fails()) {
            return response()->json([
                'message' => 'Task description is required'
            ]);
        }

        $task->task_description = $request->task_description;
        $task->save();

        return response()->json([
            'message' => 'Task has been updated',
            'task' => $task
        ], 200);
    }
}
