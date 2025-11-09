<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Exceptions\EntityNotFoundException;

class TaskService
{
    public function index(?string $status = null)
    {
        $query = Auth::user()->tasks()->latest();

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate(10);
    }


    public function store(array $validatedData): Task
    {
        return Auth::user()->tasks()->create($validatedData);
    }

    public function show(int $id): Task
    {
        $task = Auth::user()->tasks()->find($id);

        throw_unless(
            $task,
            fn() => new EntityNotFoundException('Task not found')
        );

        return $task;
    }

    public function update(int $id, array $validatedData): Task
    {
        $task = Auth::user()->tasks()->find($id);

        throw_unless(
            $task,
            fn() => new EntityNotFoundException('Task not found')
        );

        $task->update($validatedData);

        return $task->fresh();
    }

    public function destroy(int $id): bool
    {
        $task = Auth::user()->tasks()->find($id);

        throw_unless(
            $task,
            fn() => new EntityNotFoundException('Task not found')
        );

        return $task->delete();
    }
}