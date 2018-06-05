<?php

use \Todo\Migration\Migration;

class CreateTodoItemTable extends Migration
{
    public function up()
    {
        $this->schema->create('todo_items', function (Illuminate\Database\Schema\Blueprint $table) {
            // Auto-increment id
            $table->increments('id');

            //Todo list columns
            $table->integer('list_id');

            $table->string('name');
            $table->enum('status', ['active', 'completed'])->default('active');
            $table->string('created_from_ip');

            // Required for Eloquent's created_at and updated_at columns
            $table->timestamps();
        });
    }
    public function down()
    {
        $this->schema->drop('todo_items');
    }
}
