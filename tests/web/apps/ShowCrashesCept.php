<?php
$I = new ApiTester($scenario);

$I->wantTo('show crashes');

$user = $I->haveModel(\Harmony\Users\User::class, [ 'password' => bcrypt($password = str_random(10)) ]);
$application = $I->haveModel(\Harmony\Applications\Application::class, [ 'ownedBy' => $user->id ]);
$crash = $I->haveModel(\Harmony\Crashes\Crash::class, [ 'applicationId' => $application->id ]);

$application->users()->attach($user);

$I->amLoggedAs([ 'email' => $user->email, 'password' => $password ]);
$I->amOnPage('/');

$I->see($crash->exception);
$I->see(str_limit($crash->message, 300));
$I->see($crash->id);
