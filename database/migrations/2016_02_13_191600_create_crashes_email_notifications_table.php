<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrashesEmailNotificationsTable extends Migration
{
	public function up()
	{
		Schema::create('crashes_email_notifications', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('crashId')->unsigned();
			$table->integer('userId')->unsigned();

			$table->datetime('lastSentAt');

			$table->foreign('crashId')->references('id')->on('crashes');
			$table->foreign('userId')->references('id')->on('users');
		});
	}

	public function down()
	{
		Schema::drop('crashes_email_notifications');
	}
}
