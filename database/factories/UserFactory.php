<?php

$factory->define(Harmony\Users\User::class, function(Faker\Generator $faker)
{
	return [
		'email'    => $faker->email,
		'password' => bcrypt(str_random(10))
	];
});
