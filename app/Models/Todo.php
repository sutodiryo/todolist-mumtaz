<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{

    public function todoable()
    {
        return $this->morphTo();
    }
}
