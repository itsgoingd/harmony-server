<?php namespace Harmony\Crashes;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
	protected $table = 'crashes_email_notifications';

	public $timestamps = false;

	protected $fillable = [
		'crashId', 'userId', 'lastSentAt'
	];

	protected $dates = [
		'lastSentAt'
	];
}
