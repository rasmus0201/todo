<?php

use \Todo\Migration\Migration;

//New migration
class CreateTodoItemTable extends Migration
{
    public function up()
    {
        //Create todo_items table
        $this->schema->create('todo_items', function (Illuminate\Database\Schema\Blueprint $table) {
            // Auto-increment id
            $table->increments('id');

            //Todo list columns
            $table->integer('todo_list_id'); //Relation to list

            $table->string('name'); //Item name
            $table->enum('status', ['active', 'completed'])->default('active'); //Item status - is it completed?
            $table->string('created_from_ip');

            // Required for Eloquent's created_at and updated_at columns
            $table->timestamps(); //created_at and updated_at
        });
    }
    public function down()
    {
        //Drop todo_items table
        $this->schema->drop('todo_items');
    }
}
