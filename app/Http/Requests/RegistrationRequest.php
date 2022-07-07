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
			'username.required'  => __('username_required'),
			'username.unique'    => __('username_unique'),
			'email.required'     => __('email_required'),
			'email.email'        => __('email_invalid'),
			'email.unique'       => __('email_unique'),
			'password.required'  => __('password_required'),
			'password.confirmed' => __('password_confirmed'),
		];
	}
}
