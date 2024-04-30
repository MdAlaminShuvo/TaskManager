<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskAPIController extends Controller
{
    public function index()
    {
        return Task::where('is_deleted', 0)->get();
    }
    public function store(Request $request)
    {
        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->priority = $request->priority;

        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        //image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);
            $task->image = $name;
        }
        $task->save();
        return response()->json(['message' => 'Task created!'], 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Task not found!'], 404);
        }

        if($task->status == 'Deployed') {
            return response()->json(['message' => 'Task status cannot be updated!'], 400);
        }

        // Update task attributes
        $task->title = $request->title ?? $task->title;
        $task->description = $request->description ?? $task->description;
        $task->priority = $request->priority ?? $task->priority;
        $task->status = $request->status ?? $task->status;


        // If status is Testing then chack diffrerence between current time and last updated time is 15 mint or not
        if($request->status == 'Testing' || $request->status == 'Deployed') {
            $lastUpdated = strtotime($task->lastUpdated);
            $currentTime = strtotime(now());
            $diff = $currentTime - $lastUpdated;
            if($diff < 900) {
                return response()->json(['message' => 'Task can not be updated to Testing or Deployed before 15 minutes of last update!'], 400);
            }
        }

        if($request->status) {
            $task->lastUpdated = now();
        }

        // Validate the uploaded file if provided
        if ($request->hasFile('image')) {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/images');
            $image->move($destinationPath, $name);

            // Delete old image if exists
            if ($task->image) {
                unlink(public_path('images/' . $task->image));
            }

            $task->image = $name;
        }

        $task->save();

        return response()->json(['message' => 'Task updated!', 'task' => $task], 200);
    }


    public function destroy($id)
    {
        $task = Task::find($id);
        $task->is_deleted = 1;
        $task->save();
        return response()->json(['message' => 'Task deleted!'], 200);
    }

}
