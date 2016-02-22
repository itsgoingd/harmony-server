<?php
$I = new ApiTester($scenario);

$I->wantTo('login with valid credentials');

$user = $I->haveModel(\Harmony\Users\User::class, [
	'password' => bcrypt($password = str_random(10))
]);

$I->amOnPage('/sign-in');

$I->fillField([ 'name' => 'email' ], $user->email);
$I->fillField([ 'name' => 'password' ], $password);

$I->click('Sign in');

$I->seeAuthentication();
$I->seeCurrentRouteIs('apps create');
