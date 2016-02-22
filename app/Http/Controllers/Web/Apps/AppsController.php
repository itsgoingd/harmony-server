<?php namespace Harmony\Http\Controllers\Web\Apps;

use Harmony\Applications\Commands\CreateApplication;

use Illuminate\Http\Request;

class AppsController extends Controller
{
	public function create()
	{
		return view('apps.create');
	}

	public function store(Request $request)
	{
		$app = $this->dispatch(new CreateApplication($request->input('name'), $request->file('logo'), $this->signedIn));

		return redirect()->route('apps crashes', [ 'slug' => $app->slug ]);
	}
}
