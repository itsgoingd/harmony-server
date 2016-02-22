<?php namespace Harmony\Crashes;

use Harmony\Crashes\Crash;
use Harmony\Support\Repository;
use Harmony\Users\User;

class NotificationsRepository extends Repository
{
	protected $model = Notification::class;

	public function findOrCreateForUser(Crash $crash, User $user)
	{
		$notification = $this->where('crashId', $crash->id)->where('userId', $user->id)->first();

		if (! $notification) {
			$notification = $this->create([
				'crashId' => $crash->id,
				'userId'  => $user->id
			]);
		}

		return $notification;
	}
}
