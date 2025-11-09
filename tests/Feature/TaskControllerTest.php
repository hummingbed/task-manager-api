<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a user and authenticate with Sanctum
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }

    /** @test */
    public function user_can_list_their_tasks()
    {
        Task::factory()->count(3)->create(['user_id' => $this->user->id]);

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Tasks retrieved successfully.'
            ])
            ->assertJsonStructure([
                'data' => ['data' => [['id', 'title', 'description', 'status', 'user_id']]],
            ]);
    }

    /** @test */
    public function user_can_create_a_task()
    {
        $payload = [
            'title' => 'My first task',
            'description' => 'A simple test task',
            'status' => 'pending',
        ];

        $response = $this->postJson('/api/tasks', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'message' => 'Task created successfully.',
                'data' => ['title' => 'My first task']
            ]);

        $this->assertDatabaseHas('tasks', [
            'title' => 'My first task',
            'user_id' => $this->user->id,
        ]);
    }

    /** @test */
    public function user_can_view_a_specific_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->getJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Task retrieved successfully.',
                'data' => ['id' => $task->id]
            ]);
    }

    /** @test */
    public function user_can_update_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $payload = ['title' => 'Updated Title', 'status' => 'completed'];

        $response = $this->putJson("/api/tasks/{$task->id}", $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Task updated successfully.',
                'data' => ['title' => 'Updated Title']
            ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Title',
            'status' => 'completed',
        ]);
    }

    /** @test */
    public function user_can_delete_a_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'Task deleted successfully.'
            ]);

        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    /** @test */
    public function user_cannot_access_someone_elses_task()
    {
        $otherUserTask = Task::factory()->create();

        $response = $this->getJson("/api/tasks/{$otherUserTask->id}");

        $response->assertStatus(404)
            ->assertJson([
                'status' => 'failed',
                'message' => 'Task not found',
                'data' => null
            ]);

    }

}
