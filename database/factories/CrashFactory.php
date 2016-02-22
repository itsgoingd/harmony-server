<?php

$factory->define(Harmony\Crashes\Crash::class, function(Faker\Generator $faker)
{
	return [
		'exception'  => $exception = $faker->name,
		'message'    => $message = $faker->sentence,
		'fileName'   => $fileName = str_replace(' ', '/', $faker->sentence),
		'lineNumber' => $lineNumber = $faker->randomDigit,
		'hash'       => sha1("{$fileName}-{$lineNumber}-{$exception}-{$message}")
	];
});
