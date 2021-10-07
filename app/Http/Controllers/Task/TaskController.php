<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try 
        {
            $userId = auth()->user()->id;

            return response()->json([
                'status' => 0,
                'message' => 'Fetched tasks successfully',
                'data' => [
                    'tasks' => Task::where('user_id', $userId)
                                    ->where('status', 1)
                                    ->where('completed', 0)
                                    ->paginate(10)
                ]
            ]);

        } catch(\Exception $e)
        {
            return response()->json([
                'status' => 99,
                'message' => 'Unable to fetch tasks',
                'data' => [
                    'error' => $e->getMessage()
                ]
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (!$request->has('task')) 
        {
            return response()
                    ->json([
                        'status' => 99,
                        'message' => 'task is required!',
                        'data' => [],
                    ]);
        }

        try 
        {
            $userId = auth()->user()->id;

            $task = Task::create([
                'user_id' => $userId,
                'task' => $request->task,
            ]);

            return response()->json([
                'status' => 0,
                'message' => 'Task created successfully',
                'data' => [
                    'task' => $task
                ]
            ]);

        } catch(\Exception $e)
        {
            return response()->json([
                'status' => 99,
                'message' => 'Unable to create task',
                'data' => [
                    'error' => $e->getMessage()
                ]
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try
        {
            return response()
                    ->json([
                        'status' => 0,
                        'message' => 'Fetched task successfully',
                        'data' => [
                            'task' => Task::find($id)
                        ],
                    ]);
        } catch(\Exception $e)
        {
            return response()
                    ->json([
                        'status' => 99,
                        'message' => 'Unable to retrieve task with id: ' . $id,
                        'data' => [
                            'error' => $e->getMessage()
                        ],
                    ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->has('task')) 
        {
            return response()
                    ->json([
                        'status' => 99,
                        'message' => 'task is required!',
                        'data' => [],
                    ]);
        }

        if (!$request->has('completed')) 
        {
            return response()
                    ->json([
                        'status' => 99,
                        'message' => 'completed flag is required!',
                        'data' => [],
                    ]);
        }

        try
        {
            $task = Task::find($id);

            $task->task = $request->task;
            $task->completed = $request->completed;
            $task->save();

            return response()
                    ->json([
                        'status' => 0,
                        'message' => 'Updated task successfully',
                        'data' => [
                            'task' => $task
                        ],
                    ]);
        } catch(\Exception $e)
        {
            return response()->json([
                'status' => 99,
                'message' => 'Unable to update task',
                'data' => [
                    'error' => $e->getMessage()
                ]
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try 
        {
            $task = Task::find($id);
            $task->status = 0;
            $task->save();

            return response()->json([
                'status' => 0,
                'message' => 'Task deleted successfully',
                'data' => []
            ]);
        } catch(\Exception $e)
        {
            return response()->json([
                'status' => 99,
                'message' => 'Unable to delete task',
                'data' => [
                    'error' => $e->getMessage()
                ]
            ]);
        }
    }
}
