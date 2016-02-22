<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrashesInstancesTable extends Migration
{
	public function up()
	{
		Schema::create('crashes_instances', function(Blueprint $table)
		{
			$table->increments('id');

			$table->mediumtext('callStack');

			$table->mediumtext('requestData');
			$table->mediumtext('requestHeaders');

			$table->mediumtext('queryLog');

			$table->integer('crashId')->unsigned();

			$table->timestamps();

			$table->foreign('crashId')->references('id')->on('crashes');
		});
	}

	public function down()
	{
		Schema::drop('crashes_instances');
	}
}
