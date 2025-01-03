<div class="grid place-content-center h-screen gap-4 px-3">
    <h1 class="text-center">Todolist App</h1>
    <ul class="menu menu-vertical bg-base-200">
        @forelse ($tasks as $task)
            <li>
                <div @class(['line-through' => $task->is_done])>
                    <div wire:click="toggleStatus({{ $task->id }})">{{ $task->name }}</div>
                    <button class="badge badge-sm badge-warning"
                        wire:click="setAddTodo('{{ $task->id }}')">Add Todo</button>
                    <button class="badge badge-sm badge-warning"
                        wire:click="setEditTask('{{ $task->id }}')">Edit</button>
                    <button class="badge badge-sm badge-error"
                        wire:click="deleteTask({{ $task->id }})">DELETE</button>
                </div>
            </li>
        @empty
            <li>
                <div>Task is empty.</div>
            </li>
        @endforelse
    </ul>

    <!-- Add Task Modal -->
    <button class="btn" wire:click="$set('show_add_task_modal', 1)">Add Task</button>
    <dialog id="add_task_modal" class="modal" {{ $show_add_task_modal ? 'open' : '' }} data-theme="light">
        <form class="modal-box" wire:submit="addTask">
            <h3 class="text-lg font-bold">Add New Task</h3>

            <div class="py-3">
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Task Name</span>
                    </div>
                    <input type="text" placeholder="Type here" @class([
                        'input input-bordered w-full',
                        'input-error' => $errors->first('task_name'),
                    ]) wire:model="task_name" />
                    @error('task_name')
                        <div class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                </label>
            </div>

            <div class="py-3">
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Task description</span>
                    </div>
                    <textarea placeholder="Type here" @class([
                        'input input-bordered w-full',
                        'input-error' => $errors->first('description'),
                    ]) wire:model="description">
                    </textarea>
                    @error('description')
                        <div class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                </label>
            </div>

            <div class="py-3">
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Task Contributors</span>
                    </div>
                    <select multiple @class([
                        'input input-bordered w-full',
                        'input-error' => $errors->first('contributors'),
                    ]) wire:model="contributors">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('contributors')
                        <div class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                </label>
            </div>

            <div class="modal-action">
                <button type="button" class="btn" wire:click="$set('show_add_task_modal', 0)">Close</button>
                <button class="btn btn-success">Add Task</button>
            </div>
        </form>
    </dialog>

    <!-- Edit Modal -->
    <dialog id="edit_task_modal" class="modal" {{ $show_edit_task_modal ? 'open' : '' }} data-theme="light">
        <form class="modal-box" wire:submit="editTask()">
            <h3 class="text-lg font-bold">Edit Task</h3>

            <div class="py-3">
                <label class="form-control w-full">
                    <input type="text" @class([
                        'input input-bordered w-full',
                        'input-error' => $errors->first('e_name'),
                    ]) wire:model="e_name" />
                    @error('e_name')
                        <div class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                </label>
            </div>

            <div class="py-3">
                <label class="form-control w-full">
                    <textarea placeholder="Type here" @class([
                        'input input-bordered w-full',
                        'input-error' => $errors->first('e_description'),
                    ]) wire:model="e_description">
                        {{ $e_description }}
                    </textarea>
                    @error('e_description')
                        <div class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                </label>
            </div>

            <div class="py-3">
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Task Contributors</span>
                    </div>
                    <select multiple disabled @class([
                        'input input-bordered w-full',
                        'input-error' => $errors->first('e_contributors'),
                    ]) wire:model="e_contributors">
                        @if ($e_contributors)
                            @foreach ($e_contributors as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('e_contributors')
                        <div class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                </label>
            </div>

            <div class="py-3">

                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text">Task Todo</span>
                    </div>
                    <select multiple disabled @class([
                        'input input-bordered w-full',
                        'input-error' => $errors->first('e_todos'),
                    ]) wire:model="e_todos">
                        @if ($e_todos)
                            @foreach ($e_todos as $todo)
                                <option value="{{ $todo->id }}">{{ $todo->name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('e_todos')
                        <div class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                </label>
            </div>

            <div class="modal-action">
                <button type="button" class="btn" wire:click="$set('show_edit_task_modal', 0)">Close</button>
                <button class="btn btn-primary">Update Task</button>
            </div>
        </form>
    </dialog>


    <!-- Add Todo Modal -->
    <dialog id="add_todo_modal" class="modal" {{ $show_add_todo_modal ? 'open' : '' }} data-theme="light">
        <form class="modal-box" wire:submit="addTodo()">
            <h3 class="text-lg font-bold">Add Todo to Task</h3>

            <div class="py-3">
                <label class="form-control w-full">
                    <input type="text" @class([
                        'input input-bordered w-full',
                        'input-error' => $errors->first('todo_name'),
                    ]) wire:model="todo_name" />
                    @error('todo_name')
                        <div class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </div>
                    @enderror
                </label>
            </div>

            <div class="modal-action">
                <button type="button" class="btn" wire:click="$set('show_add_todo_modal', 0)">Close</button>
                <button class="btn btn-primary">Add Todo</button>
            </div>
        </form>
    </dialog>
</div>
