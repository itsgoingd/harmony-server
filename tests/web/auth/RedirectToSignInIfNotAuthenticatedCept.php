<?php
$I = new ApiTester($scenario);

$I->wantTo('redirect to sign in if not authenticated');

$I->amOnPage('/profile');

$I->seeCurrentRouteIs('sign-in form');
