<?php namespace Harmony\Http\Controllers\Web\Apps;

use Harmony\Applications\ApplicationsRepository;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
	public function edit(ApplicationsRepository $apps, $slug)
	{
		$app = $this->getApp($apps, $slug);

		$user = $app->users->find($this->signedIn);

		return view('apps.settings')->with(compact('app', 'user'));
	}

	public function update(ApplicationsRepository $apps, Request $request, $slug)
	{
		$app = $this->getOwnedApp($apps, $slug);

		$apps->update($app, [
			'name' => $request->input('name'),
			'logo' => $request->file('logo')
		]);

		return redirect()->route('apps settings', [ 'app' => $app->slug ]);
	}
}
