<?php
$I = new ApiTester($scenario);

$I->wantTo('add user');

$user = $I->haveModel(\Harmony\Users\User::class, [ 'password' => bcrypt($password = str_random(10)) ]);
$secondUser = $I->haveModel(\Harmony\Users\User::class);
$application = $I->haveModel(\Harmony\Applications\Application::class, [ 'ownedBy' => $user->id ]);

$application->users()->attach($user);
$application->users()->attach($secondUser);

$I->amLoggedAs([ 'email' => $user->email, 'password' => $password ]);
$I->amOnRoute('apps settings', [ 'app' => $application->slug ]);

$I->sendAjaxPostRequest(route('apps users delete', [ 'app' => $application->slug, 'user' => $secondUser->id ]), [
	'_method' => 'delete',
	'_token' => csrf_token()
]);

$I->dontSeeRecord('applications_users', [ 'applicationId' => $application->id, 'userId' => $secondUser->id ]);
