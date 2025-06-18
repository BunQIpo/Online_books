<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\WelcomeController;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

// Socialite import removed as Google login functionality is no longer used
// use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    $this->middleware('guest')->except('logout');
    }


    /**
     * DEPRECATED: Google login functionality has been removed.
     * The following methods are kept for reference only but are no longer used by the application.
     */

    // public function redirectToGoogle(){
    //     return Socialite::driver('google')->redirect();
    // }

    /**
     * DEPRECATED: Google login functionality has been removed.
     * a call back function to get the user details
     *
     * This was adapted from a youtube tutorial by  Code for 4
     * youtube channel here:
     * https://www.youtube.com/watch?v=jIckLu1cKew&t=223s
     *
     *  @return \Illuminate\Http\Response
     */

    // public function handleGoogleCallback(){
    //  $userSocial = Socialite::driver('google')->user();
    //  $this->_registerOrLogin($userSocial);
    //  return redirect()->route('home');
    // }


    /**
     * DEPRECATED: Google login functionality has been removed.
     * Login a user or Register
     * a new user from data from google Api.
     * This was adapted from a youtube tutorial by  Code for 4
     * youtube channel here:
     * https://www.youtube.com/watch?v=jIckLu1cKew&t=223s
     *
     * @param  $data
     */

    // public function _registerOrLogin($data){
    // $user = User::where(['email' => $data->getEmail()])->first();
    // if(!$user) {
    // $user = new User();
    // $user->name = $data->name;
    // $user->username = $data->email;
    // $user->email = $data->email;
    // $user->save();
    // }
    // Auth::login($user);
    // }
}


