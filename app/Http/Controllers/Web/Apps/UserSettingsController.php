<?php namespace Harmony\Http\Controllers\Web\Apps;

use Harmony\Applications\ApplicationsRepository;

use Illuminate\Http\Request;

class UserSettingsController extends Controller
{
	public function update(ApplicationsRepository $apps, Request $request, $slug)
	{
		$app = $this->getApp($apps, $slug);

		$apps->updateUserEmailNotificationsSetting($app, $this->signedIn, $request->input('emailNotifications'));

		return redirect()->route('apps settings', [ 'app' => $app->slug ]);
	}
}
