<?php
$I = new ApiTester($scenario);

$I->wantTo('report an existing crash');

$user = $I->haveModel(\Harmony\Users\User::class);
$application = $I->haveModel(\Harmony\Applications\Application::class, [ 'ownedBy' => $user->id ]);
$crash = $I->haveModel(\Harmony\Crashes\Crash::class, [ 'applicationId' => $application->id ]);

$exceptionData = [
	'apiKey' => $application->apiKey,
	'data'   => [
		'exception'  => $crash->exception,
		'message'    => $crash->message,
		'fileName'   => $crash->fileName,
		'lineNumber' => $crash->lineNumber
	]
];

$I->sendPOST('/api/crashes', json_encode($exceptionData));

$I->seeResponseCodeIs(200);

$response = json_decode($I->grabResponse(), true);

$I->assertEquals($response['crashId'], $crash->id);

$I->seeRecord('crashes_instances', [
	'id'      => $response['instanceId'],
	'crashId' => $crash->id
]);
