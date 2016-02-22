<?php namespace Harmony\Http\Controllers\Web\Apps;

use Harmony\Applications\ApplicationsRepository;
use Harmony\Applications\Commands\CreateApplication;
use Harmony\Crashes\CrashesRepository;

use Illuminate\Http\Request;

class CrashesController extends Controller
{
	public function index(ApplicationsRepository $apps, CrashesRepository $crashes, Request $request, $slug)
	{
		$app = $this->getApp($apps, $slug);

		$filter = [
			'search'       => $request->input('search'),
			'order'        => $request->input('order', 'latest'),
			'showArchived' => $request->input('showArchived', false)
		];

		$crashes = $crashes->getFilteredForApplication($app, $filter);

		return view('apps.crashes')->with(compact('app', 'crashes', 'filter'));
	}

	public function show(ApplicationsRepository $apps, $appSlug, $crashId)
	{
		$crash = $this->getCrash($apps, $appSlug, $crashId);

		return view('apps.crash')->with([
			'app'      => $crash->application,
			'crash'    => $crash,
			'instance' => $crash->getLastInstance()
		]);
	}
}
