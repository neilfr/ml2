<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;
use App\Http\Requests\CreateUserRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    // use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/';

    // what is calling this function???
    public function showLoginForm()
    {
        $name = "Nobody!";
        if(Auth::User())
            $name=Auth::User()->name;
        return Inertia::render('Auth/Login', ['whoAmI' => $name]);
    }

    public function register()
    {
        return Inertia::render('Auth/Register');

    }

    protected function authenticated(Request $request, $user)
    {
        // flash("Welcome back {$user->name}!");

        return redirect()->route('home');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if(!$request->remember) $request->remember=false;
        $user = User::firstWhere('email', $credentials['email']);
        if (Auth::attempt($credentials, $request->remember)) {
            return redirect(route('foods.index'));
        } else {
            dd("NOT AUTHENTICATED!");
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('guest')->except('logout');
    // }
}
