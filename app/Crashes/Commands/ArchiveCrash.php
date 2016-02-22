<?php namespace Harmony\Crashes\Commands;

use Harmony\Crashes\Crash;

class ArchiveCrash
{
	protected $crash;
	protected $signedIn;

	public function __construct(Crash $crash, $signedIn)
	{
		$this->crash    = $crash;
		$this->signedIn = $signedIn;
	}

	public function handle()
	{
		if ($this->crash->isArchived()) {
			return;
		}

		$this->crash->archivedAt = time();
		$this->crash->archivedBy = $this->signedIn->id;

		$this->crash->save();
	}
}
