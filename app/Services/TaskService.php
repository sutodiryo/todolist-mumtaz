<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TaskService
{
    public function store($data)
    {
        DB::beginTransaction();

        $task = Task::create([
            "name" => $data['task_name'],
            "description" => $data['description'],
        ]);

        $task->users()->attach($data['contributors']);

        DB::commit();

    }

    public function update_task($data)
    {
        DB::beginTransaction();
        $task = Task::findOrFail($data['task_id']);

        $task->update([
            "name" => $data['e_name'],
            "description" => $data['e_description'],
        ]);

        DB::commit();
    }

    public function todo_store($data)
    {
        DB::beginTransaction();
        $task = Task::findOrFail($data['task_id']);

        $todo = new Todo();
        $todo->task_id = $data['task_id'];
        $todo->name = $data['todo_name'];
        $task->todos()->save($todo);

        DB::commit();
    }


    public function delete_task($task)
    {
        DB::beginTransaction();

        $task->users()->detach();

        foreach ($task->todos as $todo) {
            $todo->delete();
        }

        $task->delete();

        DB::commit();
    }
}
