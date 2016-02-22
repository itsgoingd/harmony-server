<?php namespace Harmony\Crashes;

use Illuminate\Database\Eloquent\Model;

class Crash extends Model
{
	protected $fillable = [
		'exception', 'message', 'fileName', 'lineNumber', 'hash'
	];

	protected $dates = [ 'archivedAt' ];

	protected $with = [ 'instances' ];

	/**
	 * Relations
	 */

	public function application()
	{
		return $this->belongsTo(\Harmony\Applications\Application::class, 'applicationId');
	}

	public function archiver()
	{
		return $this->belongsTo(\Harmony\Users\User::class, 'archivedBy');
	}

	public function comments()
	{
		return $this->hasMany(Comments\Comment::class, 'crashId');
	}

	public function instances()
	{
		return $this->hasMany(Instance::class, 'crashId');
	}

	/**
	 * Methods
	 */

	public function getLastInstance()
	{
		return $this->instances->last();
	}

	public function getShortFileName()
	{
		if ($this->application->path) {
			return str_replace("{$this->application->path}/", '', $this->fileName);
		} else {
			return $this->fileName;
		}
	}

	public function isArchived()
	{
		return $this->archivedAt !== null;
	}
}
