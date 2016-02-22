<?php namespace Harmony\Users\Commands;

use Harmony\Support\ColorFormatConverter;
use Harmony\Users\UsersRepository;
use Harmony\Users\Exceptions\EmailAlreadyUsed;

use Illuminate\Contracts\Hashing\Hasher;

class CreateUser
{
	protected $email;
	protected $password;

	public function __construct($email, $password = null)
	{
		$this->email    = $email;
		$this->password = $password;
	}

	public function handle(UsersRepository $users, Hasher $hasher)
	{
		if ($user = $users->findByEmail($this->email)) {
			if ($user->password !== null) {
				throw new EmailAlreadyUsed;
			}

			$user->password = $hasher->make($this->password);
			$user->save();

			return $user;
		}

		return $users->store([
			'email'    => $this->email,
			'password' => $this->password ? $hasher->make($this->password) : null,
			'color'    => $this->generateColor()
		]);
	}

	protected function generateColor()
	{
		$h = rand(0, 359);
		$s = rand(50, 100);
		$l = rand(40, 50);

		list($r, $g, $b) = ColorFormatConverter::hslToRgb($h, $s, $l);

		return ColorFormatConverter::rgbToHex($r, $g, $b);
	}
}
