<?php namespace Harmony\Users;

use Harmony\Applications\Application;

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements StaplerableInterface
{
	use EloquentTrait;

	protected $fillable = [
		'name', 'email', 'password', 'color'
	];

	protected $hidden = [
		'password', 'remember_token',
	];

	public function __construct(array $attributes = array())
	{
		$this->hasAttachedFile('avatar', [
			'styles' => [
				'large' => '256x256#',
				'small' => '64x64#'
			]
		]);

		parent::__construct($attributes);
	}

	/**
	 * Relations
	 */

	public function applications()
	{
		return $this->belongsToMany(\Harmony\Applications\Application::class, 'applications_users', 'userId', 'applicationId');
	}

	/**
	 * Methods
	 */

	public function getInitials()
	{
		$username = explode('@', $this->email)[0];

		$tokens = preg_split('/[.-]/', $username);

		if (count($tokens) < 2) {
			return ucwords(substr($username, 0, 2));
		}

		return strtoupper($tokens[0][0]) . $tokens[1][0];
	}

	public function isApplicationOwner(Application $app)
	{
		return $app->ownedBy === $this->id;
	}
}
