<?php namespace Harmony\Http\Controllers\Web;

use Harmony\Http\Controllers\Controller;
use Harmony\Http\Requests\SignInRequest;

class SignInController extends Controller
{
	public function showLoginForm()
	{
		return view('auth.login');
	}

	public function login(SignInRequest $request)
	{
		$credentials = [ 'email' => $request->input('email'), 'password' => $request->input('password') ];

		if (! auth()->attempt($credentials)) {
			return back()->with('error', 'Invalid credentials.');
		}

		return redirect()->intended(route('home'));
	}

	public function logout()
	{
		auth()->logout();

		return redirect()->home();
	}
}
