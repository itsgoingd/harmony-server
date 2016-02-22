<?php
$I = new ApiTester($scenario);

$I->wantTo('register a prepared user');

$user = $I->haveModel(\Harmony\Users\User::class, [
	'password' => null
]);

$I->amOnPage('/sign-up');

$I->fillField([ 'name' => 'email' ], $user->email);
$I->fillField([ 'name' => 'password' ], 'password');
$I->fillField([ 'name' => 'password_confirmation' ], 'password');

$I->click('Sign up');

$I->seeAuthentication();
$I->seeCurrentRouteIs('apps create');
