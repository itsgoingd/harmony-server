<?php namespace Harmony\Users\Commands;

use Harmony\Users\User;

use Illuminate\Contracts\Hashing\Hasher;

class UpdateProfile
{
	protected $user;
	protected $email;
	protected $avatar;
	protected $password;

	public function __construct(User $user, $email, $avatar = null, $password = null)
	{
		$this->user     = $user;
		$this->email    = $email;
		$this->avatar   = $avatar;
		$this->password = $password;
	}

	public function handle(Hasher $hasher)
	{
		$this->user->email = $this->email;

		if ($this->avatar) {
			$this->user->avatar = $this->avatar;
		}

		if ($this->password) {
			$this->user->password = $hasher->make($this->password);
		}

		$this->user->save();
	}
}
