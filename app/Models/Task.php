<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function users() {
        return $this->belongsToMany(User::class, 'task_user', 'task_id', 'user_id');
    }

    public function todos()
    {
        return $this->morphMany(Todo::class, 'todoable');
    }
}
