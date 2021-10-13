<?php

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskActionController extends Controller
{
    public function markComplete($id)
    {
        try 
        {
            $task = Task::find($id);
            $task->completed = 1;
            $task->save();

            return response()->json([
                'status' => 0,
                'message' => 'Task completed successfully',
                'data' => []
            ]);
        } catch(\Exception $e)
        {
            return response()->json([
                'status' => 99,
                'message' => 'Unable to mark task as completed',
                'data' => [
                    'error' => $e->getMessage()
                ]
            ]);
        }
    }
}
