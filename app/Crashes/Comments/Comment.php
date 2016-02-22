<?php namespace Harmony\Crashes\Comments;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $table = 'crashes_comments';

	protected $fillable = [
		'message', 'postedBy'
	];

	/**
	 * Relations
	 */

	public function crash()
	{
		return $this->belongsTo(\Harmony\Crashes\Crash::class, 'crashId');
	}

	public function poster()
	{
		return $this->belongsTo(\Harmony\Users\User::class, 'postedBy');
	}
}
