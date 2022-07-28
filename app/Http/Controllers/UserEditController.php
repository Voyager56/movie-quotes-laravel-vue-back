<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rule;
use Illuminate\Http\Request;

class UserEditController extends Controller
{
    public function editProfile(Request $request)
    {
        $data = $request->validate([
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore(auth()->user()->id)],
            'email' =>  ['nullable', 'string', 'email', 'max:255', Rule::unique('users')->ignore(auth()->user()->id)],
            'password' => 'nullable|string|min:6|confirmed',
        ]);
        $image = $request->file('file')->store('public/images');
        $image = str_replace('public/', '', $image);

        $user = auth()->user();
        $user->update($data);
        $user->photo = 'http://127.0.0.1:8000/storage/' . $image;
        $user->save();
        return response()->json(['message' => 'Profile updated']);
    }
}