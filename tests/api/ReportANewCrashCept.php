<?php
$I = new ApiTester($scenario);

$I->wantTo('report a new crash');

$user = $I->haveModel(\Harmony\Users\User::class);
$application = $I->haveModel(\Harmony\Applications\Application::class, [ 'ownedBy' => $user->id ]);

$exceptionData = [
	'apiKey' => $application->apiKey,
	'data'   => [
		'exception'  => 'Exception',
		'message'    => 'Test exception ' . mt_rand(),
		'fileName'   => '/Sites/admin/harmony/app/Http/Controllers/SiteController.php',
		'lineNumber' => 42,
		'callStack'  => [
			[ 'file' => '/Sites/admin/harmony/app/Http/Controllers/SiteController.php' ],
			[ 'file' => '/Sites/admin/harmony/app/Http/Controllers/SiteController.php' ],
			[ 'file' => '/Sites/admin/harmony/vendor/laravel/framework/src/Illuminate/Routing/Controller.php' ],
			[ 'file' => '/Sites/admin/harmony/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php' ],
			[ 'file' => '/Sites/admin/harmony/vendor/laravel/framework/src/Illuminate/Routing/ControllerDispatcher.php' ],
			[ 'file' => null ]
		]
	]
];

$I->sendPOST('/api/crashes', json_encode($exceptionData));

$I->seeResponseCodeIs(200);

$response = json_decode($I->grabResponse(), true);

$I->seeRecord('crashes', [
	'id'            => $response['crashId'],
	'message'       => $exceptionData['data']['message'],
	'applicationId' => $application->id
]);

$I->seeRecord('crashes_instances', [
	'id'      => $response['instanceId'],
	'crashId' => $response['crashId']
]);

$I->seeRecord('applications', [ 'id' => $application->id, 'path' => '/Sites/admin/harmony/' ]);
