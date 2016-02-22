<?php namespace Harmony\Http\Controllers\Web;

use Harmony\Http\Controllers\Controller;
use Harmony\Http\Requests\ProfileUpdateRequest;
use Harmony\Users\Commands\UpdateProfile;

class ProfileController extends Controller
{
	public function show()
	{
		return view('user.profile');
	}

	public function edit()
	{
		return view('user.profile-edit');
	}

	public function update(ProfileUpdateRequest $request)
	{
		$this->dispatch(new UpdateProfile(
			$this->signedIn, $request->input('email'), $request->file('avatar'), $request->input('password')
		));

		return redirect()->route('profile');
	}
}
