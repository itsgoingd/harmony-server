<?php namespace Harmony\Applications;

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;

class Application extends Model implements SluggableInterface, StaplerableInterface
{
	use EloquentTrait, SluggableTrait;

	protected $fillable = [
		'name', 'logo', 'apiKey', 'color', 'ownedBy'
	];

	protected $sluggable = [
		'build_from' => 'name', 'save_to' => 'slug'
	];

	public function __construct(array $attributes = array())
	{
		$this->hasAttachedFile('logo', [
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

	public function crashes()
	{
		return $this->hasMany(\Harmony\Crashes\Crash::class, 'applicationId');
	}

	public function owner()
	{
		return $this->belongsTo(\Harmony\Users\User::class, 'ownerId');
	}

	public function users()
	{
		return $this->belongsToMany(\Harmony\Users\User::class, 'applications_users', 'applicationId', 'userId')
			->withPivot([ 'emailNotifications' ]);
	}

	/**
	 * Methods
	 */

	public function getInitials()
	{
		if (strpos($this->name, ' ')) {
			$tokens = explode(' ', $this->name);

			return strtoupper($tokens[0][0]) . $tokens[1][0];
		} else {
			return ucwords(substr($this->name, 0, 2));
		}
	}
}
