<?php namespace Harmony\Http\Controllers\Web\Apps;

use Harmony\Applications\ApplicationsRepository;
use Harmony\Crashes\Commands\ArchiveCrash;

use Illuminate\Http\Request;

class CrashArchiveController extends Controller
{
	public function store(ApplicationsRepository $apps, Request $request, $appSlug, $crashId)
	{
		$crash = $this->getCrash($apps, $appSlug, $crashId);

		$this->dispatch(new ArchiveCrash($crash, $this->signedIn));

		return back();
	}
}
