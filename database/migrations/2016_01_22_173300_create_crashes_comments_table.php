<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrashesCommentsTable extends Migration
{
	public function up()
	{
		Schema::create('crashes_comments', function(Blueprint $table)
		{
			$table->increments('id');

			$table->text('message');

			$table->integer('crashId')->unsigned();
			$table->integer('postedBy')->unsigned();

			$table->timestamps();

			$table->foreign('crashId')->references('id')->on('crashes');
			$table->foreign('postedBy')->references('id')->on('users');
		});
	}

	public function down()
	{
		Schema::drop('crashes_comments');
	}
}
