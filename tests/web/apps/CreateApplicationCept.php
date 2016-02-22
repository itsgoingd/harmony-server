<?php
$I = new ApiTester($scenario);

$I->wantTo('create application');

$user = $I->haveModel(\Harmony\Users\User::class, [ 'password' => bcrypt($password = str_random(10)) ]);

$I->amLoggedAs([ 'email' => $user->email, 'password' => $password ]);
$I->amOnRoute('apps create');

$I->fillField([ 'name' => 'name' ], 'New application');

$I->click('Create application');

$I->seeRecord('applications', [ 'name' => 'New application' ]);
