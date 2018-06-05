<?php

use \Todo\Migration\Migration;

class CreateTodoListTable extends Migration
{
    public function up()
    {
        $this->schema->create('todo_lists', function (Illuminate\Database\Schema\Blueprint $table) {
            // Auto-increment id
            $table->increments('id');

            //Todo list columns
            $table->string('url', 8)->unique();
            $table->string('name');
            $table->enum('status', ['public', 'private'])->default('public');
            $table->string('created_from_ip');

            // Required for Eloquent's created_at and updated_at columns
            $table->timestamps();
        });
    }
    public function down()
    {
        $this->schema->drop('todo_lists');
    }
}
