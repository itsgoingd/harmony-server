<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('email')->unique();
			$table->string('password', 60)->nullable();

			$table->string('avatar_file_name')->nullable();
			$table->integer('avatar_file_size')->nullable();
			$table->string('avatar_content_type')->nullable();
			$table->datetime('avatar_updated_at')->nullable();

			$table->string('color', 6);

			$table->rememberToken();

			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('users');
	}
}
