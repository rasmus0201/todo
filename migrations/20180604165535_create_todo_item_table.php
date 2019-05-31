<?php

use \Todo\Migration\Migration;

class CreateTodoItemTable extends Migration
{
    public function up()
    {
        $this->schema->create('todo_items', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->integer('todo_list_id');
            $table->string('name');
            $table->enum('status', ['active', 'completed'])->default('active');
            $table->string('created_from_ip');
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        $this->schema->drop('todo_items');
    }
}
