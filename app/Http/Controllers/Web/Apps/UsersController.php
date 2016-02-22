<?php namespace Harmony\Http\Controllers\Web\Apps;

use Harmony\Applications\ApplicationsRepository;
use Harmony\Applications\Commands\AddUser;
use Harmony\Applications\Commands\DeleteUser;
use Harmony\Applications\Exceptions\UserAlreadyAdded;
use Harmony\Applications\Exceptions\ValidationFailed;

use Illuminate\Http\Request;

class UsersController extends Controller
{
	public function store(ApplicationsRepository $apps, Request $request, $slug)
	{
		$app = $this->getOwnedApp($apps, $slug);

		try {
			$this->dispatch(new AddUser($app, $request->input('email')));
		} catch (UserAlreadyAdded $e) {
			return response()->json([ 'message' => 'User already added.' ], 400);
		} catch (ValidationFailed $e) {
			return response()->json([ 'message' => 'Invalid email address.' ], 400);
		}

		return view('apps.partials.settings-users-list')->with([ 'app' => $app, 'users' => $app->users ]);
	}

	public function delete(ApplicationsRepository $apps, $slug, $userId)
	{
		$app = $this->getOwnedApp($apps, $slug);

		$this->dispatch(new DeleteUser($app, $userId));

		return view('apps.partials.settings-users-list')->with([ 'app' => $app, 'users' => $app->users ]);
	}
}
