<?php namespace Harmony\Applications;

use Harmony\Support\Repository;
use Harmony\Users\User;

class ApplicationsRepository extends Repository
{
	protected $model = Application::class;

	public function findByApiKey($apiKey)
	{
		return $this->where('apiKey', $apiKey)->first();
	}

	public function updateUserEmailNotificationsSetting(Application $app, User $user, $notificationsSetting)
	{
		$user = $app->users->find($user);

		if (! $user) {
			return false;
		}

		$user->pivot->emailNotifications = $notificationsSetting;
		$user->pivot->save();
	}
}
