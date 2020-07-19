<?php

namespace App\Http\Controllers;

use App\Basket;
use App\components\Helper;
use App\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function registerForm()
    {
    	return view('site.register');
    }

    public function register(Request $request)
    {
        $post = Helper::clearArray($request->all());

    	$this->validate($request, [
    		'name'	=>	'required',
    		'email'	=>	'required|email|unique:users',
    		'password'	=>	'required'
    	]);

    	$user = User::create($post);

        return redirect()->route('login');
    }

    public function loginForm()
    {
    	return view('site.login');
    }

    public function login(Request $request)
    {
    	$this->validate($request, [
    		'email'	=>	'required|email',
    		'password'	=>	'required'
    	]);

    	if(Auth::attempt([
    		'email'	=>	$request->get('email'),
    		'password'	=>	$request->get('password')
    	]))
    	{

    	    Basket::toggleType(Auth::user()->id);
    		return redirect(RouteServiceProvider::HOME);
    	}

    	return redirect()->back()->with('status', 'Неправильный логин или пароль');
    }

    public function logout()
    {
    	Auth::logout();
    	return redirect()->route('login');
    }
}
