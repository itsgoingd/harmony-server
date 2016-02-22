<?php namespace Harmony\Http\Requests;

class SignInRequest extends Request
{
	public function authorize()
	{
		return true;
	}

	public function rules()
	{
		return [
			'email'    => 'required|email',
			'password' => 'required'
		];
	}
}
