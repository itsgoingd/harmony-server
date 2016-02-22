<?php namespace Harmony\Users;

use Harmony\Support\Repository;

class UsersRepository extends Repository
{
	protected $model = User::class;

	public function findByEmail($email)
	{
		return $this->where('email', $email)->first();
	}

	public function findByEmailWithPassword($email)
	{
		return $this->where('email', $email)->whereNotNull('password')->first();
	}
}
