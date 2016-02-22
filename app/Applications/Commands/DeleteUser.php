<?php namespace Harmony\Applications\Commands;

use Harmony\Applications\Application;
use Harmony\Users\UsersRepository;

class DeleteUser
{
	protected $app;
	protected $userId;

	public function __construct(Application $app, $userId)
	{
		$this->app    = $app;
		$this->userId = $userId;
	}

	public function handle(UsersRepository $users)
	{
		$user = $users->find($this->userId);

		if (! $user || $user->isApplicationOwner($this->app)) {
			return false;
		}

		$this->app->users()->detach($user);
	}
}
