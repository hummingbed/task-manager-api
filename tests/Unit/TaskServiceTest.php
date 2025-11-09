<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use Illuminate\Validation\ValidationException;

class TaskServiceTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected TaskService $taskService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);

        $this->taskService = new TaskService();
    }

    /** @test */
    public function it_can_list_tasks_for_authenticated_user()
    {
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);
        Task::factory()->count(2)->create(); // other users’ tasks

        $tasks = $this->taskService->index();

        $this->assertCount(3, $tasks->items());
        $this->assertEquals($this->user->id, $tasks->first()->user_id);
    }

    /** @test */
    public function it_can_create_a_new_task()
    {
        $data = [
            'title' => 'Unit Test Task',
            'description' => 'Service layer test',
            'status' => 'pending'
        ];

        $task = $this->taskService->store($data);

        $this->assertInstanceOf(Task::class, $task);
        $this->assertDatabaseHas('tasks', [
            'title' => 'Unit Test Task',
            'user_id' => $this->user->id
        ]);
    }

    /** @test */
    public function it_can_show_a_task_owned_by_user()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $found = $this->taskService->show($task->id);

        $this->assertEquals($task->id, $found->id);
    }

    /** @test */
    public function it_throws_validation_exception_when_viewing_unowned_task()
    {
        $this->expectException(\App\Exceptions\EntityNotFoundException::class);

        $otherTask = Task::factory()->create();
        $this->taskService->show($otherTask->id);
    }

    /** @test */
    public function it_can_update_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $updated = $this->taskService->update($task->id, [
            'title' => 'Updated by Service',
            'status' => 'completed'
        ]);

        $this->assertEquals('Updated by Service', $updated->title);
        $this->assertEquals('completed', $updated->status);
    }

    /** @test */
    public function it_can_delete_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $result = $this->taskService->destroy($task->id);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
