<?php
$I = new ApiTester($scenario);

$I->wantTo('register an existing user');

$user = $I->haveModel(\Harmony\Users\User::class);

$I->amOnPage('/sign-up');

$I->fillField([ 'name' => 'email' ], $user->email);
$I->fillField([ 'name' => 'password' ], 'password');
$I->fillField([ 'name' => 'password_confirmation' ], 'password');

$I->click('Sign up');

$I->seeCurrentRouteIs('sign-up form');
$I->seeElement('.login-form-errors');
