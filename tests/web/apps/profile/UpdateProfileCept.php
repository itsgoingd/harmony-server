<?php
$I = new ApiTester($scenario);

$I->wantTo('update profile');

$user = $I->haveModel(\Harmony\Users\User::class, [ 'password' => bcrypt($password = str_random(10)) ]);

$I->amLoggedAs([ 'email' => $user->email, 'password' => $password ]);
$I->amOnRoute('profile edit');

$I->fillField([ 'name' => 'email' ], $newEmail = 'new@user.tld');

$I->click('Update my profile');

$I->seeRecord('users', [ 'id' => $user->id, 'email' => $newEmail ]);
