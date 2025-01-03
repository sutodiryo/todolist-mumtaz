<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use App\Models\Task;
use App\Models\Todo;
use App\Models\User;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TaskTest extends TestCase
{

    /**
     * Check if task succesfully created or not.
     *
     * @return void
     */
    public function test_can_create_task()
    {
        // First count total number of task
        $totalTasks = Task::get('id')->count();

        $task = Task::create([
            'name'       => 'Test',
            'description' => 'test ini',
        ]);

        $this->assertDatabaseCount('tasks', $totalTasks + 1);

        // Delete the task as need to keep it in database for other tests
        $task = Task::where('name', 'Test')->where('description', 'test ini')->first();
        $task->users()->detach();
        $task->delete();
    }

}
