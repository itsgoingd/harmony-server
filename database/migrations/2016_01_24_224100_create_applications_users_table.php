<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsUsersTable extends Migration
{
	public function up()
	{
		Schema::create('applications_users', function(Blueprint $table)
		{
			$table->integer('applicationId')->unsigned();
			$table->integer('userId')->unsigned();

			$table->enum('emailNotifications', [ 'disabled', 'asap', 'hourly', 'daily' ])->default('disabled');

			$table->foreign('applicationId')->references('id')->on('applications');
			$table->foreign('userId')->references('id')->on('users');
		});
	}

	public function down()
	{
		Schema::drop('applications_users');
	}
}
