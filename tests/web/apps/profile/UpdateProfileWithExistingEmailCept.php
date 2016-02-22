<?php
$I = new ApiTester($scenario);

$I->wantTo('update profile with existing email');

$user = $I->haveModel(\Harmony\Users\User::class, [ 'password' => bcrypt($password = str_random(10)) ]);
$secondUser = $I->haveModel(\Harmony\Users\User::class);

$I->amLoggedAs([ 'email' => $user->email, 'password' => $password ]);
$I->amOnRoute('profile edit');

$I->fillField([ 'name' => 'email' ], $secondUser->email);

$I->click('Update my profile');

$I->seeCurrentRouteIs('profile edit');
$I->seeElement('.userProfileEdit-form-errors');
