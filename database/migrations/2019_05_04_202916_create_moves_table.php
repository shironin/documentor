<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovesTable extends Migration
{

//Action
//1 - created doc(number)
//2 - view(number)
//3 - edit(number)
//4 - search(criteria)
//5 - logged in
//6 - make ocr(number)


    public function up()
    {
        Schema::create('moves', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('action')->unsigned()->nullable();
            $table->integer('doc_number')->unsigned()->nullable();
            $table->text('search_criteria')->nullable();
            $table->timestamp('time')->useCurrent();

            $table->foreign('user_id')
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
        Schema::drop('moves');
    }
}
