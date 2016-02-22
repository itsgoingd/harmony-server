<?php

$factory->define(Harmony\Applications\Application::class, function(Faker\Generator $faker)
{
	return [
		'name'   => $name = $faker->name,
		'slug'   => str_slug($name),
		'apiKey' => str_random(32)
	];
});
