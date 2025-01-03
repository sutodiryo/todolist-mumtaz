<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Services\TaskService;

class Tasks extends Component
{

    public $tasks;
    public $users, $contributors;
    public $show_add_task_modal = false;
    public $show_edit_task_modal = false;
    public $show_add_todo_modal = false;
    public $task_name, $description;
    public $todo_name, $task_id;
    public $edit_task, $e_name, $e_description, $e_users = [], $e_contributors, $e_todos;

    public function mount(Task $task)
    {
        $this->tasks = $task->orderBy("is_done")->get();
        $this->users = User::get();
    }

    public function addTask()
    {
        $data = $this->validate([
            "task_name" => "required",
            "description" => "nullable",
            "contributors" => "required",
        ]);

        $task = new TaskService();
        $task->store($data);

        $this->redirect('/');
    }

    public function setAddTodo($id)
    {
        $this->task_id = $id;
        $this->show_add_todo_modal = true;
    }

    public function addTodo()
    {
        $data = $this->validate([
            "task_id" => "required",
            "todo_name" => "required",
        ]);

        $task = new TaskService();
        $task->todo_store($data);

        $this->reset("task_id");
        $this->reset("show_add_todo_modal");
    }

    public function setEditTask($id)
    {
        $this->task_id = $id;

        $task = Task::findOrFail($id);
        $this->e_name = $task->name;
        $this->e_description = $task->description;
        $this->e_contributors = $task->users;
        $this->e_todos = $task->todos;

        $this->show_edit_task_modal = true;
    }

    public function editTask()
    {
        $data = $this->validate([
            "task_id" => "required",
            "e_name" => "required",
            "e_description" => "nullable"
        ]);

        $task = new TaskService();
        $task->update_task($data);

        $this->redirect('/');
    }

    public function toggleStatus(Task $task)
    {
        $new_is_done = !$task->is_done;

        $task->update([
            "is_done" => $new_is_done
        ]);
    }

    public function deleteTask(Task $task)
    {
        $taskService = new TaskService();
        $taskService->delete_task($task);

        $this->redirect('/');
    }


    public function render()
    {
        return view('livewire.tasks');
    }
}
