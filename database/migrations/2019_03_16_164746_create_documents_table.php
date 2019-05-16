<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->increments('id');
            $table->text('comment')->nullable();
            $table->integer('user_id')->unsigned()->nullable();
            $table->integer('doctype_id')->unsigned()->nullable();
            $table->integer('department_id')->unsigned()->nullable();
            $table->text('file')->nullable();
            $table->text('temp_file')->nullable();
            $table->integer('is_file')->unsigned()->nullable();
            $table->text('content_string')->nullable();
            $table->text('full_content')->nullable();
            $table->integer('status')->unsigned()->nullable();
            $table->integer('ocr_status')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->foreign('doctype_id')
                ->references('id')
                ->on('doctypes');
            $table->foreign('department_id')
                ->references('id')
                ->on('departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('documents');
    }
}
