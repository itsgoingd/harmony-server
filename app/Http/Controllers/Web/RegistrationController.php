<?php namespace Harmony\Http\Controllers\Web;

use Harmony\Http\Controllers\Controller;
use Harmony\Http\Requests\RegistrationRequest;
use Harmony\Users\Commands\CreateUser;
use Harmony\Users\Exceptions\EmailAlreadyUsed;

class RegistrationController extends Controller
{
	public function showRegistrationForm()
	{
		return view('auth.register');
	}

	public function register(RegistrationRequest $request)
	{
		try {
			$user = $this->dispatch(new CreateUser($request->input('email'), $request->input('password')));
		} catch (EmailAlreadyUsed $e) {
			return back()->with('error', 'Email address is already in use.');
		}

		auth()->login($user);

		return redirect()->intended(route('home'));
	}
}
