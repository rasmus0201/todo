<?php

use \Todo\Migration\Migration;

//New migration
class CreateTodoListTable extends Migration
{
    public function up()
    {
        //Create todo_lists table
        $this->schema->create('todo_lists', function (Illuminate\Database\Schema\Blueprint $table) {
            // Auto-increment id
            $table->increments('id');

            //Todo list columns
            $table->string('url', 8)->unique(); //unique url
            $table->string('name'); //List name
            $table->enum('status', ['public', 'private'])->default('public'); //List status
            $table->string('created_from_ip'); //IP created from

            // Required for Eloquent's created_at and updated_at columns
            $table->timestamps(); //created_at and updated_at
        });
    }
    public function down()
    {
        //Drop todo_lists table
        $this->schema->drop('todo_lists');
    }
}
