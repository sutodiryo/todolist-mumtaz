<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

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
        $this->validate([
            "task_name" => "required",
            "contributors" => "required"
        ]);

        DB::beginTransaction();

        $task = Task::create([
            "name" => $this->task_name,
            "description" => $this->description,
        ]);

        $task->users()->attach($this->contributors);
        // dd($task->users);
        DB::commit();

        $this->reset("task_name", "contributors");
        $this->reset("show_add_task_modal");
        $this->redirect('/');
    }

    public function setAddTodo($id)
    {
        $this->task_id = $id;
        $this->show_add_todo_modal = true;
    }

    public function addTodo() {

        DB::beginTransaction();
        $task = Task::findOrFail($this->task_id);

        $todo = new Todo();
        $todo->task_id = $this->task_id;
        $todo->name = $this->todo_name;
        $task->todos()->save($todo);

        DB::commit();

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
        DB::beginTransaction();
        $task = Task::findOrFail($this->task_id);
        $this->validate([
            "e_name" => "required"
        ]);

        $task->update([
            "name" => $this->e_name,
            "description" => $this->e_description,
        ]);

        DB::commit();

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
        DB::beginTransaction();

        $task->users()->detach();
        // $task->todos()->detach();
        $task->delete();

        DB::commit();
        $this->redirect('/');
    }


    public function render()
    {
        return view('livewire.tasks');
    }
}
