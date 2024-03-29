<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieRequest extends FormRequest
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
			'title_ka'       => 'required|string|max:255',
			'title_en'       => 'required|string|max:255',
			'director_ka'    => 'required|string|max:255',
			'director_en'    => 'required|string|max:255',
			'description_ka' => 'required|string|max:255',
			'description_en' => 'required|string|max:255',
			'release_year'   => 'required|integer',
			'budget'         => 'required|integer',
			'genres'         => 'required|string',
			'image'          => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
		];
	}
}
