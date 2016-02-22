<?php namespace Harmony\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $signedIn;

	public function __construct()
	{
		$this->signedIn = auth()->user();

		view()->composer('*', function($view)
		{
			$view->with('signedIn', $this->signedIn);

			$view->with('success', session()->get('success'));
			$view->with('error', session()->get('error'));
		});

		// Laravel shares the main app instance by default, we want to use the app variable for our own purposes
		view()->share('app', null);
	}
}
