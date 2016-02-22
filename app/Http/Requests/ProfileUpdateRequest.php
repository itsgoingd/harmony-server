<?php namespace Harmony\Http\Requests;

class ProfileUpdateRequest extends Request
{
	public function authorize()
	{
		return auth()->check();
	}

	public function rules()
	{
		return [
			'email'    => 'required|email|unique:users,email,' . auth()->user()->id,
			'avatar'   => 'image',
			'password' => 'confirmed'
		];
	}
}
