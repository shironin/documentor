<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sender_id')->unsigned()->nullable();
            $table->integer('receiver_id')->unsigned()->nullable();
            $table->integer('doc_number')->unsigned()->nullable();
            $table->text('message')->nullable();
            $table->integer('status')->unsigned()->default(0);
            $table->timestamp('time')->useCurrent();

            $table->foreign('sender_id')
                ->references('id')
                ->on('users');
            $table->foreign('receiver_id')
                ->references('id')
                ->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
    }
}
