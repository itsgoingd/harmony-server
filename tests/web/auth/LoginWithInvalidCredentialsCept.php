<?php
$I = new ApiTester($scenario);

$I->wantTo('login with invalid credentials');

$I->amOnPage('/sign-in');

$I->fillField([ 'name' => 'email' ], 'nonexistent@user.tld');
$I->fillField([ 'name' => 'password' ], 'nonexistent');

$I->click('Sign in');

$I->seeCurrentRouteIs('sign-in form');
$I->seeElement('.login-form-errors');
