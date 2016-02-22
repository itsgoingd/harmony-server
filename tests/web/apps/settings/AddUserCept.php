<?php
$I = new ApiTester($scenario);

$I->wantTo('add user');

$user = $I->haveModel(\Harmony\Users\User::class, [ 'password' => bcrypt($password = str_random(10)) ]);
$application = $I->haveModel(\Harmony\Applications\Application::class, [ 'ownedBy' => $user->id ]);

$application->users()->attach($user);

$I->amLoggedAs([ 'email' => $user->email, 'password' => $password ]);
$I->amOnRoute('apps settings', [ 'app' => $application->slug ]);

$I->sendAjaxPostRequest(route('apps users add', [ 'app' => $application->slug ]), [
	'_token' => csrf_token(),
 	'email' => 'new@user.tld'
]);

$I->seeRecord('users', [ 'email' => 'new@user.tld' ]);

$userRecord = $I->grabRecord('users', [ 'email' => 'new@user.tld' ]);
$I->seeRecord('applications_users', [ 'applicationId' => $application->id, 'userId' => $userRecord->id ]);
