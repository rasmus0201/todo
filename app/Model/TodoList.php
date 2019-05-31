<?php
namespace Todo\Model;

use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    public function items()
    {
        return $this->hasMany(TodoItem::class);
    }
}
