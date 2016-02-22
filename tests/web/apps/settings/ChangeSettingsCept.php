<?php
$I = new ApiTester($scenario);

$I->wantTo('change settings');

$user = $I->haveModel(\Harmony\Users\User::class, [ 'password' => bcrypt($password = str_random(10)) ]);
$application = $I->haveModel(\Harmony\Applications\Application::class, [ 'ownedBy' => $user->id ]);

$application->users()->attach($user);

$I->amLoggedAs([ 'email' => $user->email, 'password' => $password ]);
$I->amOnRoute('apps settings', [ 'app' => $application->slug ]);

$I->fillField([ 'name' => 'name' ], $newName = 'New Application');

$I->click('.applicationSettings-form:first-child input[type="submit"]');

$I->seeRecord('applications', [ 'id' => $application->id, 'name' => $newName ]);
