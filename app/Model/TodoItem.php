<?php
namespace Todo\Model;

use Illuminate\Database\Eloquent\Model;

//Create model for todo item
class TodoItem extends Model
{
    //Add relationship to TodoList
    //A TodoItem belong to only 1 list
    public function list()
    {
        return $this->belongsTo(TodoList::class);
    }
}
