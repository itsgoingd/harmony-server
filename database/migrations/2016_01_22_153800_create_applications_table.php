<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationsTable extends Migration
{
	public function up()
	{
		Schema::create('applications', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('name');
			$table->string('slug')->unique();
			$table->string('apiKey')->index();

			$table->string('path')->nullable();

			$table->string('logo_file_name')->nullable();
			$table->integer('logo_file_size')->nullable();
			$table->string('logo_content_type')->nullable();
			$table->datetime('logo_updated_at')->nullable();

			$table->string('color', 6);

			$table->integer('ownedBy')->unsigned();

			$table->timestamps();

			$table->foreign('ownedBy')->references('id')->on('users');
		});
	}

	public function down()
	{
		Schema::drop('applications');
	}
}
