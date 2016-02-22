<?php namespace Harmony\Applications\Commands;

use Harmony\Applications\Application;
use Harmony\Applications\Exceptions\UserAlreadyAdded;
use Harmony\Applications\Exceptions\ValidationFailed;
use Harmony\Users\UsersRepository;
use Harmony\Users\Commands\CreateUser;

use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;

class AddUser
{
	use DispatchesJobs;

	protected $app;
	protected $email;

	protected $users;

	public function __construct(Application $app, $email)
	{
		$this->app   = $app;
		$this->email = $email;
	}

	public function handle(UsersRepository $users, Validator $validator)
	{
		$this->users = $users;

		if ($validator->make([ 'email' => $this->email ], [ 'email' => 'required|email' ])->fails()) {
			throw new ValidationFailed;
		}

		$user = $this->findOrCreateUser($this->email);

		if ($this->app->users->contains($user)) {
			throw new UserAlreadyAdded;
		}

		$this->app->users()->attach($user);

		$this->app->load('users');
	}

	protected function findOrCreateUser($email)
	{
		if ($user = $this->users->findByEmail($email)) {
			return $user;
		}

		return $this->dispatch(new CreateUser($email));
	}
}
