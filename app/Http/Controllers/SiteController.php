<?php namespace Harmony\Http\Controllers;

class SiteController extends Controller
{
	public function home()
	{
		if ($this->signedIn) {
			if ($application = $this->signedIn->applications->first()) {
				return redirect()->route('apps crashes', [ 'app' => $application->slug ]);
			}

			return redirect()->route('apps create');
		}

		return redirect()->route('sign-in form');
	}
}
