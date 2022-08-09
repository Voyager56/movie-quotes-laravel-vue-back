<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize()
	{
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'username' => 'required|unique:users',
			'email'    => 'required|email|unique:users',
			'password' => 'required|confirmed',
		];
	}

	public function messages()
	{
		return [
			'username.required'  => 'username_required',
			'username.unique'    => 'username_unique',
			'email.required'     => 'email_required',
			'email.email'        => 'email_email',
			'email.unique'       => 'email_unique',
			'password.required'  => 'password_required',
			'password.confirmed' => 'password_confirmed',
		];
	}
}
