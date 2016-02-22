<?php
$I = new ApiTester($scenario);

$I->wantTo('register a new user');

$I->amOnPage('/sign-up');

$I->fillField([ 'name' => 'email' ], 'new@user.tld');
$I->fillField([ 'name' => 'password' ], 'password');
$I->fillField([ 'name' => 'password_confirmation' ], 'password');

$I->click('Sign up');

$I->seeAuthentication();
$I->seeCurrentRouteIs('apps create');
