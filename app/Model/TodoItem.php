<?php
namespace Todo\Model;

use Illuminate\Database\Eloquent\Model;

class TodoItem extends Model
{
    public function list()
    {
        return $this->belongsTo(TodoList::class);
    }
}
