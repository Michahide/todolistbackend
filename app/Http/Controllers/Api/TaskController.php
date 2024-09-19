<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /*
     * @return void
     */
    public function index()
    {
        $tasks = Task::latest()->paginate(10);

        return new TaskResource(true, 'List of tasks', $tasks);
    }

    /*
     * store
     * 
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            // return new TaskResource(false, 'Validation failed', $validator->errors());
            return response()->json($validator->errors(), 422);
        }

        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return new TaskResource(true, 'Task created successfully', $task);
    }

    public function show($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return new TaskResource(false, 'Task' . $id . ' not found', null);
        }

        return new TaskResource(true, 'Task ' . $id . ' found', $task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return new TaskResource(false, 'Task' . $id . ' not found', null);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return new TaskResource(true, 'Task' . $id . ' updated successfully', $task);
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return new TaskResource(false, 'Task' . $id . ' not found', null);
        }

        $task->delete();

        return new TaskResource(true, 'Task' . $id . ' deleted successfully', null);
    }
}
