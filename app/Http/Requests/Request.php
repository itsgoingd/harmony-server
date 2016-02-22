<?php namespace Harmony\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exception\HttpResponseException;

abstract class Request extends FormRequest
{
	protected function failedValidation(Validator $validator)
	{
		$redirect = $this->redirector->to($this->getRedirectUrl())
			->withInput($this->except($this->dontFlash))
			->with('error', implode("\n", $validator->errors()->all()));

		throw new HttpResponseException($redirect);
	}
}
