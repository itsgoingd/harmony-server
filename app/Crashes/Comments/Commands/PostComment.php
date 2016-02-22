<?php namespace Harmony\Crashes\Comments\Commands;

use Harmony\Crashes\Crash;
use Harmony\Users\User;

class PostComment
{
	protected $message;
	protected $crash;
	protected $poster;

	public function __construct($message, Crash $crash, User $poster = null)
	{
		$this->message = $message;
		$this->crash   = $crash;
		$this->poster  = $poster;
	}

	public function handle()
	{
		return $this->crash->comments()->create([
			'message'  => $this->message,
			'postedBy' => $this->poster ? $this->poster->id : null
		]);
	}
}
