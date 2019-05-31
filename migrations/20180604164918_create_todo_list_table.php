<?php

use \Todo\Migration\Migration;

class CreateTodoListTable extends Migration
{
    public function up()
    {
        $this->schema->create('todo_lists', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->string('url', 8)->unique();
            $table->string('name');
            $table->enum('status', ['public', 'private'])->default('public');
            $table->string('created_from_ip');
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        $this->schema->drop('todo_lists');
    }
}
