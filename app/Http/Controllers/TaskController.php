<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use App\Traits\HttpResponses;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use HttpResponses;

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request)
    {
        $status = $request->query('status');

        $tasks = $this->taskService->index($status);

        return $this->successHttpMessage(
            $tasks,
            'Tasks retrieved successfully.',
            200
        );
    }


    public function store(StoreTaskRequest $request)
    {
        $task = $this->taskService->store($request->validated());

        return $this->successHttpMessage(
            $task,
            'Task created successfully.',
            201
        );
    }

    public function show($id)
    {
        $task = $this->taskService->show($id);

        return $this->successHttpMessage(
            $task,
            'Task retrieved successfully.',
            200
        );
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = $this->taskService->update($id, $request->validated());

        return $this->successHttpMessage(
            $task,
            'Task updated successfully.',
            200
        );
    }

    public function destroy($id)
    {
        $this->taskService->destroy($id);

        return $this->successHttpMessage(
            null,
            'Task deleted successfully.',
            200
        );
    }
}
