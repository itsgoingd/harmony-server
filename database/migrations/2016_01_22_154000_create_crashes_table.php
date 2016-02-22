<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCrashesTable extends Migration
{
	public function up()
	{
		Schema::create('crashes', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('exception');
			$table->text('message');
			$table->string('fileName');
			$table->string('lineNumber');

			$table->string('hash')->index();

			$table->datetime('archivedAt')->nullable();
			$table->integer('archivedBy')->unsigned()->nullable();

			$table->integer('applicationId')->unsigned();

			$table->timestamps();

			$table->foreign('applicationId')->references('id')->on('applications');
			$table->foreign('archivedBy')->references('id')->on('users');
		});
	}

	public function down()
	{
		Schema::drop('crashes');
	}
}
