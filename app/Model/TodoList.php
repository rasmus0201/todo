<?php
namespace Todo\Model;

use Illuminate\Database\Eloquent\Model;

//Create model for todo list
class TodoList extends Model
{
    //Add relationship to TodoItem
    //A TodoList can have many TodoItems
    public function items()
    {
        return $this->hasMany(TodoItem::class);
    }
}
