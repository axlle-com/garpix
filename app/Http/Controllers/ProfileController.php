<?php

namespace App\Http\Controllers;

use App\components\Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
    	$user = Auth::user();
    	return view('site.profile', ['user'	=>	$user]);
    }

    public function store(Request $request)
    {
        $post = Helper::clearArray($request->all());

        $user = Auth::user();
    	$this->validate($request, [
    		'name'	=>	'required',
    		'email' =>  [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
    		'avatar'	=>	'nullable|image'
    	]);


    	$user->edit($post);
    	$user->generatePassword($post['password']);
    	$user->uploadAvatar($request->file('avatar'));

    	return redirect()->back()->with('status', 'Профиль успешно обновлен');
    }
}
